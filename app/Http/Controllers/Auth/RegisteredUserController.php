<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Setting;
use App\Models\Transaction;
use App\Models\UserNotification;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        // Normalize mobile number before validation
        if ($request->has('mobile_number')) {
            $request->merge([
                'mobile_number' => $this->normalizeMobileNumber($request->mobile_number),
            ]);
        }

        if ($request->has('email') && $request->email) {
            $request->merge([
                'email' => strtolower($request->email),
            ]);
        }

        $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'mobile_number' => ['required', 'string', 'regex:/^(?:\+88|88)?(01[3-9]\d{8})$/', 'unique:'.User::class],
            'email' => ['nullable', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'referred_by' => ['nullable', 'string', 'exists:users,referral_code'],
            'device_fingerprint' => ['required', 'string'],
            // ── Honeypot: bots will fill this hidden field, humans won't ────
            'website_url' => ['nullable', 'string', 'max:0'], // Must be empty
        ], [
            'device_fingerprint.required' => 'Unable to verify device identity safely. Please disable adblockers/VPNs or try another browser.',
            'website_url.max' => 'Bot detected. If you are human, please try again.',
        ]);

        // ── Fraud checks AFTER validation (clean data only) ──────────────────
        $ip = $request->ip();
        $fingerprint = $request->device_fingerprint;
        $isLocalhost = in_array($ip, ['127.0.0.1', '::1']);

        if ($fingerprint && !$isLocalhost) {
            $isBlacklisted = \App\Models\Blacklist::where(function ($q) use ($ip, $fingerprint) {
                $q->where('type', 'ip')->where('value', $ip)
                    ->orWhere('type', 'device')->where('value', $fingerprint);
            })->exists();

            if ($isBlacklisted) {
                \App\Models\FraudLog::create([
                    'attempted_email' => $request->email,
                    'ip_address' => $ip,
                    'device_fingerprint' => $fingerprint,
                    'reason' => 'Blacklisted IP/Device',
                ]);

                return back()->withInput()->withErrors(['device_fingerprint' => 'Registration blocked due to security reasons.']);
            }

            if (User::where('device_fingerprint', $fingerprint)->exists()) {
                \App\Models\FraudLog::create([
                    'attempted_email' => $request->email,
                    'ip_address' => $ip,
                    'device_fingerprint' => $fingerprint,
                    'reason' => 'Device Duplicate',
                ]);

                return back()->withInput()->withErrors(['device_fingerprint' => 'Sorry, an account has already been registered from this device.']);
            }

            if (User::where('registration_ip', $ip)->orWhere('last_ip', $ip)->exists()) {
                \App\Models\FraudLog::create([
                    'attempted_email' => $request->email,
                    'ip_address' => $ip,
                    'device_fingerprint' => $fingerprint,
                    'reason' => 'Shared Network (IP)',
                ]);
            }
        }

        $referredBy = $request->referred_by ?? session('ref') ?? request()->cookie('ref');

        $referrer = null;
        if ($referredBy) {
            $referrer = User::where('referral_code', strtoupper($referredBy))->first();
        }

        // ── Wrap user creation + signup bonus in DB transaction ──────────────
        $user = \Illuminate\Support\Facades\DB::transaction(function () use ($request, $referrer) {
            $user = User::create([
                'full_name' => $request->full_name,
                'name' => $request->full_name,
                'email' => $request->email,
                'mobile_number' => $request->mobile_number,
                'password' => Hash::make($request->password),
                'referred_by' => $referrer ? $referrer->id : null,
                'device_fingerprint' => $request->device_fingerprint,
                'utm_source' => session('utm_source') ?? request()->cookie('utm_source'),
                'utm_medium' => session('utm_medium') ?? request()->cookie('utm_medium'),
                'utm_campaign' => session('utm_campaign') ?? request()->cookie('utm_campaign'),
                'utm_term' => session('utm_term') ?? request()->cookie('utm_term'),
                'utm_content' => session('utm_content') ?? request()->cookie('utm_content'),
            ]);

            if ($referrer) {
                app(\App\Services\ReferralService::class)->createReferral($user, $referrer);
            }

            // Apply Signup Bonus
            $signupBonus = (int) Setting::get('signup_bonus_points', 200);
            if ($signupBonus > 0) {
                $user->increment('points', $signupBonus);

                Transaction::create([
                    'user_id'           => $user->id,
                    'amount_points'     => $signupBonus,
                    'amount_bdt'        => 0,
                    'receivable_amount' => 0,
                    'fee_amount'        => 0,
                    'admin_profit'      => 0,
                    'user_reward'       => $signupBonus,
                    'type'              => 'signup_bonus',
                    'source'            => 'System',
                    'description'       => 'Welcome Bonus! Thank you for joining us.',
                    'status'            => 'approved',
                ]);

                UserNotification::create([
                    'user_id' => $user->id,
                    'type'    => 'success',
                    'title'   => '🎉 Welcome to EasyTSK!',
                    'message' => 'You received a sign-up bonus of ' . $signupBonus . ' points. Enjoy earning!',
                ]);

                session()->flash('welcome_bonus', $signupBonus);
            }

            return $user;
        });

        event(new Registered($user));

        Auth::login($user);

        session()->flash('fire_event', 'CompleteRegistration');

        return redirect(route('dashboard', absolute: false));
    }

    /**
     * Normalize a mobile number: strip spaces, dashes, parentheses, and ensure proper format.
     */
    private function normalizeMobileNumber(string $number): string
    {
        // Strip all non-digit characters except leading '+'
        $hasPlus = str_starts_with($number, '+');
        $number = preg_replace('/[^0-9]/', '', $number);
        
        if ($hasPlus) {
            $number = '+' . $number;
        }

        return $number;
    }
}

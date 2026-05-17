<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'login' => ['required', 'string'],
            'password' => ['required', 'string'],
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        $login = $this->input('login');

        // Normalize mobile number: strip spaces, dashes, parentheses
        if (!filter_var($login, FILTER_VALIDATE_EMAIL)) {
            $login = $this->normalizeMobileNumber($login);
        }

        $fieldType = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'mobile_number';

        // ── Account Lockout Check ──────────────────────────────────────────
        // Find user by login field to check lockout status BEFORE attempt
        $user = \App\Models\User::where($fieldType, $login)->first();
        if ($user && $user->locked_until && now()->lt($user->locked_until)) {
            $remaining = now()->diffInMinutes($user->locked_until);
            throw ValidationException::withMessages([
                'login' => "অ্যাকাউন্ট লক করা হয়েছে। অনুগ্রহ করে {$remaining} মিনিট পর আবার চেষ্টা করুন।",
            ]);
        }

        if (! Auth::attempt([$fieldType => $login, 'password' => $this->password], $this->boolean('remember'))) {
            RateLimiter::hit($this->throttleKey());

            // ── Track failed attempts for account lockout ──────────────────
            if ($user) {
                $user->increment('failed_login_attempts');
                $user->refresh();

                // Lock account after 5 consecutive failed attempts (15 min ban)
                if ($user->failed_login_attempts >= 5) {
                    $user->locked_until = now()->addMinutes(15);
                    $user->save();
                    \Illuminate\Support\Facades\Log::warning("Account locked: user #{$user->id} ({$user->email}) after 5 failed logins from IP {$this->ip()}");
                }
            }

            throw ValidationException::withMessages([
                'login' => trans('auth.failed'),
            ]);
        }

        // ── Reset failed attempts on successful login ──────────────────────
        $authUser = Auth::user();
        if ($authUser && ($authUser->failed_login_attempts > 0 || $authUser->locked_until)) {
            $authUser->failed_login_attempts = 0;
            $authUser->locked_until = null;
            $authUser->save();
        }

        RateLimiter::clear($this->throttleKey());
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'login' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
    public function throttleKey(): string
    {
        // Use REMOTE_ADDR (real server-level IP) instead of $this->ip()
        // which can be spoofed via X-Forwarded-For header.
        // Cloudflare passes real IP in HTTP_CF_CONNECTING_IP, but for
        // rate limiting we use REMOTE_ADDR which is the actual connecting IP
        // (Cloudflare edge IP when behind CF, or real client IP when direct).
        $realIp = $_SERVER['REMOTE_ADDR'] ?? $this->server->get('REMOTE_ADDR', '127.0.0.1');

        return Str::transliterate(Str::lower($this->string('login')).'|'.$realIp);
    }

    /**
     * Normalize a mobile number: strip spaces, dashes, parentheses, and ensure proper format.
     */
    private function normalizeMobileNumber(string $number): string
    {
        $hasPlus = str_starts_with($number, '+');
        $number = preg_replace('/[^0-9]/', '', $number);

        if ($hasPlus) {
            $number = '+' . $number;
        }

        return $number;
    }
}

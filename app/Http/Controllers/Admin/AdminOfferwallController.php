<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Offerwall;
use App\Models\PostbackLog;
use Illuminate\Http\Request;

class AdminOfferwallController extends Controller
{
    public function index()
    {
        $offerwalls = Offerwall::ordered()
            ->withCount('postbackLogs as total_postbacks')
            ->get()
            ->map(function ($ow) {
                $ow->success_count = PostbackLog::where('offerwall_id', $ow->id)->where('status', 'success')->count();
                $ow->failed_count  = PostbackLog::where('offerwall_id', $ow->id)->whereNotIn('status', ['success', 'duplicate'])->count();
                return $ow;
            });

        return view('admin.offerwalls.index', compact('offerwalls'));
    }

    public function create()
    {
        return view('admin.offerwalls.form', [
            'offerwall' => new Offerwall(),
            'isEdit'    => false,
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validateOfferwall($request);

        $data['hmac_fields']  = $this->parseJsonField($request->hmac_fields);
        $data['ip_whitelist'] = $this->parseJsonField($request->ip_whitelist);

        Offerwall::create($data);

        return redirect()->route('admin.offerwalls.index')
            ->with('success', '✅ নতুন অফারওয়াল তৈরি হয়েছে!');
    }

    public function edit(Offerwall $offerwall)
    {
        return view('admin.offerwalls.form', [
            'offerwall' => $offerwall,
            'isEdit'    => true,
        ]);
    }

    public function update(Request $request, Offerwall $offerwall)
    {
        $data = $this->validateOfferwall($request);

        $data['hmac_fields']  = $this->parseJsonField($request->hmac_fields);
        $data['ip_whitelist'] = $this->parseJsonField($request->ip_whitelist);

        $offerwall->update($data);

        return redirect()->route('admin.offerwalls.index')
            ->with('success', '✅ অফারওয়াল আপডেট হয়েছে!');
    }

    public function toggle(Offerwall $offerwall)
    {
        $offerwall->update(['is_active' => ! $offerwall->is_active]);

        $status = $offerwall->is_active ? 'সক্রিয়' : 'নিষ্ক্রিয়';
        return back()->with('success', "✅ {$offerwall->name} এখন {$status}।");
    }

    public function destroy(Offerwall $offerwall)
    {
        $name = $offerwall->name;
        $offerwall->delete();

        return redirect()->route('admin.offerwalls.index')
            ->with('success', "✅ {$name} ডিলিট হয়েছে।");
    }

    /**
     * Show postback logs for a specific offerwall.
     */
    public function logs(Offerwall $offerwall)
    {
        $logs = PostbackLog::where('offerwall_id', $offerwall->id)
            ->with('user')
            ->latest()
            ->paginate(50);

        return view('admin.offerwalls.logs', compact('offerwall', 'logs'));
    }

    // ─── Helpers ─────────────────────────────────────────────────────────────

    private function validateOfferwall(Request $request): array
    {
        return $request->validate([
            'name'                 => 'required|string|max:100',
            'display_name'         => 'required|string|max:100',
            'description'          => 'nullable|string|max:500',
            'icon_emoji'           => 'nullable|string|max:10',
            'color'                => 'required|string|max:30',
            'sort_order'           => 'required|integer|min:0',
            'iframe_url_template'  => 'nullable|string|max:1000',
            'widget_script'        => 'nullable|string',
            'display_mode'         => 'required|in:iframe,widget,both',
            'api_key'              => 'nullable|string|max:500',
            'secret_key'           => 'nullable|string|max:500',
            'postback_method'      => 'required|in:GET,POST,ANY',
            'security_type'        => 'required|in:hmac_sha256,secret_match,ip_whitelist,none',
            'security_field'       => 'nullable|string|max:100',
            'field_user_id'        => 'required|string|max:100',
            'field_reward'         => 'required|string|max:100',
            'field_transaction_id' => 'required|string|max:100',
            'field_campaign_id'    => 'nullable|string|max:100',
            'reward_type'          => 'required|in:usd,points,custom',
            'conversion_rate'      => 'required|numeric|min:0',
            'platform_share_pct'   => 'required|numeric|min:0|max:100',
            'requires_task_lock'   => 'nullable',
        ]);
    }

    /**
     * Prepare validated data — normalize requires_task_lock checkbox + display_mode default.
     */
    private function prepareData(array $validated, Request $request): array
    {
        $validated['requires_task_lock'] = $request->boolean('requires_task_lock');
        return $validated;
    }

    /**
     * Parse comma-separated or JSON string into array.
     */
    private function parseJsonField(?string $value): ?array
    {
        if (empty($value)) {
            return null;
        }

        // Try JSON decode first
        $decoded = json_decode($value, true);
        if (is_array($decoded)) {
            return $decoded;
        }

        // Fall back to comma-separated
        return array_map('trim', explode(',', $value));
    }
}

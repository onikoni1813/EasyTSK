<x-admin-layout>
    <div class="mb-10 flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div>
            <h1 class="text-3xl font-black text-white tracking-tighter uppercase italic">
                {{ $isEdit ? 'Edit' : 'New' }} <span class="text-primary-500">Offerwall</span>
            </h1>
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] mt-2 flex items-center gap-2">
                <span class="w-1.5 h-1.5 bg-primary-500 rounded-full animate-pulse"></span>
                {{ $isEdit ? 'Update integration config for '.$offerwall->name : 'Configure a new ad network integration' }}
            </p>
        </div>
        <a href="{{ route('admin.offerwalls.index') }}"
            class="bg-white/10 border border-white/5 px-5 py-3 rounded-2xl text-xs text-white font-bold hover:bg-white/20 transition-all inline-flex items-center gap-2 w-fit">
            ← Back to List
        </a>
    </div>

    @if ($errors->any())
        <div class="glass-card p-6 border-rose-500/20 bg-rose-500/5 mb-8">
            <ul class="space-y-1">
                @foreach ($errors->all() as $error)
                    <li class="text-[11px] font-bold text-rose-400 flex items-center gap-2">
                        <span class="w-1 h-1 bg-rose-500 rounded-full"></span> {{ $error }}
                    </li>
                @endforeach
            </ul>
        </div>
    @endif

    @if($isEdit)
        {{-- Show Postback URL for existing offerwall --}}
        <div class="glass-card p-6 mb-8 border-emerald-500/20" x-data="{ copied: false }">
            <div class="flex items-center gap-3 mb-3">
                <span class="text-xl">📡</span>
                <h4 class="text-[10px] font-black text-emerald-400 uppercase tracking-widest">Your Postback URL</h4>
            </div>
            <div class="flex items-center gap-3">
                <code class="text-sm font-mono text-emerald-300 bg-white/5 px-4 py-3 rounded-xl flex-1 break-all">{{ $offerwall->getPostbackUrl() }}</code>
                <button type="button"
                    @click="navigator.clipboard.writeText('{{ $offerwall->getPostbackUrl() }}'); copied = true; setTimeout(() => copied = false, 2000)"
                    class="premium-btn px-4 py-3 rounded-xl text-xs whitespace-nowrap"
                    :class="copied && '!bg-emerald-500'">
                    <span x-show="!copied">📋 Copy</span>
                    <span x-show="copied" x-cloak>✅ Copied!</span>
                </button>
            </div>
            <p class="text-[9px] font-bold text-slate-500 mt-3">এই URL টি এড নেটওয়ার্কের ড্যাশবোর্ডে Postback/Callback URL হিসেবে পেস্ট করুন।</p>
        </div>
    @endif

    <form action="{{ $isEdit ? route('admin.offerwalls.update', $offerwall) : route('admin.offerwalls.store') }}"
        method="POST" class="space-y-8">
        @csrf
        @if($isEdit) @method('PUT') @endif

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            {{-- ── LEFT: Identity & Display ──────────────────────────────── --}}
            <div class="space-y-8">
                <div class="glass-card p-8">
                    <div class="flex items-center gap-3 mb-8">
                        <div class="w-10 h-10 bg-primary-500/10 text-primary-500 rounded-xl flex items-center justify-center border border-primary-500/20">🏷️</div>
                        <h3 class="text-[11px] font-black text-white uppercase tracking-[0.2em]">Identity & Display</h3>
                    </div>
                    <div class="space-y-6">
                        <div class="grid grid-cols-2 gap-6">
                            <div>
                                <label class="block mb-3 text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Network Name *</label>
                                <input type="text" name="name" value="{{ old('name', $offerwall->name) }}" required placeholder="CPAGrip"
                                    class="w-full bg-white/5 border border-white/5 rounded-2xl p-4 text-white font-black text-sm focus:bg-white/10 focus:ring-primary-500 transition-all outline-none">
                            </div>
                            <div>
                                <label class="block mb-3 text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Display Name (ইউজার দেখবে) *</label>
                                <input type="text" name="display_name" value="{{ old('display_name', $offerwall->display_name) }}" required placeholder="বোনাস ওয়াল"
                                    class="w-full bg-white/5 border border-white/5 rounded-2xl p-4 text-white font-black text-sm focus:bg-white/10 focus:ring-primary-500 transition-all outline-none">
                            </div>
                        </div>
                        <div>
                            <label class="block mb-3 text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Description</label>
                            <textarea name="description" rows="2" placeholder="ছোট বর্ণনা..."
                                class="w-full bg-white/5 border border-white/5 rounded-2xl p-4 text-white text-sm focus:bg-white/10 transition-all outline-none resize-none">{{ old('description', $offerwall->description) }}</textarea>
                        </div>
                        <div class="grid grid-cols-3 gap-6">
                            <div>
                                <label class="block mb-3 text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Icon Emoji</label>
                                <input type="text" name="icon_emoji" value="{{ old('icon_emoji', $offerwall->icon_emoji ?? '🎁') }}" placeholder="🎁"
                                    class="w-full bg-white/5 border border-white/5 rounded-2xl p-4 text-white text-lg text-center outline-none">
                            </div>
                            <div>
                                <label class="block mb-3 text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Theme Color</label>
                                <select name="color"
                                    class="w-full bg-[#1e2433] border border-white/5 rounded-2xl p-4 text-white font-black text-sm outline-none">
                                    @foreach(['indigo', 'emerald', 'amber', 'rose', 'sky', 'violet', 'teal', 'orange', 'pink', 'cyan'] as $clr)
                                        <option value="{{ $clr }}" {{ old('color', $offerwall->color ?? 'indigo') === $clr ? 'selected' : '' }} class="bg-[#1e2433] text-white">{{ ucfirst($clr) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block mb-3 text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Sort Order</label>
                                <input type="number" name="sort_order" value="{{ old('sort_order', $offerwall->sort_order ?? 0) }}"
                                    class="w-full bg-white/5 border border-white/5 rounded-2xl p-4 text-white font-black text-sm outline-none">
                            </div>
                        </div>
                    </div>
                </div>


                {{-- ── Integration ──────────────────────────────────────────── --}}
                <div class="glass-card p-8" x-data="{ mode: '{{ old('display_mode', $offerwall->display_mode ?? 'iframe') }}' }">
                    <div class="flex items-center gap-3 mb-8">
                        <div class="w-10 h-10 bg-indigo-500/10 text-indigo-500 rounded-xl flex items-center justify-center border border-indigo-500/20">🔗</div>
                        <h3 class="text-[11px] font-black text-white uppercase tracking-[0.2em]">Integration Config</h3>
                    </div>
                    <div class="space-y-6">
                        {{-- Display Mode --}}
                        <div>
                            <label class="block mb-3 text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Display Mode *</label>
                            <div class="grid grid-cols-3 gap-3">
                                @foreach([
                                    ['value' => 'iframe',  'icon' => '🖥️',  'label' => 'iFrame Only'],
                                    ['value' => 'widget',  'icon' => '🧩',  'label' => 'Widget Only'],
                                    ['value' => 'both',    'icon' => '⚡',  'label' => 'iFrame + Widget'],
                                ] as $opt)
                                <label class="cursor-pointer">
                                    <input type="radio" name="display_mode" value="{{ $opt['value'] }}"
                                        x-model="mode"
                                        class="sr-only">
                                    <div class="flex flex-col items-center gap-2 p-4 rounded-2xl border transition-all text-center"
                                        :class="mode === '{{ $opt['value'] }}'
                                            ? 'border-primary-500/60 bg-primary-500/10 text-primary-400'
                                            : 'border-white/5 bg-white/5 text-slate-500 hover:border-white/20'">
                                        <span class="text-2xl">{{ $opt['icon'] }}</span>
                                        <span class="text-[9px] font-black uppercase tracking-widest">{{ $opt['label'] }}</span>
                                    </div>
                                </label>
                                @endforeach
                            </div>
                        </div>

                        {{-- iFrame URL (hidden when Widget Only) --}}
                        <div x-show="mode !== 'widget'" x-cloak>
                            <label class="block mb-3 text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">iFrame URL Template</label>
                            <input type="text" name="iframe_url_template" value="{{ old('iframe_url_template', $offerwall->iframe_url_template) }}"
                                placeholder="https://offers.example.com/?appid={api_key}&userid={user_id}"
                                class="w-full bg-white/5 border border-white/5 rounded-2xl p-4 text-white font-mono text-xs focus:bg-white/10 transition-all outline-none">
                            <p class="text-[9px] font-bold text-slate-500 mt-2 ml-1">ব্যবহারযোগ্য placeholders: <code class="text-primary-400">{user_id}</code>, <code class="text-primary-400">{api_key}</code></p>
                        </div>

                        {{-- Widget Script (hidden when iFrame Only) --}}
                        <div x-show="mode !== 'iframe'" x-cloak>
                            <div class="flex items-center gap-3 mb-3">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Widget Embed Script</label>
                                <span class="text-[8px] font-black bg-violet-500/20 text-violet-400 px-2 py-1 rounded-lg border border-violet-500/20 tracking-widest uppercase">notik.io / JS Widget</span>
                            </div>
                            <textarea name="widget_script" rows="7"
                                placeholder="<!-- Paste your widget script/HTML here -->&#10;<script src=&quot;https://notik.io/...&quot;></script>&#10;&#10;<!-- Use {user_id} as the user identifier placeholder -->"
                                class="w-full bg-white/5 border border-white/5 rounded-2xl p-4 text-white font-mono text-xs focus:bg-white/10 transition-all outline-none resize-y">{{ old('widget_script', $offerwall->widget_script) }}</textarea>
                            <p class="text-[9px] font-bold text-slate-500 mt-2 ml-1">
                                এখানে JS widget snippet বা সরাসরি HTML embed code পেস্ট করুন। User ID placeholder হিসেবে
                                <code class="text-violet-400">{user_id}</code> ব্যবহার করুন — এটি স্বয়ংক্রিয়ভাবে replace হবে।
                            </p>
                        </div>

                        <div class="grid grid-cols-2 gap-6">
                            <div>
                                <label class="block mb-3 text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">API Key</label>
                                <input type="text" name="api_key" value="{{ old('api_key', $offerwall->api_key) }}" placeholder="your-api-key"
                                    class="w-full bg-white/5 border border-white/5 rounded-2xl p-4 text-white font-mono text-xs outline-none">
                            </div>
                            <div>
                                <label class="block mb-3 text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Secret Key</label>
                                <input type="text" name="secret_key" value="{{ old('secret_key', $offerwall->secret_key) }}" placeholder="your-secret-key"
                                    class="w-full bg-white/5 border border-white/5 rounded-2xl p-4 text-white font-mono text-xs outline-none">
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            {{-- ── RIGHT: Postback & Reward ──────────────────────────────── --}}
            <div class="space-y-8">
                {{-- ── Security ─────────────────────────────────────────────── --}}
                <div class="glass-card p-8">
                    <div class="flex items-center gap-3 mb-8">
                        <div class="w-10 h-10 bg-amber-500/10 text-amber-500 rounded-xl flex items-center justify-center border border-amber-500/20">🔒</div>
                        <h3 class="text-[11px] font-black text-white uppercase tracking-[0.2em]">Postback Security</h3>
                    </div>
                    <div class="space-y-6">
                        <div class="grid grid-cols-2 gap-6">
                            <div>
                                <label class="block mb-3 text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Postback Method</label>
                                <select name="postback_method" class="w-full bg-[#1e2433] border border-white/5 rounded-2xl p-4 text-white font-black text-sm outline-none">
                                    @foreach(['ANY', 'GET', 'POST'] as $m)
                                        <option value="{{ $m }}" {{ old('postback_method', $offerwall->postback_method ?? 'ANY') === $m ? 'selected' : '' }} class="bg-[#1e2433] text-white">{{ $m }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block mb-3 text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Security Type *</label>
                                <select name="security_type" class="w-full bg-[#1e2433] border border-white/5 rounded-2xl p-4 text-white font-black text-sm outline-none">
                                    <option value="secret_match" {{ old('security_type', $offerwall->security_type ?? 'secret_match') === 'secret_match' ? 'selected' : '' }} class="bg-[#1e2433] text-white">Secret Match</option>
                                    <option value="hmac_sha256" {{ old('security_type', $offerwall->security_type) === 'hmac_sha256' ? 'selected' : '' }} class="bg-[#1e2433] text-white">HMAC-SHA256</option>
                                    <option value="ip_whitelist" {{ old('security_type', $offerwall->security_type) === 'ip_whitelist' ? 'selected' : '' }} class="bg-[#1e2433] text-white">IP Whitelist</option>
                                    <option value="none" {{ old('security_type', $offerwall->security_type) === 'none' ? 'selected' : '' }} class="bg-[#1e2433] text-white">None (No verification)</option>
                                </select>
                            </div>
                        </div>
                        <div>
                            <label class="block mb-3 text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Security Field Name</label>
                            <input type="text" name="security_field" value="{{ old('security_field', $offerwall->security_field ?? 'secret') }}" placeholder="secret"
                                class="w-full bg-white/5 border border-white/5 rounded-2xl p-4 text-white font-mono text-xs outline-none">
                            <p class="text-[9px] font-bold text-slate-500 mt-2 ml-1">নেটওয়ার্ক যে request field-এ signature/secret পাঠায় (e.g., "secret", "signature", "hash")</p>
                        </div>
                        <div>
                            <label class="block mb-3 text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">HMAC Fields (comma-separated)</label>
                            <input type="text" name="hmac_fields" value="{{ old('hmac_fields', is_array($offerwall->hmac_fields) ? implode(', ', $offerwall->hmac_fields) : '') }}" placeholder="user_id, campaign_id, reward"
                                class="w-full bg-white/5 border border-white/5 rounded-2xl p-4 text-white font-mono text-xs outline-none">
                            <p class="text-[9px] font-bold text-slate-500 mt-2 ml-1">HMAC-SHA256 এর জন্য কোন ফিল্ডগুলো concatenate করে hash বানাবে</p>
                        </div>
                        <div>
                            <label class="block mb-3 text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">IP Whitelist (comma-separated)</label>
                            <input type="text" name="ip_whitelist" value="{{ old('ip_whitelist', is_array($offerwall->ip_whitelist) ? implode(', ', $offerwall->ip_whitelist) : '') }}" placeholder="1.2.3.4, 5.6.7.8"
                                class="w-full bg-white/5 border border-white/5 rounded-2xl p-4 text-white font-mono text-xs outline-none">
                        </div>
                    </div>
                </div>

                {{-- ── Field Mapping ────────────────────────────────────────── --}}
                <div class="glass-card p-8">
                    <div class="flex items-center gap-3 mb-8">
                        <div class="w-10 h-10 bg-emerald-500/10 text-emerald-500 rounded-xl flex items-center justify-center border border-emerald-500/20">🗺️</div>
                        <h3 class="text-[11px] font-black text-white uppercase tracking-[0.2em]">Field Mapping & Reward</h3>
                    </div>
                    <div class="space-y-6">
                        <p class="text-[9px] font-bold text-slate-500 bg-white/5 p-3 rounded-xl border border-white/5 leading-relaxed">
                            প্রতিটি নেটওয়ার্ক ভিন্ন ভিন্ন parameter name ব্যবহার করে। নেটওয়ার্কের ডকুমেন্টেশন দেখে সঠিক field name দিন।
                        </p>
                        <div class="grid grid-cols-2 gap-6">
                            <div>
                                <label class="block mb-3 text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">User ID Field *</label>
                                <input type="text" name="field_user_id" value="{{ old('field_user_id', $offerwall->field_user_id ?? 'user_id') }}" required placeholder="user_id"
                                    class="w-full bg-white/5 border border-white/5 rounded-2xl p-4 text-white font-mono text-xs outline-none">
                            </div>
                            <div>
                                <label class="block mb-3 text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Reward Field *</label>
                                <input type="text" name="field_reward" value="{{ old('field_reward', $offerwall->field_reward ?? 'reward') }}" required placeholder="reward"
                                    class="w-full bg-white/5 border border-white/5 rounded-2xl p-4 text-white font-mono text-xs outline-none">
                            </div>
                            <div>
                                <label class="block mb-3 text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Transaction ID Field *</label>
                                <input type="text" name="field_transaction_id" value="{{ old('field_transaction_id', $offerwall->field_transaction_id ?? 'transaction_id') }}" required placeholder="transaction_id"
                                    class="w-full bg-white/5 border border-white/5 rounded-2xl p-4 text-white font-mono text-xs outline-none">
                            </div>
                            <div>
                                <label class="block mb-3 text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Campaign ID Field</label>
                                <input type="text" name="field_campaign_id" value="{{ old('field_campaign_id', $offerwall->field_campaign_id ?? 'campaign_id') }}" placeholder="campaign_id"
                                    class="w-full bg-white/5 border border-white/5 rounded-2xl p-4 text-white font-mono text-xs outline-none">
                            </div>
                        </div>

                        <div class="pt-6 border-t border-white/5 grid grid-cols-3 gap-6">
                            <div>
                                <label class="block mb-3 text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Reward Type *</label>
                                <select name="reward_type" class="w-full bg-[#1e2433] border border-white/5 rounded-2xl p-4 text-white font-black text-sm outline-none">
                                    <option value="usd" {{ old('reward_type', $offerwall->reward_type ?? 'usd') === 'usd' ? 'selected' : '' }} class="bg-[#1e2433] text-white">USD ($) — নেটওয়ার্ক ডলারে রিওয়ার্ড পাঠায়</option>
                                    <option value="points" {{ old('reward_type', $offerwall->reward_type) === 'points' ? 'selected' : '' }} class="bg-[#1e2433] text-white">Points — নেটওয়ার্ক পয়েন্টে রিওয়ার্ড পাঠায়</option>
                                    <option value="custom" {{ old('reward_type', $offerwall->reward_type) === 'custom' ? 'selected' : '' }} class="bg-[#1e2433] text-white">Custom — raw value = আমাদের পয়েন্ট</option>
                                </select>
                            </div>
                            <div>
                                <label class="block mb-3 text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Conversion Rate *</label>
                                <input type="number" step="0.0001" name="conversion_rate" value="{{ old('conversion_rate', $offerwall->conversion_rate ?? 11000) }}" required
                                    class="w-full bg-white/5 border border-white/5 rounded-2xl p-4 text-white font-black text-sm outline-none">
                                <div class="mt-2 ml-1 space-y-0.5">
                                    <p class="text-[9px] font-bold text-slate-500">নেটওয়ার্কের রিওয়ার্ড ইউনিট → আমাদের পয়েন্ট</p>
                                    <p class="text-[9px] font-bold text-amber-500/80">💡 আপনার প্ল্যাটফর্ম: ১০০ পয়েন্ট = ৳১</p>
                                    <p class="text-[9px] font-bold text-slate-600">USD টাইপ: $১ ≈ ৳১১০ = ১১,০০০ পয়েন্ট → rate = 11000</p>
                                    <p class="text-[9px] font-bold text-slate-600">Points টাইপ: তাদের ১ পয়েন্ট = আমাদের কত পয়েন্ট</p>
                                </div>
                            </div>
                            <div>
                                <label class="block mb-3 text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Platform Share % *</label>
                                <input type="number" step="0.01" name="platform_share_pct" value="{{ old('platform_share_pct', $offerwall->platform_share_pct ?? 20) }}" required
                                    class="w-full bg-white/5 border border-white/5 rounded-2xl p-4 text-white font-black text-sm outline-none">
                                <p class="text-[9px] font-bold text-slate-500 mt-2 ml-1">মোট পয়েন্টের কত % প্ল্যাটফর্ম রাখবে (বাকিটা ইউজার পাবে)</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-3 bg-white/5 p-4 rounded-2xl border border-white/5">
                            <input type="checkbox" name="requires_task_lock" value="1"
                                {{ old('requires_task_lock', $offerwall->requires_task_lock ?? true) ? 'checked' : '' }}
                                class="w-5 h-5 bg-dark border-white/10 rounded focus:ring-primary-500 text-primary-500">
                            <label class="text-sm font-bold text-white">🔒 Task Lock (mandatory tasks কমপ্লিট না করলে এই offerwall ব্লক থাকবে)</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex gap-4 justify-end">
            <a href="{{ route('admin.offerwalls.index') }}"
                class="bg-white/10 border border-white/5 px-8 py-4 rounded-2xl text-xs text-white font-bold hover:bg-white/20 transition-all">
                Cancel
            </a>
            <button type="submit" class="premium-btn px-8 py-4 rounded-2xl text-xs">
                {{ $isEdit ? '💾 Update Offerwall' : '✅ Create Offerwall' }}
            </button>
        </div>
    </form>
</x-admin-layout>

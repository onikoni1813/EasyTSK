@php $m = $method; @endphp

@if($errors->any())
    <div class="mb-6 p-4 bg-rose-500/10 border border-rose-500/20 rounded-2xl text-rose-400 text-xs font-bold space-y-1">
        @foreach($errors->all() as $error)
            <p>• {{ $error }}</p>
        @endforeach
    </div>
@endif

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    {{-- Left: Main Info --}}
    <div class="lg:col-span-2 space-y-8">
        <div class="glass-card p-8 space-y-6">
            <h3 class="text-[11px] font-black text-white uppercase tracking-[0.2em]">⚙️ Method Identity</h3>

            @if(!$m)
            {{-- Name only for create --}}
            <div>
                <label class="block mb-2 text-[10px] font-black text-slate-400 uppercase tracking-widest">
                    Unique Key <span class="text-rose-400">*</span>
                    <span class="text-slate-600 normal-case font-medium ml-2">(lowercase, no spaces — e.g. bkash, nagad, upay)</span>
                </label>
                <input type="text" name="name" value="{{ old('name') }}" required
                    pattern="[a-z0-9_]+"
                    placeholder="e.g. upay"
                    class="w-full bg-white/5 border border-white/10 rounded-2xl p-4 text-white font-bold text-sm focus:border-primary-500 focus:bg-white/10 transition-all">
            </div>
            @endif

            <div class="grid grid-cols-2 gap-6">
                <div>
                    <label class="block mb-2 text-[10px] font-black text-slate-400 uppercase tracking-widest">Display Label <span class="text-rose-400">*</span></label>
                    <input type="text" name="label" value="{{ old('label', $m?->label) }}" required
                        placeholder="e.g. Bkash"
                        class="w-full bg-white/5 border border-white/10 rounded-2xl p-4 text-white font-bold text-sm focus:border-primary-500 focus:bg-white/10 transition-all">
                </div>
                <div>
                    <label class="block mb-2 text-[10px] font-black text-slate-400 uppercase tracking-widest">Icon Emoji <span class="text-rose-400">*</span></label>
                    <input type="text" name="icon_emoji" value="{{ old('icon_emoji', $m?->icon_emoji ?? '💳') }}" required
                        maxlength="4"
                        class="w-full bg-white/5 border border-white/10 rounded-2xl p-4 text-white font-bold text-2xl focus:border-primary-500 focus:bg-white/10 transition-all text-center">
                </div>
            </div>

            <div>
                <label class="block mb-2 text-[10px] font-black text-slate-400 uppercase tracking-widest">UI Color Theme</label>
                <div class="grid grid-cols-4 gap-3">
                    @foreach(['primary' => 'Violet', 'pink' => 'Pink', 'orange' => 'Orange', 'sky' => 'Sky', 'emerald' => 'Green', 'amber' => 'Amber', 'purple' => 'Purple', 'rose' => 'Rose'] as $color => $colorLabel)
                    <label class="cursor-pointer">
                        <input type="radio" name="color_class" value="{{ $color }}" class="hidden peer"
                            {{ old('color_class', $m?->color_class ?? 'primary') === $color ? 'checked' : '' }}>
                        <span class="block text-center p-3 rounded-xl border-2 border-white/5 peer-checked:border-primary-500 text-[10px] font-black uppercase text-slate-400 peer-checked:text-white transition-all hover:bg-white/5">
                            {{ $colorLabel }}
                        </span>
                    </label>
                    @endforeach
                </div>
            </div>

            <div>
                <label class="block mb-2 text-[10px] font-black text-slate-400 uppercase tracking-widest">User Instructions (Optional)</label>
                <textarea name="instructions" rows="2"
                    placeholder="ব্যবহারকারীকে দেখানো হবে..."
                    class="w-full bg-white/5 border border-white/10 rounded-2xl p-4 text-white font-medium text-sm focus:border-primary-500 focus:bg-white/10 transition-all">{{ old('instructions', $m?->instructions) }}</textarea>
            </div>
        </div>

        {{-- Account Config --}}
        <div class="glass-card p-8 space-y-6">
            <h3 class="text-[11px] font-black text-white uppercase tracking-[0.2em]">📱 Account Field Config</h3>
            <div class="grid grid-cols-2 gap-6">
                <div>
                    <label class="block mb-2 text-[10px] font-black text-slate-400 uppercase tracking-widest">Field Label</label>
                    <input type="text" name="account_label" value="{{ old('account_label', $m?->account_label ?? 'একাউন্ট নম্বর') }}"
                        class="w-full bg-white/5 border border-white/10 rounded-2xl p-4 text-white font-bold text-sm focus:border-primary-500 focus:bg-white/10 transition-all">
                </div>
                <div>
                    <label class="block mb-2 text-[10px] font-black text-slate-400 uppercase tracking-widest">Placeholder</label>
                    <input type="text" name="account_placeholder" value="{{ old('account_placeholder', $m?->account_placeholder ?? '017XXXXXXXX') }}"
                        class="w-full bg-white/5 border border-white/10 rounded-2xl p-4 text-white font-bold text-sm focus:border-primary-500 focus:bg-white/10 transition-all">
                </div>
                <div>
                    <label class="block mb-2 text-[10px] font-black text-slate-400 uppercase tracking-widest">Min Length</label>
                    <input type="number" name="account_min_length" value="{{ old('account_min_length', $m?->account_min_length ?? 11) }}" min="1"
                        class="w-full bg-white/5 border border-white/10 rounded-2xl p-4 text-white font-bold text-sm focus:border-primary-500 focus:bg-white/10 transition-all">
                </div>
                <div>
                    <label class="block mb-2 text-[10px] font-black text-slate-400 uppercase tracking-widest">Max Length</label>
                    <input type="number" name="account_max_length" value="{{ old('account_max_length', $m?->account_max_length ?? 14) }}" min="1"
                        class="w-full bg-white/5 border border-white/10 rounded-2xl p-4 text-white font-bold text-sm focus:border-primary-500 focus:bg-white/10 transition-all">
                </div>
            </div>
        </div>
    </div>

    {{-- Right: Settings --}}
    <div class="space-y-8">
        <div class="glass-card p-8 space-y-6">
            <h3 class="text-[11px] font-black text-white uppercase tracking-[0.2em]">💰 Pricing</h3>

            <div>
                <label class="block mb-2 text-[10px] font-black text-slate-400 uppercase tracking-widest">Minimum Amount (BDT)</label>
                <input type="number" name="min_amount" value="{{ old('min_amount', $m?->min_amount ?? 50) }}" min="1" step="1" required
                    class="w-full bg-white/5 border border-white/10 rounded-2xl p-4 text-white font-bold text-sm focus:border-primary-500 focus:bg-white/10 transition-all">
            </div>

            <div>
                <label class="block mb-2 text-[10px] font-black text-slate-400 uppercase tracking-widest">Charge Type</label>
                <div class="grid grid-cols-2 gap-3">
                    <label class="cursor-pointer">
                        <input type="radio" name="charge_type" value="percent" class="hidden peer"
                            {{ old('charge_type', $m?->charge_type ?? 'percent') === 'percent' ? 'checked' : '' }}>
                        <span class="block text-center p-4 rounded-xl border-2 border-white/5 peer-checked:border-primary-500 peer-checked:bg-primary-500/10 text-[10px] font-black uppercase text-slate-400 peer-checked:text-primary-400 transition-all">
                            % Percent
                        </span>
                    </label>
                    <label class="cursor-pointer">
                        <input type="radio" name="charge_type" value="fixed" class="hidden peer"
                            {{ old('charge_type', $m?->charge_type) === 'fixed' ? 'checked' : '' }}>
                        <span class="block text-center p-4 rounded-xl border-2 border-white/5 peer-checked:border-amber-500 peer-checked:bg-amber-500/10 text-[10px] font-black uppercase text-slate-400 peer-checked:text-amber-400 transition-all">
                            ৳ Fixed
                        </span>
                    </label>
                </div>
            </div>

            <div>
                <label class="block mb-2 text-[10px] font-black text-slate-400 uppercase tracking-widest">Charge Value <span class="text-slate-600">(0 = free)</span></label>
                <input type="number" name="charge_value" value="{{ old('charge_value', $m?->charge_value ?? 0) }}" min="0" step="0.01"
                    class="w-full bg-white/5 border border-white/10 rounded-2xl p-4 text-white font-bold text-sm focus:border-primary-500 focus:bg-white/10 transition-all">
            </div>

            <div>
                <label class="block mb-2 text-[10px] font-black text-slate-400 uppercase tracking-widest">Sort Order</label>
                <input type="number" name="sort_order" value="{{ old('sort_order', $m?->sort_order ?? 10) }}" min="0"
                    class="w-full bg-white/5 border border-white/10 rounded-2xl p-4 text-white font-bold text-sm focus:border-primary-500 focus:bg-white/10 transition-all">
            </div>
        </div>

        {{-- Active Toggle --}}
        <div class="glass-card p-8">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-black text-white">Active Status</p>
                    <p class="text-[10px] font-bold text-slate-500 mt-1">ইউজাররা এটি দেখতে পাবে</p>
                </div>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" class="sr-only peer"
                        {{ old('is_active', $m?->is_active ?? true) ? 'checked' : '' }}>
                    <div class="w-12 h-6 bg-white/10 rounded-full peer peer-checked:bg-emerald-500
                        after:content-[''] after:absolute after:top-1 after:left-1
                        after:bg-white after:rounded-full after:h-4 after:w-4
                        after:transition-all peer-checked:after:translate-x-6"></div>
                </label>
            </div>
        </div>

        <button type="submit" class="w-full premium-btn py-5 rounded-[24px] text-sm">
            {{ $m ? '✅ আপডেট করুন' : '✅ তৈরি করুন' }}
        </button>
    </div>
</div>

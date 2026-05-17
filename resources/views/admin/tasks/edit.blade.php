<x-admin-layout>
    <!-- Header Section -->
    <div class="mb-10 flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div>
            <h1 class="text-3xl font-black text-white tracking-tighter uppercase italic">Mission <span
                    class="text-primary-500">Refactor</span></h1>
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] mt-2 flex items-center gap-2">
                <span class="w-1.5 h-1.5 bg-primary-500 rounded-full animate-pulse"></span>
                Modify operational protocol: {{ $task->title }}
            </p>
        </div>
        <a href="{{ route('admin.tasks.index') }}"
            class="px-6 py-2.5 bg-white/5 border border-white/10 rounded-xl text-[10px] font-black text-slate-400 hover:text-white transition-all uppercase tracking-widest">
            Discard Changes
        </a>
    </div>

    <form action="{{ route('admin.tasks.update', $task) }}" method="POST" class="space-y-8">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Core Parameters -->
            <div class="lg:col-span-2 space-y-8">
                <div class="glass-card p-8 relative overflow-hidden">
                    <div class="flex items-center gap-3 mb-8">
                        <div
                            class="w-10 h-10 bg-primary-500/10 text-primary-500 rounded-xl flex items-center justify-center border border-primary-500/20">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                </path>
                            </svg>
                        </div>
                        <h3 class="text-[11px] font-black text-white uppercase tracking-[0.2em]">Primary Protocol
                            Identity</h3>
                    </div>

                    <div class="space-y-6">
                        <div>
                            <label for="title"
                                class="block mb-3 text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 leading-none">Mission
                                Header (Title)</label>
                            <input type="text" id="title" name="title" value="{{ $task->title }}"
                                placeholder="e.g. Subscribe to YouTube Channel..."
                                class="w-full bg-white/5 border border-white/5 rounded-2xl p-4 text-white font-black text-sm focus:bg-white/10 focus:ring-primary-500 transition-all placeholder:text-slate-400"
                                required>
                        </div>

                        <div>
                            <label for="description"
                                class="block mb-3 text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 leading-none">Operational
                                Instructions</label>
                            <textarea id="description" name="description" rows="6"
                                placeholder="Describe the sequence of actions the agent must execute..."
                                class="w-full bg-white/5 border border-white/5 rounded-2xl p-5 text-white font-bold text-sm focus:bg-white/10 transition-all placeholder:text-slate-400"
                                required>{{ $task->description }}</textarea>
                        </div>

                        <div>
                            <label for="external_link"
                                class="block mb-3 text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 leading-none">External
                                Signal Target (URL)</label>
                            <div class="relative group">
                                <span
                                    class="absolute left-5 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-primary-500 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.828a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1">
                                        </path>
                                    </svg>
                                </span>
                                <input type="url" id="external_link" name="external_link"
                                    value="{{ $task->external_link }}" placeholder="https://..."
                                    class="w-full bg-white/5 border border-white/5 rounded-2xl p-4 pl-12 text-white font-black text-sm focus:bg-white/10 focus:ring-primary-500 transition-all placeholder:text-slate-400">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Proof Logic -->
                <div class="glass-card p-8">
                    <div class="flex items-center gap-3 mb-8">
                        <div
                            class="w-10 h-10 bg-amber-500/10 text-amber-500 rounded-xl flex items-center justify-center border border-amber-500/20">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-[11px] font-black text-white uppercase tracking-[0.2em]">Validation Protocols
                        </h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div
                            class="p-6 bg-white/5 border border-white/5 rounded-[32px] flex items-center justify-between group">
                            <div>
                                <h4
                                    class="text-[10px] font-black text-white uppercase tracking-tight group-hover:text-primary-500 transition-colors">
                                    Visual Evidence</h4>
                                <p class="text-[8px] font-bold text-slate-400 uppercase tracking-widest mt-1">Require
                                    Screenshot Proof</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input id="requires_image_proof" name="requires_image_proof" type="checkbox" value="1"
                                    {{ $task->requires_image_proof ? 'checked' : '' }} class="sr-only peer">
                                <div
                                    class="w-10 h-5 bg-white/10 rounded-full peer peer-checked:bg-primary-500 after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-slate-400 after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:after:translate-x-5">
                                </div>
                            </label>
                        </div>

                        <div
                            class="p-6 bg-white/5 border border-white/5 rounded-[32px] flex items-center justify-between group">
                            <div>
                                <h4
                                    class="text-[10px] font-black text-white uppercase tracking-tight group-hover:text-primary-500 transition-colors">
                                    Digital Signature</h4>
                                <p class="text-[8px] font-bold text-slate-400 uppercase tracking-widest mt-1">Require
                                    Text/Code Evidence</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input id="requires_text_proof" name="requires_text_proof" type="checkbox" value="1" {{ $task->requires_text_proof ? 'checked' : '' }} class="sr-only peer">
                                <div
                                    class="w-10 h-5 bg-white/10 rounded-full peer peer-checked:bg-primary-500 after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-slate-400 after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:after:translate-x-5">
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Static Secret Code (for YouTube/Facebook video tasks) -->
                    <div class="mt-6 p-6 bg-white/5 border border-white/5 rounded-[32px]">
                        <div class="flex items-start gap-4">
                            <div
                                class="w-10 h-10 bg-violet-500/10 text-violet-500 rounded-xl flex items-center justify-center border border-violet-500/20 shrink-0">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z">
                                    </path>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <h4 class="text-[10px] font-black text-white uppercase tracking-tight">Static Secret
                                    Code</h4>
                                <p class="text-[8px] font-bold text-slate-400 uppercase tracking-widest mt-1 mb-3">
                                    For YouTube/Facebook videos — set a fixed code that users submit after watching.
                                    Leave empty for auto-generated codes (subdomain tasks).
                                </p>
                                <input type="text" id="secret_code" name="secret_code" value="{{ $task->secret_code }}"
                                    placeholder="e.g. YT2026XK9" maxlength="64"
                                    class="w-full bg-white/5 border border-white/5 rounded-2xl p-4 text-white font-black text-sm focus:bg-white/10 focus:ring-primary-500 transition-all placeholder:text-slate-400 font-mono tracking-widest">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Deployment Configs -->
            <div class="space-y-8">
                <div class="glass-card p-8">
                    <div class="flex items-center gap-3 mb-8">
                        <div
                            class="w-10 h-10 bg-indigo-500/10 text-indigo-500 rounded-xl flex items-center justify-center border border-indigo-500/20">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-[11px] font-black text-white uppercase tracking-[0.2em]">Saturation & Yield</h3>
                    </div>

                    <div class="space-y-6">
                        <div>
                            <label for="type"
                                class="block mb-3 text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 leading-none">Operational
                                Target Engine</label>
                            <select id="type" name="type"
                                class="w-full bg-white/5 border border-white/5 rounded-2xl p-4 text-white font-black text-xs focus:bg-white/10 transition-all appearance-none cursor-pointer">
                                <option value="custom" {{ $task->type === 'custom' ? 'selected' : '' }}
                                    class="bg-dark text-white">Standard Network Task</option>
                                <option value="timewall" {{ $task->type === 'timewall' ? 'selected' : '' }}
                                    class="bg-dark text-white">TimeWall Integrated</option>
                                <option value="adsterra" {{ $task->type === 'adsterra' ? 'selected' : '' }}
                                    class="bg-dark text-white">Adsterra Gateway</option>
                                <option value="youtube" {{ $task->type === 'youtube' ? 'selected' : '' }}
                                    class="bg-dark text-white">YouTube Engagement</option>
                                <option value="facebook" {{ $task->type === 'facebook' ? 'selected' : '' }}
                                    class="bg-dark text-white">Facebook Engagement</option>
                                <option value="telegram" {{ $task->type === 'telegram' ? 'selected' : '' }}
                                    class="bg-dark text-white">Telegram Engagement</option>
                            </select>
                        </div>

                        <div>
                            <label for="points"
                                class="block mb-3 text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 leading-none">Agent
                                Bounty (PTS)</label>
                            <input type="number" id="points" name="points" value="{{ $task->points }}" placeholder="0"
                                class="w-full bg-white/5 border border-white/5 rounded-2xl p-4 text-white font-black text-sm focus:bg-white/10 transition-all placeholder:text-slate-400"
                                required>
                        </div>

                        <div>
                            <label for="admin_profit"
                                class="block mb-3 text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 leading-none">Platform
                                Reserve (BDT)</label>
                            <input type="number" step="0.01" id="admin_profit" name="admin_profit"
                                value="{{ $task->admin_profit }}" placeholder="0.00"
                                class="w-full bg-white/5 border border-white/5 rounded-2xl p-4 text-white font-black text-sm focus:bg-white/10 transition-all placeholder:text-slate-400"
                                required>
                        </div>

                        <div>
                            <label for="quota_max"
                                class="block mb-3 text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 leading-none">মোট
                                স্লট (Quota Max)</label>
                            <input type="number" id="quota_max" name="quota_max" value="{{ $task->quota_max }}"
                                class="w-full bg-white/5 border border-white/5 rounded-2xl p-4 text-white font-black text-sm focus:bg-white/10 transition-all placeholder:text-slate-400"
                                required>
                            {{-- Quota Status Info --}}
                            @php
                                $usedSlots = $task->quota_max - $task->quota_remaining;
                                $slotPct = $task->quota_max > 0 ? round(($usedSlots / $task->quota_max) * 100) : 0;
                            @endphp
                            <div class="mt-3 p-3 bg-white/5 rounded-xl border border-white/5">
                                <div class="flex justify-between items-center mb-1.5">
                                    <span class="text-[9px] font-black text-slate-500 uppercase tracking-widest">স্লট
                                        অগ্রগতি</span>
                                    <span
                                        class="text-[9px] font-black {{ $task->quota_remaining <= 0 ? 'text-rose-400' : 'text-emerald-400' }} uppercase tracking-widest">
                                        {{ $usedSlots }} ব্যবহৃত · {{ $task->quota_remaining }} বাকি ({{ $slotPct }}%)
                                    </span>
                                </div>
                                <div class="w-full h-1.5 bg-white/10 rounded-full overflow-hidden">
                                    <div class="h-full {{ $task->quota_remaining <= 0 ? 'bg-rose-500' : ($slotPct >= 75 ? 'bg-amber-400' : 'bg-emerald-500') }} rounded-full"
                                        style="width: {{ min(100, $slotPct) }}%"></div>
                                </div>
                                <p class="text-[8px] text-slate-500 mt-2 font-bold uppercase tracking-widest">
                                    ⚠️ Quota Max বাড়ালে remaining স্বয়ংক্রিয়ভাবে বাড়বে। কমালে সীমাবদ্ধতা প্রযোজ্য
                                    হবে।
                                </p>
                            </div>
                        </div>

                        <div>
                            <label for="cooldown_hours"
                                class="block mb-3 text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 leading-none">Cooldown
                                Hours (Auto-Rotation)</label>
                            <input type="number" id="cooldown_hours" name="cooldown_hours"
                                value="{{ $task->cooldown_hours }}"
                                class="w-full bg-white/5 border border-white/5 rounded-2xl p-4 text-white font-black text-sm focus:bg-white/10 transition-all placeholder:text-slate-400">
                            <p class="text-[9px] text-slate-500 mt-2 font-bold uppercase tracking-widest">0 = One-time.
                                24 = User can repeat daily.</p>
                        </div>

                        <!-- Optional Task Toggle -->
                        <div
                            class="p-6 bg-white/5 border border-white/5 rounded-[32px] flex items-center justify-between group">
                            <div>
                                <h4
                                    class="text-[10px] font-black text-white uppercase tracking-tight group-hover:text-emerald-500 transition-colors">
                                    Optional Task</h4>
                                <p class="text-[8px] font-bold text-slate-400 uppercase tracking-widest mt-1">Skip
                                    allowed — won't block offerwall</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input id="is_optional" name="is_optional" type="checkbox" value="1" {{ $task->is_optional ? 'checked' : '' }} class="sr-only peer">
                                <div
                                    class="w-10 h-5 bg-white/10 rounded-full peer peer-checked:bg-emerald-500 after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-slate-400 after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:after:translate-x-5">
                                </div>
                            </label>
                        </div>
                    </div>
                </div>

                <button type="submit" class="w-full premium-btn py-6 rounded-[32px] text-sm">
                    Commit Protocol Updates
                </button>
            </div>
        </div>
    </form>
</x-admin-layout>
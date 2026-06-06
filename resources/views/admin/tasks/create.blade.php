<x-admin-layout>
    <!-- Header Section -->
    <div class="mb-10 flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div>
            <h1 class="text-3xl font-black text-white tracking-tighter uppercase italic">Mission <span
                    class="text-primary-500">Deployment</span></h1>
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] mt-2 flex items-center gap-2">
                <span class="w-1.5 h-1.5 bg-primary-500 rounded-full animate-pulse"></span>
                Initialize new operational protocol
            </p>
        </div>
        <a href="{{ route('admin.tasks.index') }}"
            class="px-6 py-2.5 bg-white/5 border border-white/10 rounded-xl text-[10px] font-black text-slate-400 hover:text-white transition-all uppercase tracking-widest">
            Abort Deployment
        </a>
    </div>

    <form action="{{ route('admin.tasks.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
        @csrf

        @if($errors->any())
            <div class="p-6 bg-rose-500/10 border border-rose-500/20 rounded-[32px]">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-8 h-8 bg-rose-500/20 text-rose-500 rounded-xl flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    <h5 class="text-xs font-black text-white uppercase tracking-widest">Validation Error</h5>
                </div>
                <ul class="space-y-1">
                    @foreach($errors->all() as $error)
                        <li class="text-[11px] font-bold text-rose-400">• {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

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
                        <h3 class="text-[11px] font-black text-white uppercase tracking-[0.2em]">Mission Identity
                            Profile</h3>
                    </div>

                    <div class="space-y-6">
                        <div>
                            <label for="title"
                                class="block mb-3 text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 leading-none">Mission
                                Header (Title)</label>
                            <input type="text" id="title" name="title"
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
                                required></textarea>
                        </div>

                        {{-- Multiple Instruction Images --}}
                        <div>
                            <div class="flex items-center justify-between mb-3">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">
                                    📸 Instruction Images (Optional · Multiple)
                                </label>
                                <span class="text-[9px] text-slate-600 uppercase tracking-widest">Max 10MB each</span>
                            </div>

                            {{-- Preview Grid --}}
                            <div id="instr-preview-grid" class="grid grid-cols-3 gap-3 mb-3"></div>

                            {{-- Upload Zone: label triggers SEPARATE trigger input --}}
                            <label for="instr_trigger"
                                class="flex flex-col items-center justify-center gap-3 border-2 border-dashed border-white/10 rounded-[28px] p-6 hover:border-primary-500/50 transition-all cursor-pointer group">
                                <div class="w-12 h-12 bg-white/5 rounded-2xl flex items-center justify-center text-slate-500 group-hover:text-primary-500 transition-colors">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 4v16m8-8H4" />
                                    </svg>
                                </div>
                                <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest">ক্লিক করে ছবি যোগ করুন</p>
                            </label>

                            {{-- Trigger input: NOT submitted, just opens file picker --}}
                            <input type="file" id="instr_trigger" accept="image/*" multiple class="hidden"
                                onchange="addInstrImages(this)">

                            {{-- Actual form field: managed via DataTransfer, NEVER reset --}}
                            <input type="file" id="instruction_images_input" name="instruction_images[]"
                                multiple class="hidden">
                        </div>

                        <div id="external-link-wrap">
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
                                <input type="url" id="external_link" name="external_link" placeholder="https://..."
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
                            <button type="button" id="toggle-image-proof"
                                onclick="toggleProof('image')"
                                class="relative w-10 h-5 rounded-full transition-all duration-200 bg-white/10"
                                aria-pressed="false">
                                <span id="thumb-image" class="absolute top-[2px] left-[2px] w-4 h-4 bg-slate-400 rounded-full transition-all duration-200"></span>
                            </button>
                            <input type="hidden" id="requires_image_proof" name="requires_image_proof" value="0">
                        </div>
                        {{-- Image Proof Count --}}
                        <div id="image-proof-count-wrap" class="hidden px-4 pb-2">
                            <label class="text-[9px] font-black text-slate-500 uppercase tracking-widest mb-2 block">কতটি ভিজুয়াল প্রমাণ দিতে হবে?</label>
                            <div class="flex gap-2">
                                @foreach([1,2,3,4,5] as $n)
                                    <button type="button" onclick="setCount('image_proof_count', {{ $n }}, this)"
                                        class="count-btn-image w-8 h-8 rounded-xl text-xs font-black border border-white/10 bg-white/5 text-slate-400 hover:bg-primary-600 hover:text-white hover:border-primary-500 transition-all {{ $n === 1 ? 'bg-primary-600 text-white border-primary-500' : '' }}">
                                        {{ $n }}
                                    </button>
                                @endforeach
                            </div>
                            <input type="hidden" name="image_proof_count" id="image_proof_count" value="1">
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
                            <button type="button" id="toggle-text-proof"
                                onclick="toggleProof('text')"
                                class="relative w-10 h-5 rounded-full transition-all duration-200 bg-white/10"
                                aria-pressed="false">
                                <span id="thumb-text" class="absolute top-[2px] left-[2px] w-4 h-4 bg-slate-400 rounded-full transition-all duration-200"></span>
                            </button>
                            <input type="hidden" id="requires_text_proof" name="requires_text_proof" value="0">
                        </div>

                        <div
                            class="p-6 bg-white/5 border border-white/5 rounded-[32px] flex items-center justify-between group">
                            <div>
                                <h4
                                    class="text-[10px] font-black text-white uppercase tracking-tight group-hover:text-emerald-400 transition-colors">
                                    📧 Email / Gmail Proof</h4>
                                <p class="text-[8px] font-bold text-slate-400 uppercase tracking-widest mt-1">Require
                                    Gmail Submission</p>
                            </div>
                            <button type="button" id="toggle-email-proof"
                                onclick="toggleProof('email')"
                                class="relative w-10 h-5 rounded-full transition-all duration-200 bg-white/10"
                                aria-pressed="false">
                                <span id="thumb-email" class="absolute top-[2px] left-[2px] w-4 h-4 bg-slate-400 rounded-full transition-all duration-200"></span>
                            </button>
                            <input type="hidden" id="requires_email_proof" name="requires_email_proof" value="0">
                        </div>
                    </div>

                    {{-- Email + Visual Proof Sub-section (shows when email is ON) --}}
                    <div id="email-visual-wrap" class="hidden mt-4 p-5 bg-emerald-500/5 border border-emerald-500/10 rounded-[24px]">
                        <div class="flex items-center justify-between">
                            <div>
                                <h5 class="text-[10px] font-black text-emerald-400 uppercase tracking-tight">📸 ভিজুয়াল প্রমাণও দরকার?</h5>
                                <p class="text-[8px] font-bold text-slate-400 uppercase tracking-widest mt-0.5">Email + Password এর সাথে Screenshot ও নেবেন?</p>
                            </div>
                            <button type="button" id="toggle-email-visual"
                                onclick="toggleEmailVisual()"
                                class="relative w-10 h-5 rounded-full transition-all duration-200 bg-white/10"
                                aria-pressed="false">
                                <span id="thumb-email-visual" class="absolute top-[2px] left-[2px] w-4 h-4 bg-slate-400 rounded-full transition-all duration-200"></span>
                            </button>
                        </div>
                        {{-- Visual count for email tasks --}}
                        <div id="email-visual-count-wrap" class="hidden mt-3">
                            <label class="text-[9px] font-black text-slate-500 uppercase tracking-widest mb-2 block">কতটি স্ক্রিনশট দিতে হবে?</label>
                            <div class="flex gap-2">
                                @foreach([1,2,3] as $n)
                                    <button type="button" onclick="setCount('image_proof_count', {{ $n }}, this)"
                                        class="count-btn-image w-8 h-8 rounded-xl text-xs font-black border border-white/10 bg-white/5 text-slate-400 hover:bg-emerald-600 hover:text-white hover:border-emerald-500 transition-all {{ $n === 1 ? 'bg-emerald-600 text-white border-emerald-500' : '' }}">
                                        {{ $n }}
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Static Secret Code -->
                    <div class="mt-6 p-6 bg-white/5 border border-white/5 rounded-[32px]">
                        <div class="flex items-start gap-4">
                            <div class="w-10 h-10 bg-violet-500/10 text-violet-500 rounded-xl flex items-center justify-center border border-violet-500/20 shrink-0">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z">
                                    </path>
                                </svg>
                            </div>
                            <div class="flex-1 space-y-4">
                                <div>
                                    <h4 class="text-[10px] font-black text-white uppercase tracking-tight">Secret Code(s)</h4>
                                    <p class="text-[8px] font-bold text-slate-400 uppercase tracking-widest mt-1">
                                        Fixed code(s) users must submit. Leave empty for auto-generated.
                                    </p>
                                </div>

                                {{-- How many secret codes --}}
                                <div>
                                    <label class="text-[9px] font-black text-slate-500 uppercase tracking-widest mb-2 block">কতটি সিক্রেট কোড দিতে হবে?</label>
                                    <div class="flex gap-2 mb-3">
                                        @foreach([1,2,3,4,5] as $n)
                                            <button type="button" onclick="setSecretCount({{ $n }}, this)"
                                                class="count-btn-secret w-8 h-8 rounded-xl text-xs font-black border border-white/10 bg-white/5 text-slate-400 hover:bg-violet-600 hover:text-white hover:border-violet-500 transition-all {{ $n === 1 ? 'bg-violet-600 text-white border-violet-500' : '' }}">
                                                {{ $n }}
                                            </button>
                                        @endforeach
                                    </div>
                                    <input type="hidden" name="secret_code_count" id="secret_code_count" value="1">
                                </div>

                                {{-- Dynamic code inputs --}}
                                <div id="secret-code-inputs" class="space-y-2">
                                    <div class="flex items-center gap-2">
                                        <span class="text-[9px] font-black text-violet-400 uppercase w-16 shrink-0">Code #1</span>
                                        <input type="text" name="secret_codes[]" placeholder="e.g. YT2026XK9"
                                            maxlength="64"
                                            class="flex-1 bg-white/5 border border-white/5 rounded-2xl p-3 text-white font-black text-sm focus:bg-white/10 transition-all placeholder:text-slate-400 font-mono tracking-widest">
                                    </div>
                                </div>
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
                                <option value="custom" class="bg-dark text-white">Standard Network Task</option>
                                <option value="timewall" class="bg-dark text-white">TimeWall Integrated</option>
                                <option value="adsterra" class="bg-dark text-white">Adsterra Gateway</option>
                                <option value="youtube" class="bg-dark text-white">YouTube Engagement</option>
                                <option value="facebook" class="bg-dark text-white">Facebook Engagement</option>
                                <option value="telegram" class="bg-dark text-white">Telegram Engagement</option>
                            </select>
                        </div>

                        <div>
                            <label for="points"
                                class="block mb-3 text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 leading-none">Agent
                                Bounty (PTS)</label>
                            <input type="number" id="points" name="points" placeholder="0"
                                class="w-full bg-white/5 border border-white/5 rounded-2xl p-4 text-white font-black text-sm focus:bg-white/10 transition-all placeholder:text-slate-400"
                                required>
                        </div>

                        <div>
                            <label for="admin_profit"
                                class="block mb-3 text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 leading-none">Platform
                                Reserve (Points)</label>
                            <input type="number" step="1" id="admin_profit" name="admin_profit" placeholder="0"
                                class="w-full bg-white/5 border border-white/5 rounded-2xl p-4 text-white font-black text-sm focus:bg-white/10 transition-all placeholder:text-slate-400"
                                required min="0">
                            <p class="text-[9px] text-slate-500 mt-2 font-bold uppercase tracking-widest">0 = বিনা কাটে, ইউসার সব পয়েন্ট পাবে — দিতে চাইলে পরিমাণ লিখুন</p>
                        </div>

                        <div>
                            <label for="quota_max"
                                class="block mb-3 text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 leading-none">Global
                                Capacity Quota</label>
                            <input type="number" id="quota_max" name="quota_max" placeholder="0"
                                class="w-full bg-white/5 border border-white/5 rounded-2xl p-4 text-white font-black text-sm focus:bg-white/10 transition-all placeholder:text-slate-400"
                                required min="0">
                            <p class="text-[9px] text-slate-500 mt-2 font-bold uppercase tracking-widest">🔞 0 = আনলিমিটেড (যতজন খুশি করতে পারবে) — নির্দিষ্ট সংখ্যা দিতে চাইলে লিখুন</p>
                        </div>

                        <div>
                            <label for="cooldown_hours"
                                class="block mb-3 text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 leading-none">Cooldown
                                Hours (Auto-Rotation)</label>
                            <input type="number" id="cooldown_hours" name="cooldown_hours" value="0"
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
                                <input id="is_optional" name="is_optional" type="checkbox" value="1"
                                    class="sr-only peer">
                                <div
                                    class="w-10 h-5 bg-white/10 rounded-full peer peer-checked:bg-emerald-500 after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-slate-400 after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:after:translate-x-5">
                                </div>
                            </label>
                        </div>
                    </div>
                </div>

                <button type="submit" class="w-full premium-btn py-6 rounded-[32px] text-sm">
                    Initialize Protocol
                </button>
            </div>
        </div>
    </form>

    <script>
        // ── Independent Toggle System ──────────────────────────────────────────
        // প্রতিটি toggle সম্পূর্ণ স্বাধীন। একটা অন করলে অন্যটা affect হবে না।
        const proofState = { image: false, text: false, email: false };

        function toggleProof(type) {
            proofState[type] = !proofState[type];
            const btn   = document.getElementById('toggle-' + type + '-proof');
            const thumb = document.getElementById('thumb-' + type);
            const input = document.getElementById('requires_' + type + '_proof');

            if (proofState[type]) {
                btn.classList.remove('bg-white/10');
                btn.classList.add('bg-primary-500');
                thumb.classList.remove('left-[2px]', 'bg-slate-400');
                thumb.classList.add('left-[22px]', 'bg-white');
                input.value = '1';
                btn.setAttribute('aria-pressed', 'true');
            } else {
                btn.classList.add('bg-white/10');
                btn.classList.remove('bg-primary-500');
                thumb.classList.add('left-[2px]', 'bg-slate-400');
                thumb.classList.remove('left-[22px]', 'bg-white');
                input.value = '0';
                btn.setAttribute('aria-pressed', 'false');
            }

            // Email toggle হলে External Link লুকিয়ে/দেখাও
            const extWrap = document.getElementById('external-link-wrap');
            const extInput = document.getElementById('external_link');
            if (extWrap) {
                if (proofState.email) {
                    extWrap.style.display = 'none';
                    if (extInput) { extInput.removeAttribute('required'); extInput.value = ''; }
                } else {
                    extWrap.style.display = '';
                }
            }

            // ── Smart Email Logic ─────────────────────────────────────────
            if (type === 'email') {
                const textBtn   = document.getElementById('toggle-text-proof');
                const textInput = document.getElementById('requires_text_proof');
                const emailVisualWrap = document.getElementById('email-visual-wrap');

                if (proofState.email) {
                    // Email ON → Secret Code OFF + disabled (grey out)
                    if (proofState.text) {
                        // Force-disable text proof
                        proofState.text = false;
                        textInput.value = '0';
                        textBtn.classList.add('bg-white/10');
                        textBtn.classList.remove('bg-primary-500');
                        document.getElementById('thumb-text').classList.add('left-[2px]', 'bg-slate-400');
                        document.getElementById('thumb-text').classList.remove('left-[22px]', 'bg-white');
                    }
                    textBtn.disabled = true;
                    textBtn.classList.add('opacity-30', 'cursor-not-allowed');
                    textBtn.title = 'Email Proof এ Secret Code দরকার নেই';
                    // Show visual sub-option
                    emailVisualWrap.classList.remove('hidden');
                } else {
                    // Email OFF → Re-enable Secret Code
                    textBtn.disabled = false;
                    textBtn.classList.remove('opacity-30', 'cursor-not-allowed');
                    textBtn.title = '';
                    // Hide + reset visual sub-option
                    emailVisualWrap.classList.add('hidden');
                    // Also turn off image proof if it was set via email sub-toggle
                    if (document.getElementById('toggle-email-visual').getAttribute('aria-pressed') === 'true') {
                        toggleEmailVisual(); // reset it
                    }
                }
            }

            // Image proof toggle হলে count selector দেখাও/লুকাও
            if (type === 'image') {
                const imgCountWrap = document.getElementById('image-proof-count-wrap');
                if (imgCountWrap) {
                    imgCountWrap.classList.toggle('hidden', !proofState.image);
                }
            }
        }

        // ── Email + Visual Sub-toggle ─────────────────────────────────────
        let emailVisualOn = false;
        function toggleEmailVisual() {
            emailVisualOn = !emailVisualOn;
            const btn   = document.getElementById('toggle-email-visual');
            const thumb = document.getElementById('thumb-email-visual');
            const countWrap = document.getElementById('email-visual-count-wrap');
            const imgInput  = document.getElementById('requires_image_proof');
            const imgCountInput = document.getElementById('image_proof_count');

            if (emailVisualOn) {
                btn.classList.remove('bg-white/10');
                btn.classList.add('bg-emerald-500');
                thumb.classList.remove('left-[2px]', 'bg-slate-400');
                thumb.classList.add('left-[22px]', 'bg-white');
                btn.setAttribute('aria-pressed', 'true');
                imgInput.value = '1';   // requires_image_proof = 1
                countWrap.classList.remove('hidden');
            } else {
                btn.classList.add('bg-white/10');
                btn.classList.remove('bg-emerald-500');
                thumb.classList.add('left-[2px]', 'bg-slate-400');
                thumb.classList.remove('left-[22px]', 'bg-white');
                btn.setAttribute('aria-pressed', 'false');
                imgInput.value = '0';   // requires_image_proof = 0
                imgCountInput.value = '1';
                countWrap.classList.add('hidden');
            }
        }


        // ── Multiple Instruction Images ─────────────────────────────────
        const selectedFiles = [];

        function addInstrImages(trigger) {
            const grid = document.getElementById('instr-preview-grid');
            Array.from(trigger.files).forEach(file => {
                const idx = selectedFiles.length;
                selectedFiles.push(file);
                const reader = new FileReader();
                reader.onload = function(e) {
                    const card = document.createElement('div');
                    card.className = 'relative rounded-2xl overflow-hidden border border-white/10 group';
                    card.id = 'instr-card-' + idx;
                    card.innerHTML = `
                        <img src="${e.target.result}" class="w-full h-28 object-cover">
                        <button type="button" onclick="removeInstrPreview(${idx})"
                            class="absolute top-1 right-1 bg-rose-600 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs font-black opacity-0 group-hover:opacity-100 transition-opacity">
                            ×
                        </button>
                        <div class="absolute bottom-0 left-0 right-0 bg-black/60 text-[8px] text-white px-2 py-1 truncate">${file.name}</div>
                    `;
                    grid.appendChild(card);
                };
                reader.readAsDataURL(file);
            });
            syncFileInput();
            trigger.value = ''; // শুধু trigger reset — actual form field (instruction_images_input) স্পর্শ করবে না
        }

        function removeInstrPreview(idx) {
            selectedFiles[idx] = null;
            const card = document.getElementById('instr-card-' + idx);
            if (card) card.remove();
            syncFileInput();
        }

        function syncFileInput() {
            const dt = new DataTransfer();
            selectedFiles.forEach(f => { if (f) dt.items.add(f); });
            // Assign to the ACTUAL form field (separate from trigger)
            document.getElementById('instruction_images_input').files = dt.files;
        }

        // ── Proof Count Selectors ─────────────────────────────────────────
        function setCount(fieldId, value, btn) {
            document.getElementById(fieldId).value = value;
            // Update button styles
            const prefix = fieldId === 'image_proof_count' ? 'count-btn-image' : 'count-btn-secret';
            document.querySelectorAll('.' + prefix).forEach(b => {
                b.classList.remove('bg-primary-600', 'text-white', 'border-primary-500');
                b.classList.add('bg-white/5', 'text-slate-400', 'border-white/10');
            });
            btn.classList.add('bg-primary-600', 'text-white', 'border-primary-500');
            btn.classList.remove('bg-white/5', 'text-slate-400', 'border-white/10');
        }

        function setSecretCount(count, btn) {
            document.getElementById('secret_code_count').value = count;
            // Update button styles
            document.querySelectorAll('.count-btn-secret').forEach(b => {
                b.classList.remove('bg-violet-600', 'text-white', 'border-violet-500');
                b.classList.add('bg-white/5', 'text-slate-400', 'border-white/10');
            });
            btn.classList.add('bg-violet-600', 'text-white', 'border-violet-500');
            btn.classList.remove('bg-white/5', 'text-slate-400', 'border-white/10');

            // Rebuild dynamic code inputs
            const container = document.getElementById('secret-code-inputs');
            container.innerHTML = '';
            for (let i = 1; i <= count; i++) {
                const row = document.createElement('div');
                row.className = 'flex items-center gap-2';
                row.innerHTML = `
                    <span class="text-[9px] font-black text-violet-400 uppercase w-16 shrink-0">Code #${i}</span>
                    <input type="text" name="secret_codes[]" placeholder="e.g. CODE${i}"
                        maxlength="64"
                        class="flex-1 bg-white/5 border border-white/5 rounded-2xl p-3 text-white font-black text-sm focus:bg-white/10 transition-all placeholder:text-slate-400 font-mono tracking-widest">
                `;
                container.appendChild(row);
            }
        }


    </script>
</x-admin-layout>
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
                                <input type="text" id="secret_code" name="secret_code" placeholder="e.g. YT2026XK9"
                                    maxlength="64"
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
                            <input type="number" step="0.01" id="admin_profit" name="admin_profit" placeholder="0"
                                class="w-full bg-white/5 border border-white/5 rounded-2xl p-4 text-white font-black text-sm focus:bg-white/10 transition-all placeholder:text-slate-400"
                                required>
                            <p class="text-[9px] text-slate-500 mt-2 font-bold uppercase tracking-widest">Admin profit তোলার জন্য পয়েন্ট সংখ্যা দিন</p>
                        </div>

                        <div>
                            <label for="quota_max"
                                class="block mb-3 text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 leading-none">Global
                                Capacity Quota</label>
                            <input type="number" id="quota_max" name="quota_max" placeholder="Unlimited"
                                class="w-full bg-white/5 border border-white/5 rounded-2xl p-4 text-white font-black text-sm focus:bg-white/10 transition-all placeholder:text-slate-400"
                                required>
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
    </script>
</x-admin-layout>
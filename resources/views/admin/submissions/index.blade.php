<x-admin-layout>
    <!-- Header Section -->
    <div class="mb-10 flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div>
            <h1 class="text-3xl font-black text-white tracking-tighter uppercase italic">Operational <span
                    class="text-primary-500">Review</span></h1>
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] mt-2 flex items-center gap-2">
                <span class="w-1.5 h-1.5 bg-primary-500 rounded-full animate-pulse"></span>
                Mission evidence validation and authorization
            </p>
        </div>
        <div class="hidden md:flex gap-4">
            <div class="glass-card px-5 py-2.5">
                <span class="text-[10px] font-black text-primary-500 uppercase tracking-widest">Pending Review:
                    {{ $submissions->total() }}</span>
            </div>
        </div>
    </div>

    <!-- Review Matrix -->
    <div class="glass-card overflow-hidden min-h-[400px]">
        <!-- Desktop Table (visible on md+) -->
        <div class="hidden md:block overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-white/5 border-b border-white/5">
                        <th class="px-8 py-5 text-[10px] font-black uppercase text-slate-400 tracking-widest">Mission &
                            Agent</th>
                        <th
                            class="px-8 py-5 text-[10px] font-black uppercase text-slate-400 tracking-widest text-center">
                            Bounty</th>
                        <th
                            class="px-8 py-5 text-[10px] font-black uppercase text-slate-400 tracking-widest text-center">
                            Evidence</th>
                        <th
                            class="px-8 py-5 text-[10px] font-black uppercase text-slate-400 tracking-widest text-center">
                            Log Time</th>
                        <th
                            class="px-8 py-5 text-[10px] font-black uppercase text-slate-400 tracking-widest text-right">
                            Decision</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @forelse($submissions as $submission)
                        <tr class="hover:bg-white/[0.02] transition-colors group">
                            <td class="px-8 py-6">
                                <div class="flex items-center gap-4">
                                    <div
                                        class="w-12 h-12 bg-white/5 border border-white/10 rounded-2xl flex items-center justify-center text-primary-500 transition-all font-black text-xs uppercase">
                                        {{ strtoupper(substr(optional($submission->user)->name ?? 'D', 0, 2)) }}
                                    </div>
                                    <div class="max-w-[200px]">
                                        <div
                                            class="font-black text-white uppercase tracking-tight group-hover:text-primary-500 transition-colors truncate">
                                            {{ optional($submission->task)->title ?? 'N/A' }}</div>
                                        <div
                                            class="text-[10px] font-bold text-slate-400 uppercase tracking-tighter mt-1 truncate">
                                            By: {{ optional($submission->user)->name ?? 'N/A' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-6 text-center">
                                <span
                                    class="font-black text-primary-500 tracking-widest text-sm italic">{{ number_format(optional($submission->task)->points ?? 0) }}
                                    PTS</span>
                            </td>
                            <td class="px-6 py-5 text-center">
                                <div class="flex flex-col items-center gap-1.5 max-w-[220px] mx-auto">

                                    {{-- সিক্রেট কোড (multiple JSON array) --}}
                                    @php
                                        $codes = $submission->proof_texts ?? ($submission->proof_text ? [$submission->proof_text] : []);
                                        $images = $submission->proof_images ?? ($submission->proof_image ? [$submission->proof_image] : []);
                                    @endphp

                                    @if(count($codes) > 0)
                                        <div class="w-full">
                                            @foreach($codes as $ci => $code)
                                                <button onclick="showCodeModal('{{ e($code) }}', {{ $ci + 1 }}, {{ count($codes) }})"
                                                    class="w-full mb-1 px-3 py-1.5 bg-violet-500/10 border border-violet-500/20 rounded-lg text-[9px] font-black text-violet-400 hover:bg-violet-500 hover:text-white transition-all uppercase tracking-widest text-left truncate">
                                                    🔑 {{ count($codes) > 1 ? 'Code #'.($ci+1).': ' : '' }}{{ Str::limit($code, 18) }}
                                                </button>
                                            @endforeach
                                        </div>
                                    @endif

                                    @if(count($images) > 0)
                                        <div class="flex gap-1 flex-wrap justify-center">
                                            @foreach($images as $ii => $img)
                                                <button data-url="{{ Storage::url($img) }}"
                                                    onclick="showProofModal(this.dataset.url)"
                                                    class="px-3 py-1.5 bg-primary-500/10 border border-primary-500/20 rounded-lg text-[9px] font-black text-primary-400 hover:bg-primary-500 hover:text-white transition-all uppercase tracking-widest">
                                                    📸 {{ count($images) > 1 ? 'IMG #'.($ii+1) : 'View IMG' }}
                                                </button>
                                            @endforeach
                                        </div>
                                    @endif

                                    @if($submission->proof_email)
                                        <div class="w-full flex gap-1">
                                            <span class="flex-1 px-2 py-1.5 bg-emerald-500/10 border border-emerald-500/20 rounded-lg text-[9px] font-black text-emerald-400 uppercase tracking-widest truncate">
                                                📧 {{ $submission->proof_email }}
                                            </span>
                                            @if($submission->proof_password)
                                                <button onclick="showPassModal('{{ e($submission->proof_email) }}', '{{ e($submission->proof_password) }}')"
                                                    class="px-2 py-1.5 bg-amber-500/10 border border-amber-500/20 rounded-lg text-[9px] font-black text-amber-400 hover:bg-amber-500 hover:text-white transition-all">
                                                    🔐
                                                </button>
                                            @endif
                                        </div>
                                    @endif

                                    @if(!count($codes) && !count($images) && !$submission->proof_email)
                                        <span class="text-[9px] text-slate-600 uppercase tracking-widest italic">No Evidence</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-8 py-6 text-center">
                                <div
                                    class="text-[10px] font-black text-slate-400 uppercase tracking-widest leading-relaxed">
                                    {{ $submission->created_at->format('M d, Y') }}<br>
                                    <span
                                        class="text-[9px] opacity-50 font-bold uppercase">{{ $submission->created_at->format('H:i') }}
                                        Zulu</span>
                                </div>
                            </td>
                            <td class="px-8 py-6">
                                <div class="flex items-center justify-end gap-3">
                                    <form action="{{ route('admin.submissions.approve', $submission) }}" method="POST">
                                        @csrf
                                        <button type="submit"
                                            class="px-6 py-2.5 bg-primary-600 text-white text-[10px] font-black uppercase tracking-widest rounded-xl hover:bg-primary-500 transition-all shadow-lg shadow-primary-900/20"
                                            onclick="return confirm('Authorize this operational success?')">
                                            Approve
                                        </button>
                                    </form>
                                    <button type="button"
                                        class="px-6 py-2.5 bg-rose-600 text-white text-[10px] font-black uppercase tracking-widest rounded-xl hover:bg-rose-500 transition-all shadow-lg shadow-rose-900/40"
                                        onclick="showRejectModal('{{ $submission->id }}')">
                                        Void
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-8 py-32 text-center">
                                <div
                                    class="w-20 h-20 bg-white/5 border border-white/5 rounded-3xl flex items-center justify-center text-slate-400 mx-auto mb-6">
                                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em]">No missions
                                    pending manual review</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Mobile/Tablet Card View (visible below md) -->
        <div class="md:hidden divide-y divide-white/5">
            @forelse($submissions as $submission)
                <div class="p-6 transition-colors hover:bg-white/[0.02] flex flex-col gap-6">
                    <!-- User/Task Info -->
                    <div class="flex items-start justify-between">
                        <div class="flex items-center gap-4">
                            <div
                                class="w-12 h-12 bg-white/5 border border-white/10 rounded-2xl flex items-center justify-center text-primary-500 font-black text-xs uppercase">
                                {{ strtoupper(substr(optional($submission->user)->name ?? 'D', 0, 2)) }}
                            </div>
                            <div class="max-w-[180px]">
                                <div class="font-black text-white uppercase tracking-tight truncate">
                                    {{ optional($submission->task)->title ?? 'N/A' }}</div>
                                <div class="text-[10px] font-bold text-slate-500 uppercase tracking-tighter mt-1">
                                    {{ optional($submission->user)->name ?? 'N/A' }}</div>
                            </div>
                        </div>
                        <div class="text-right">
                            <span
                                class="font-black text-primary-500 tracking-widest text-sm italic">{{ number_format(optional($submission->task)->points ?? 0) }}
                                PTS</span>
                        </div>
                    </div>

                    {{-- Proof Box --}}
                    <div class="bg-white/5 border border-white/5 rounded-2xl p-4 space-y-2">
                        <div class="text-[9px] font-black text-slate-500 uppercase tracking-[0.2em] mb-3">Operational Evidence</div>
                        @php
                            $codes  = $submission->proof_texts  ?? ($submission->proof_text  ? [$submission->proof_text]  : []);
                            $images = $submission->proof_images ?? ($submission->proof_image ? [$submission->proof_image] : []);
                        @endphp

                        @foreach($codes as $ci => $code)
                            <button onclick="showCodeModal('{{ e($code) }}', {{ $ci + 1 }}, {{ count($codes) }})"
                                class="w-full px-4 py-3 bg-violet-500/10 border border-violet-500/20 text-violet-400 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-violet-500 hover:text-white transition-all text-left truncate">
                                🔑 {{ count($codes) > 1 ? 'Code #'.($ci+1).': ' : 'Secret Code: ' }}{{ Str::limit($code, 22) }}
                            </button>
                        @endforeach

                        @foreach($images as $ii => $img)
                            <button data-url="{{ Storage::url($img) }}"
                                onclick="showProofModal(this.dataset.url)"
                                class="w-full px-4 py-3 bg-primary-500/10 border border-primary-500/20 text-primary-400 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-primary-500 hover:text-white transition-all">
                                📸 {{ count($images) > 1 ? 'Screenshot #'.($ii+1) : 'Screenshot' }} দেখুন
                            </button>
                        @endforeach

                        @if($submission->proof_email)
                            <div class="flex gap-2 items-center">
                                <div class="flex-1 px-4 py-3 bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 rounded-xl text-[10px] font-black uppercase tracking-widest truncate">
                                    📧 {{ $submission->proof_email }}
                                </div>
                                @if($submission->proof_password)
                                    <button onclick="showPassModal('{{ e($submission->proof_email) }}', '{{ e($submission->proof_password) }}')"
                                        class="px-4 py-3 bg-amber-500/10 border border-amber-500/20 text-amber-400 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-amber-500 hover:text-white transition-all">
                                        🔐 Pass
                                    </button>
                                @endif
                            </div>
                        @endif

                        @if(!count($codes) && !count($images) && !$submission->proof_email)
                            <p class="text-center text-[9px] text-slate-600 uppercase tracking-widest italic py-2">No evidence submitted</p>
                        @endif
                    </div>

                    <!-- Action Hub -->
                    <div class="flex gap-4">
                        <form action="{{ route('admin.submissions.approve', $submission) }}" method="POST" class="flex-1">
                            @csrf
                            <button type="submit"
                                class="w-full py-4 bg-primary-600 text-white text-[10px] font-black uppercase tracking-widest rounded-xl hover:bg-primary-500 transition-all shadow-lg shadow-primary-900/40"
                                onclick="return confirm('Confirm Authorization?')">
                                Approve
                            </button>
                        </form>
                        <button type="button"
                            class="flex-1 py-4 bg-rose-600 text-white text-[10px] font-black uppercase tracking-widest rounded-xl hover:bg-rose-500 transition-all shadow-lg shadow-rose-900/40"
                            onclick="showRejectModal('{{ $submission->id }}')">
                            Void
                        </button>
                    </div>

                    <!-- Timestamp -->
                    <div class="text-center text-[9px] font-black text-slate-600 uppercase tracking-widest italic">
                        Logged: {{ $submission->created_at->format('M d, Y - H:i') }} Zulu
                    </div>
                </div>
            @empty
                <div class="px-8 py-32 text-center">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em]">No missions pending manual
                        review</p>
                </div>
            @endforelse
        </div>

        @if($submissions->hasPages())
            <div class="p-8 border-t border-white/5 bg-white/[0.01]">
                {{ $submissions->links() }}
            </div>
        @endif
    </div>

    <!-- Reject Modal -->
    <div id="rejectModal" x-data="{ show: false, id: null }" @open-reject.window="show = true; id = $event.detail"
        x-show="show" x-cloak
        class="fixed inset-0 z-[100] bg-black/80 backdrop-blur-md flex items-center justify-center p-4">
        <div
            class="glass-card w-full max-w-md p-10 transform translate-y-0 scale-100 animate-in fade-in zoom-in duration-200 text-center">
            <div
                class="w-16 h-16 bg-rose-500/10 text-rose-500 rounded-3xl flex items-center justify-center mb-6 mx-auto">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                    </path>
                </svg>
            </div>
            <h3 class="text-2xl font-black text-white mb-2 uppercase tracking-tight">Void Mission</h3>
            <p class="text-[10px] font-black text-slate-400 mb-8 uppercase tracking-widest leading-relaxed">Agent will
                be auto-notified of the operational failure reason.</p>

            <form :action="'{{ route('admin.submissions.reject', ['submission' => '__ID__']) }}'.replace('__ID__', id)" method="POST">
                @csrf
                <textarea name="rejection_reason" rows="4" required
                    placeholder="Specify why the evidence provided is invalid..."
                    class="w-full bg-white/5 border border-white/5 rounded-2xl p-5 text-white font-bold text-sm focus:bg-white/10 focus:ring-rose-500 focus:border-rose-500 transition-all placeholder:text-slate-400 mb-8"></textarea>

                <div class="grid grid-cols-2 gap-4">
                    <button type="button" @click="show = false"
                        class="py-4 bg-white/5 text-slate-400 font-black rounded-2xl uppercase tracking-widest hover:bg-white/10 transition-all text-[11px]">Abort</button>
                    <button type="submit"
                        class="py-4 bg-rose-600 text-white font-black rounded-2xl uppercase tracking-widest hover:bg-rose-500 transition-all shadow-lg shadow-rose-900/40 text-[11px]">Execute
                        Void</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Proof Modal -->
    <div id="proofModal" x-data="{ show: false, src: '' }" @open-proof.window="show = true; src = $event.detail"
        x-show="show" x-cloak
        class="fixed inset-0 z-[101] bg-black/95 backdrop-blur-2xl flex flex-col items-center justify-center p-4 sm:p-10">
        <div class="relative max-w-5xl w-full max-h-full flex flex-col items-center">
            <button @click="show = false"
                class="absolute -top-12 sm:-top-16 right-0 sm:-right-8 text-white/40 hover:text-white transition-all p-2 bg-white/5 rounded-full backdrop-blur-md">
                <svg class="w-6 h-6 sm:w-8 sm:h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                    </path>
                </svg>
            </button>
            <div
                class="w-full h-full overflow-y-auto rounded-2xl sm:rounded-[32px] border border-white/10 shadow-2xl overflow-x-hidden">
                <img :src="src" class="w-full h-auto block">
            </div>
        </div>
    </div>

    {{-- ── Code Modal ─────────────────────────────────────────────────── --}}
    <div id="codeModal" class="hidden fixed inset-0 z-[102] bg-black/80 backdrop-blur-md flex items-center justify-center p-4">
        <div class="glass-card w-full max-w-sm p-8 text-center">
            <div class="w-14 h-14 bg-violet-500/10 rounded-2xl flex items-center justify-center text-3xl mx-auto mb-5">🔑</div>
            <h3 id="codeModalTitle" class="text-[11px] font-black text-slate-400 uppercase tracking-widest mb-4">সিক্রেট কোড</h3>
            <div class="bg-white/5 border border-white/10 rounded-2xl p-5 mb-5">
                <p id="codeModalText" class="font-mono font-black text-white text-lg tracking-widest break-all"></p>
            </div>
            <div class="flex gap-3">
                <button id="copyCodeBtn" class="flex-1 py-3 bg-violet-600 text-white text-[10px] font-black uppercase tracking-widest rounded-xl hover:bg-violet-500 transition-all">📋 Copy</button>
                <button id="closeCodeModal" class="flex-1 py-3 bg-white/5 text-slate-400 text-[10px] font-black uppercase tracking-widest rounded-xl hover:bg-white/10 transition-all">Close</button>
            </div>
        </div>
    </div>

    {{-- ── Pass Modal ─────────────────────────────────────────────────── --}}
    <div id="passModal" class="hidden fixed inset-0 z-[102] bg-black/80 backdrop-blur-md flex items-center justify-center p-4">
        <div class="glass-card w-full max-w-sm p-8 text-center">
            <div class="w-14 h-14 bg-amber-500/10 rounded-2xl flex items-center justify-center text-3xl mx-auto mb-5">🔐</div>
            <h3 class="text-[11px] font-black text-slate-400 uppercase tracking-widest mb-4">Gmail Credentials</h3>
            <div class="space-y-3 mb-6">
                <div class="bg-emerald-500/5 border border-emerald-500/20 rounded-2xl p-4 text-left">
                    <p class="text-[9px] font-black text-emerald-400 uppercase tracking-widest mb-1">📧 Email</p>
                    <p id="passModalEmail" class="font-mono font-bold text-white text-sm break-all"></p>
                </div>
                <div class="bg-amber-500/5 border border-amber-500/20 rounded-2xl p-4 text-left">
                    <p class="text-[9px] font-black text-amber-400 uppercase tracking-widest mb-1">🔑 Password</p>
                    <p id="passModalPass" class="font-mono font-bold text-white text-sm break-all"></p>
                </div>
            </div>
            <button id="closePassModal" class="w-full py-3 bg-white/5 text-slate-400 text-[10px] font-black uppercase tracking-widest rounded-xl hover:bg-white/10 transition-all">Close</button>
        </div>
    </div>

    @push('scripts')

        <script>
            function showRejectModal(id) {
                window.dispatchEvent(new CustomEvent('open-reject', { detail: id }));
            }

            function showProofModal(src) {
                window.dispatchEvent(new CustomEvent('open-proof', { detail: src }));
            }

            // ── Code Modal ────────────────────────────────────────────────
            function showCodeModal(code, index, total) {
                const title = total > 1 ? `সিক্রেট কোড #${index} / ${total}` : 'সিক্রেট কোড';
                const modal = document.getElementById('codeModal');
                document.getElementById('codeModalTitle').textContent = title;
                document.getElementById('codeModalText').textContent = code;
                modal.classList.remove('hidden');
            }
            document.getElementById('closeCodeModal').addEventListener('click', () => {
                document.getElementById('codeModal').classList.add('hidden');
            });
            document.getElementById('copyCodeBtn').addEventListener('click', () => {
                const text = document.getElementById('codeModalText').textContent;
                navigator.clipboard.writeText(text).then(() => {
                    const btn = document.getElementById('copyCodeBtn');
                    btn.textContent = '✅ Copied!';
                    setTimeout(() => btn.textContent = '📋 Copy', 1500);
                });
            });

            // ── Pass Modal ────────────────────────────────────────────────
            function showPassModal(email, pass) {
                const modal = document.getElementById('passModal');
                document.getElementById('passModalEmail').textContent = email;
                document.getElementById('passModalPass').textContent = pass;
                modal.classList.remove('hidden');
            }
            document.getElementById('closePassModal').addEventListener('click', () => {
                document.getElementById('passModal').classList.add('hidden');
            });

        </script>
    @endpush
</x-admin-layout>
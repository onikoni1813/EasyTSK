<x-admin-layout>
    <!-- Header Section -->
    <div class="mb-10 flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div>
            <h1 class="text-3xl font-black text-white tracking-tighter uppercase italic">Identity <span
                    class="text-amber-500">Audit</span></h1>
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] mt-2 flex items-center gap-2">
                <span class="w-1.5 h-1.5 bg-amber-500 rounded-full animate-pulse"></span>
                Personnel verification & NID validation protocols
            </p>
        </div>
        <div class="hidden md:flex gap-4">
            <div class="glass-card px-5 py-2.5">
                <span class="text-[10px] font-black text-amber-500 uppercase tracking-widest">Active Requests:
                    {{ $users->total() }}</span>
            </div>
        </div>
    </div>

    <!-- Identity Matrix -->
    <div class="glass-card overflow-hidden min-h-[400px]">
        <!-- Desktop Table -->
        <div class="hidden md:block overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-white/5 border-b border-white/5">
                        <th class="px-8 py-5 text-[10px] font-black uppercase text-slate-400 tracking-widest">Candidate
                            Identity</th>
                        <th
                            class="px-8 py-5 text-[10px] font-black uppercase text-slate-400 tracking-widest text-center">
                            NID Serial</th>
                        <th
                            class="px-8 py-5 text-[10px] font-black uppercase text-slate-400 tracking-widest text-center">
                            Inspection</th>
                        <th
                            class="px-8 py-5 text-[10px) font-black uppercase text-slate-400 tracking-widest text-center">
                            Arrival Log</th>
                        <th
                            class="px-8 py-5 text-[10px] font-black uppercase text-slate-400 tracking-widest text-right">
                            Decision</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @forelse($users as $user)
                        <tr class="hover:bg-white/[0.02] transition-colors group">
                            <td class="px-8 py-6">
                                <div class="flex items-center gap-4">
                                    <div
                                        class="w-12 h-12 bg-white/5 border border-white/10 rounded-2xl flex items-center justify-center font-black text-xs group-hover:scale-110 transition-transform uppercase text-amber-500">
                                        {{ substr($user->name, 0, 2) }}
                                    </div>
                                    <div>
                                        <div
                                            class="font-black text-white uppercase tracking-tight group-hover:text-amber-500 transition-colors">
                                            {{ $user->name }}</div>
                                        <div class="text-[10px] font-bold text-slate-400 uppercase tracking-tighter mt-1">
                                            {{ $user->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-6 text-center">
                                <span
                                    class="px-4 py-1.5 bg-white/5 border border-white/10 rounded-xl font-black text-[11px] text-white tracking-widest leading-none font-mono">
                                    {{ $user->id_number }}
                                </span>
                            </td>
                            <td class="px-8 py-6 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('admin.kyc.view', [$user, 'front']) }}" target="_blank"
                                        class="px-4 py-2 bg-white/5 border border-white/10 rounded-xl text-[9px] font-black text-slate-400 hover:text-white hover:bg-amber-600 hover:border-amber-500 transition-all uppercase tracking-widest">
                                        Front Side
                                    </a>
                                    <a href="{{ route('admin.kyc.view', [$user, 'back']) }}" target="_blank"
                                        class="px-4 py-2 bg-white/5 border border-white/10 rounded-xl text-[9px] font-black text-slate-400 hover:text-white hover:bg-amber-600 hover:border-amber-500 transition-all uppercase tracking-widest">
                                        Back Side
                                    </a>
                                </div>
                            </td>
                            <td class="px-8 py-6 text-center">
                                <div
                                    class="text-[10px] font-black text-slate-400 uppercase tracking-widest leading-relaxed">
                                    {{ $user->updated_at->format('M d, Y') }}<br>
                                    <span
                                        class="text-[9px] opacity-50 font-bold uppercase">{{ $user->updated_at->format('H:i') }}
                                        Zulu</span>
                                </div>
                            </td>
                            <td class="px-8 py-6">
                                <div class="flex items-center justify-end gap-3">
                                    <form action="{{ route('admin.kyc.approve', $user) }}" method="POST">
                                        @csrf
                                        <button type="submit"
                                            class="px-6 py-2.5 bg-primary-600 text-white text-[10px] font-black uppercase tracking-widest rounded-xl hover:bg-primary-500 transition-all shadow-lg shadow-primary-900/20"
                                            onclick="return confirm('Authorize this identity protocol?')">
                                            Approve
                                        </button>
                                    </form>
                                    <button type="button"
                                        class="px-6 py-2.5 bg-rose-600 text-white text-[10px] font-black uppercase tracking-widest rounded-xl hover:bg-rose-500 transition-all shadow-lg shadow-rose-900/40"
                                        onclick="showRejectModal('{{ $user->id }}')">
                                        Reject
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5"
                                class="px-8 py-32 text-center text-slate-500 italic text-xs uppercase tracking-widest">No
                                pending verification identities</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Mobile View (visible below md) -->
        <div class="md:hidden divide-y divide-white/5">
            @forelse($users as $user)
                <div class="p-6 space-y-4">
                    <div class="flex items-center justify-between gap-4">
                        <div class="flex items-center gap-3">
                            <div
                                class="w-10 h-10 bg-white/5 border border-white/10 rounded-xl flex items-center justify-center font-black text-xs uppercase text-amber-500">
                                {{ substr($user->name, 0, 2) }}
                            </div>
                            <div>
                                <div class="font-black text-white uppercase tracking-tight text-xs">{{ $user->name }}</div>
                                <div
                                    class="text-[8px] font-black text-slate-500 uppercase tracking-widest italic font-mono">
                                    {{ $user->id_number }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <a href="{{ route('admin.kyc.view', [$user, 'front']) }}" target="_blank"
                            class="text-center py-2.5 bg-white/5 border border-white/10 rounded-xl text-[9px] font-black text-slate-400 uppercase tracking-widest hover:text-white transition-all">Front
                            Doc</a>
                        <a href="{{ route('admin.kyc.view', [$user, 'back']) }}" target="_blank"
                            class="text-center py-2.5 bg-white/5 border border-white/10 rounded-xl text-[9px] font-black text-slate-400 uppercase tracking-widest hover:text-white transition-all">Back
                            Doc</a>
                    </div>
                    <div class="flex gap-3">
                        <form action="{{ route('admin.kyc.approve', $user) }}" method="POST" class="flex-1">
                            @csrf
                            <button type="submit"
                                class="w-full py-3.5 bg-primary-600 text-white text-[9px] font-black uppercase tracking-widest rounded-xl"
                                onclick="return confirm('Authorize?')">Approve</button>
                        </form>
                        <button type="button"
                            class="flex-1 py-3.5 bg-rose-600 text-white text-[9px] font-black uppercase tracking-widest rounded-xl"
                            onclick="showRejectModal('{{ $user->id }}')">Reject</button>
                    </div>
                </div>
            @empty
                <div class="p-10 text-center text-slate-500 text-[10px] font-black uppercase tracking-widest italic">No
                    pending verifications</div>
            @endforelse
        </div>
    </div>

    @if($users->hasPages())
        <div class="p-8 border-t border-white/5 bg-white/[0.01]">
            {{ $users->links() }}
        </div>
    @endif
    </div>

    <!-- Reject Modal -->
    <div id="rejectModal" x-data="{ show: false, id: null }" x-show="show" x-cloak
        class="fixed inset-0 z-[100] bg-black/80 backdrop-blur-md flex items-center justify-center p-4">
        <div
            class="glass-card w-full max-w-md p-10 transform scale-110 animate-in fade-in zoom-in duration-200 text-center">
            <div
                class="w-16 h-16 bg-rose-500/10 text-rose-500 rounded-3xl flex items-center justify-center mb-6 mx-auto">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                    </path>
                </svg>
            </div>
            <h3 class="text-2xl font-black text-white mb-2 uppercase tracking-tight">Void Identity</h3>
            <p class="text-[10px] font-black text-slate-400 mb-8 uppercase tracking-widest leading-relaxed">Agent will
                be auto-notified of the ID verification failure reason.</p>

            <form id="rejectForm" method="POST">
                @csrf
                <textarea name="kyc_notes" rows="4" required
                    placeholder="Specify why the identity document is invalid (e.g. Mismatch, Blur, Expired)..."
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

    @push('scripts')
        <script>
            function showRejectModal(id) {
                const modal = document.getElementById('rejectModal');
                const form = document.getElementById('rejectForm');
                form.action = `/admin/kyc/${id}/reject`;
                modal.__x.$data.show = true;
            }
        </script>
    @endpush
</x-admin-layout>
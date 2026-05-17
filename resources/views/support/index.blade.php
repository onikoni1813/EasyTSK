<x-user-layout>
    <div class="mb-10 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-3xl font-black text-white tracking-tight uppercase">হেল্প ও <span
                    class="text-primary-500">সাপোর্ট</span></h2>
            <p class="text-sm text-slate-400 font-medium mt-1">আমাদের হেল্পলাইন ব্যবহার করে আপনার সমস্যার দ্রুত সমাধান
                পান।</p>
        </div>
        <a href="{{ route('support.create') }}"
            class="flex items-center justify-center gap-2 px-8 py-4 bg-primary-600 text-white rounded-2xl text-sm font-black uppercase tracking-widest hover:bg-primary-500 transition-all shadow-xl shadow-primary-900/40">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6">
                </path>
            </svg>
            নতুন টিকেট খুলুন
        </a>
    </div>

    <div
        class="p-5 md:p-8 bg-dark-card border border-white/5 rounded-[32px] md:rounded-[40px] shadow-sm flex flex-col overflow-hidden min-h-[500px]">
        <h3 class="text-lg font-black text-white mb-8 flex items-center justify-between">
            আপনার সাপোর্ট টিকেটসমূহ
            <span
                class="text-[10px] font-black text-slate-500 uppercase tracking-widest bg-white/5 px-3 py-1.5 rounded-xl border border-white/5">সকল
                ডাটা</span>
        </h3>

        <div class="relative overflow-x-auto flex-1">
            <table class="w-full text-left">
                <thead>
                    <tr class="text-[10px] font-black text-slate-500 uppercase tracking-widest border-b border-white/5">
                        <th class="pb-4 pr-4">বিষয় (Subject)</th>
                        <th class="pb-4 pr-4 hidden sm:table-cell">প্রাইোরিটি</th>
                        <th class="pb-4 pr-4">অবস্থা</th>
                        <th class="pb-4 pr-4 hidden md:table-cell">সর্বশেষ আপডেট</th>
                        <th class="pb-4 text-center">অ্যাকশন</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @forelse($tickets as $ticket)
                                    <tr class="group">
                                        <td class="py-5 pr-4">
                                            <span class="text-xs font-black text-white block">{{ $ticket->subject }}</span>
                                            <span class="text-[9px] font-bold text-slate-500 block tracking-widest">ID:
                                                #{{ str_pad($ticket->id, 5, '0', STR_PAD_LEFT) }}</span>
                                        </td>
                                        <td class="py-5 pr-4 hidden sm:table-cell">
                                            <span
                                                class="px-2.5 py-1 rounded-lg text-[9px] font-black uppercase tracking-widest
                                                {{ $ticket->priority == 'high' ? 'bg-rose-500/10 text-rose-500' : ($ticket->priority == 'medium' ? 'bg-amber-500/10 text-amber-500' : 'bg-primary-500/10 text-primary-500') }}">
                                                {{ $ticket->priority }}
                                            </span>
                                        </td>
                                        <td class="py-5 pr-4">
                                            <span
                                                class="px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-widest
                                                {{ $ticket->status === 'open' ? 'bg-primary-500/10 text-primary-500 border border-primary-500/20' :
                        ($ticket->status === 'responded' ? 'bg-emerald-500/10 text-emerald-500 border border-emerald-500/20' :
                            ($ticket->status === 'pending_admin' ? 'bg-amber-500/10 text-amber-500 border border-amber-500/20' : 'bg-white/5 text-slate-500 border border-white/5')) }}">
                                                {{ $ticket->status }}
                                            </span>
                                        </td>
                                        <td class="py-5 pr-4 hidden md:table-cell text-xs font-bold text-slate-400">
                                            {{ $ticket->updated_at->diffForHumans() }}</td>
                                        <td class="py-5 text-center">
                                            <a href="{{ route('support.show', $ticket) }}"
                                                class="inline-flex items-center justify-center w-10 h-10 bg-white/5 border border-white/5 rounded-xl text-slate-500 hover:bg-primary-600 hover:text-white transition-all">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                                    </path>
                                                </svg>
                                            </a>
                                        </td>
                                    </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-24 text-center">
                                <div
                                    class="w-16 h-16 bg-white/5 border border-white/5 rounded-[24px] flex items-center justify-center text-slate-700 mx-auto mb-6">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z">
                                        </path>
                                    </svg>
                                </div>
                                <p class="text-sm font-black text-white">এখনো কোনো টিকেট নেই</p>
                                <p class="text-xs text-slate-500 mt-2 font-medium">যেকোনো সাহায্যে নতুন সাপোর্ট টিকেট খুলুন।
                                </p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($tickets->hasPages())
            <div class="mt-8 pt-6 border-t border-white/5">
                {{ $tickets->links() }}
            </div>
        @endif
    </div>
</x-user-layout>
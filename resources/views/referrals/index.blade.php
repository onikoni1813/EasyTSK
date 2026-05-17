<x-user-layout>
    <div class="mb-10 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-3xl font-black text-white tracking-tight uppercase">রেফারেল <span
                    class="text-primary-500">নেটওয়ার্ক</span></h2>
            <p class="text-sm text-slate-400 font-medium mt-1">বন্ধুদের আমন্ত্রণ জানান এবং তাদের সারাজীবন বোনাস পান।</p>
        </div>
        <div class="bg-dark-card border border-white/5 px-6 py-3 rounded-2xl shadow-xl text-white">
            <span class="text-[10px] font-black uppercase tracking-widest block opacity-70">মোট রেফারেল বোনাস</span>
            <span class="text-xl font-black tracking-tighter text-primary-500">৳
                {{ number_format($totalBonus / 100, 2) }} <span class="text-xs font-bold opacity-70">BDT</span></span>
        </div>
    </div>

    <!-- Referral Stats -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
        <div class="p-6 bg-dark-card border border-white/5 rounded-[32px] shadow-sm">
            <span class="text-[10px] font-black text-slate-500 uppercase tracking-widest block mb-1">সফল রেফারেল</span>
            <h3 class="text-3xl font-black text-white">{{ number_format($totalUnlocked) }}</h3>
            <div class="mt-4 flex items-center gap-2">
                <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></span>
                <span class="text-[10px] font-black text-emerald-500 uppercase">বোনাস আনলকড</span>
            </div>
        </div>
        <div class="p-6 bg-dark-card border border-white/5 rounded-[32px] shadow-sm">
            <span class="text-[10px] font-black text-slate-500 uppercase tracking-widest block mb-1">পেন্ডিং
                রেফারেল</span>
            <h3 class="text-3xl font-black text-white">{{ number_format($totalLocked) }}</h3>
            <div class="mt-4 flex items-center gap-2">
                <span class="w-1.5 h-1.5 bg-amber-500 rounded-full"></span>
                <span class="text-[10px] font-black text-amber-500 uppercase">টার্গেট পূরণ হয়নি</span>
            </div>
        </div>
        <div class="p-6 bg-dark-card border border-white/5 rounded-[32px] shadow-sm">
            <span class="text-[10px] font-black text-slate-500 uppercase tracking-widest block mb-1">মোট জেনারেটেড
                প্রফিট</span>
            <h3 class="text-3xl font-black text-white">৳ {{ number_format($totalProfit, 2) }}</h3>
            <div class="mt-4 flex items-center gap-2">
                <span class="w-1.5 h-1.5 bg-primary-500 rounded-full"></span>
                <span class="text-[10px] font-black text-primary-500 uppercase">লাইফটাইম আর্নিং</span>
            </div>
        </div>
        <!--<div class="p-6 bg-primary-600 rounded-[32px] shadow-lg shadow-primary-900/40 text-white">
            <span class="text-[10px] font-black uppercase tracking-widest block mb-1 opacity-70">আপনার কমিশন রেট</span>
            <h3 class="text-3xl font-black">৫%</h3>
            <div class="mt-4 flex items-center gap-2">
                <span class="text-[10px] font-black uppercase">প্রতিটি টাস্ক থেকে</span>
            </div>
        </div>-->
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
        <!-- Referral Link Card -->
        <div class="lg:col-span-1 space-y-8">
            <div class="p-8 bg-dark-card border border-white/5 rounded-[40px] shadow-sm relative overflow-hidden group">
                <div
                    class="absolute -right-4 -top-4 w-24 h-24 bg-primary-500/10 rounded-full group-hover:scale-150 transition-transform duration-700">
                </div>
                <h3 class="text-lg font-black text-white mb-2 relative">ইনভাইট লিংক</h3>
                <p class="text-xs text-slate-500 font-bold mb-6 relative">নিচের লিংকটি কপি করে শেয়ার করুন</p>

                <div class="relative mb-6">
                    <input type="text" readonly value="{{ $referralLink }}" id="refLink"
                        class="w-full bg-white/5 border border-white/5 rounded-2xl py-4 pl-4 pr-12 text-xs font-bold text-slate-300 focus:ring-0">
                    <button onclick="copyRefLink()"
                        class="absolute right-2 top-2 bottom-2 px-4 bg-primary-600 hover:bg-primary-500 text-white rounded-xl transition-all shadow-lg shadow-primary-900/40">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3">
                            </path>
                        </svg>
                    </button>
                </div>

                <div class="p-6 bg-slate-900 rounded-3xl text-white">
                    <h4 class="text-xs font-black uppercase tracking-widest text-primary-400 mb-2">কিভাবে এটি কাজ করে?
                    </h4>
                    <ul class="space-y-3">
                        <li class="flex items-start gap-3">
                            <span
                                class="w-4 h-4 bg-primary-600 rounded-full flex items-center justify-center text-[8px] font-black mt-0.5">১</span>
                            <p class="text-[10px] text-slate-400 font-medium leading-relaxed">আপনার রেফারেল যখন ১০ টাকা
                                আয় করবে, তখন আপনি বোনাস পাবেন।</p>
                        </li>
                        <li class="flex items-start gap-3">
                            <span
                                class="w-4 h-4 bg-primary-600 rounded-full flex items-center justify-center text-[8px] font-black mt-0.5">২</span>
                            <p class="text-[10px] text-slate-400 font-medium leading-relaxed">রেফারেল বোনাস সরাসরি আপনার
                                মেইন ব্যালেন্সে যোগ হবে।</p>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Referral List -->
        <div class="lg:col-span-2">
            <div class="p-8 bg-dark-card border border-white/5 rounded-[40px] shadow-sm min-h-[500px] flex flex-col">
                <h3 class="text-lg font-black text-white mb-8 flex items-center justify-between">
                    রেফারেল লিস্ট
                    <span
                        class="text-[10px] font-black text-slate-500 uppercase tracking-widest bg-white/5 px-3 py-1.5 rounded-xl border border-white/5">সকল
                        মেম্বার</span>
                </h3>

                <div class="relative overflow-x-auto flex-1">
                    <table class="w-full text-left">
                        <thead>
                            <tr
                                class="text-[10px] font-black text-slate-500 uppercase tracking-widest border-b border-white/5">
                                <th class="pb-4 pr-4">মেম্বার</th>
                                <th class="pb-4 pr-4">তারিখ</th>
                                <th class="pb-4 pr-4">জেনারেটেড প্রফিট</th>
                                <th class="pb-4">অবস্থা</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5">
                            @forelse($referrals as $ref)
                                <tr class="group">
                                    <td class="py-5 pr-4">
                                        <div class="flex items-center gap-3">
                                            <div
                                                class="w-10 h-10 bg-white/5 border border-white/5 rounded-full flex items-center justify-center text-slate-500 font-black text-xs uppercase shadow-inner">
                                                {{ substr(optional($ref->referredUser)->name ?? 'NA', 0, 2) }}
                                            </div>
                                            <div>
                                                <span
                                                    class="text-xs font-black text-white block">{{ optional($ref->referredUser)->name ?? 'N/A' }}</span>
                                                <span
                                                    class="text-[9px] font-bold text-slate-500 block">{{ optional($ref->referredUser)->mobile_number ?? 'N/A' }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-5 pr-4 text-xs font-bold text-slate-400">
                                        {{ $ref->created_at->format('d M, Y') }}
                                    </td>
                                    <td class="py-5 pr-4">
                                        <span class="text-xs font-black text-primary-500 block">৳
                                            {{ number_format($ref->profit_generated, 2) }}</span>
                                        <span class="text-[9px] font-bold text-slate-500 block">মোট অবদান</span>
                                    </td>
                                    <td class="py-5">
                                        <span
                                            class="px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-widest
                                                {{ $ref->status == 'Unlocked' ? 'bg-emerald-500/10 text-emerald-500 border border-emerald-500/20' : 'bg-amber-500/10 text-amber-500 border border-amber-500/20' }}">
                                            {{ $ref->status }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="py-24 text-center">
                                        <div
                                            class="w-16 h-16 bg-white/5 border border-white/5 rounded-[24px] flex items-center justify-center text-slate-700 mx-auto mb-6">
                                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                                                </path>
                                            </svg>
                                        </div>
                                        <p class="text-sm font-black text-white">এখনো কোনো রেফারেল নেই</p>
                                        <p class="text-xs text-slate-500 mt-2 font-medium">লিংক শেয়ার করে আজই ইনকাম শুরু
                                            করুন!</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($referrals->hasPages())
                    <div class="mt-8 pt-6 border-t border-white/5">
                        {{ $referrals->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            function copyRefLink() {
                const copyText = document.getElementById("refLink");
                copyText.select();
                copyText.setSelectionRange(0, 99999);
                document.execCommand("copy");
                alert("Referral link copied!");
            }
        </script>
    @endpush
</x-user-layout>
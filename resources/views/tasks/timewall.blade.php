<x-user-layout>
    <div class="mb-10">
        <h2 class="text-3xl font-black text-white tracking-tight uppercase">
            {{ setting('timewall_display_name', 'Premium Tasks') }}
        </h2>
        <p class="text-sm text-slate-400 font-medium mt-1">এই সেকশন থেকে সার্ভে, কাজ এবং ক্লিক সম্পন্ন করুন। ক্রেডিট
            অটোমেটিক আপনার একাউন্টে যোগ হবে।</p>
    </div>

    <!-- Enhanced Tabs Nav -->
    <div
        class="flex flex-wrap gap-4 mb-10 bg-dark-card/50 p-2.5 rounded-[32px] border border-white/5 shadow-2xl w-fit backdrop-blur-xl">
        @if(setting('is_social_tasks_active', '1') == '1')
            <a href="{{ route('tasks.index') }}"
                class="px-7 py-3 text-[11px] font-black uppercase rounded-[18px] transition-all flex items-center gap-2 group {{ request()->routeIs('tasks.index') ? 'bg-gradient-to-r from-primary-600 to-primary-500 text-white shadow-lg shadow-primary-900/40 scale-105 active:scale-95' : 'text-slate-500 hover:bg-white/5 hover:text-slate-300' }}">
                <span class="text-sm">📱</span> সোশ্যাল টাস্ক
            </a>
        @endif
        @if(setting('is_timewall_active', '0') == '1')
            <a href="{{ route('timewall.index') }}"
                class="px-7 py-3 text-[11px] font-black uppercase rounded-[18px] transition-all flex items-center gap-2 group {{ request()->routeIs('timewall.index') ? 'bg-gradient-to-r from-orange-600 to-orange-500 text-white shadow-lg shadow-orange-900/40 scale-105 active:scale-95' : 'text-slate-500 hover:bg-white/5 hover:text-slate-300' }}">
                <span class="text-sm">🔥</span> প্রিমিয়াম টাস্ক
            </a>
        @endif
        @if(setting('is_monlix_active', '0') == '1')
            <a href="{{ route('monlix.index') }}"
                class="px-7 py-3 text-[11px] font-black uppercase rounded-[18px] transition-all flex items-center gap-2 group {{ request()->routeIs('monlix.index') ? 'bg-gradient-to-r from-rose-600 to-rose-500 text-white shadow-lg shadow-rose-900/40 scale-105 active:scale-95' : 'text-slate-500 hover:bg-white/5 hover:text-slate-300' }}">
                <span class="text-sm">💎</span> বোনাস ওয়াল
            </a>
        @endif
        @if(setting('is_adsterra_active', '0') == '1')
            <a href="{{ route('adsterra.index') }}"
                class="px-7 py-3 text-[11px] font-black uppercase rounded-[18px] transition-all flex items-center gap-2 group {{ request()->routeIs('adsterra.index') ? 'bg-gradient-to-r from-emerald-600 to-emerald-500 text-white shadow-lg shadow-emerald-900/40 scale-105 active:scale-95' : 'text-slate-500 hover:bg-white/5 hover:text-slate-300' }}">
                <span class="text-sm">📺</span> অ্যাড ভিউ
            </a>
        @endif
    </div>

    <div class="p-0 bg-dark-card border border-white/5 rounded-[40px] shadow-sm overflow-hidden">
        <div class="p-8 pb-0">
            <div class="flex items-center justify-between mb-8">
                <h3 class="text-lg font-black text-white uppercase flex items-center gap-3">
                    <span
                        class="w-10 h-10 bg-orange-500/10 text-orange-500 rounded-xl flex items-center justify-center text-lg border border-orange-500/20">🔥</span>
                    {{ setting('timewall_display_name', 'Premium Tasks') }}
                </h3>
                <span
                    class="text-[10px] font-black uppercase text-slate-500 tracking-widest bg-white/5 px-3 py-1.5 rounded-xl border border-white/5">Secured
                    Connection Active</span>
            </div>
        </div>

        @if($iframeUrl)
            <div class="relative w-full overflow-hidden" style="min-height: 85vh;">
                <iframe src="{{ $iframeUrl }}" style="width: 100%; height: 85vh; border: none; display: block;"
                    allow="camera; microphone; geolocation"></iframe>
            </div>
        @else
            <div class="py-24 text-center">
                <div
                    class="w-20 h-20 bg-rose-500/10 text-rose-500 rounded-[24px] flex items-center justify-center mx-auto mb-6 shadow-sm border border-rose-500/20">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                        </path>
                    </svg>
                </div>
                <p class="text-xl font-black text-white uppercase tracking-tight">API কানেকশন এরর!</p>
                <p class="text-sm text-slate-500 mt-2 font-medium">অ্যাডমিন এখনো TimeWall API কনফিগার করেননি।</p>
            </div>
        @endif
    </div>

    <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="p-8 bg-primary-500/10 rounded-[32px] border border-primary-500/20 relative overflow-hidden group">
            <div class="absolute -right-10 -bottom-10 w-40 h-40 bg-primary-500/10 rounded-full blur-3xl"></div>
            <h4 class="text-sm font-black text-primary-500 uppercase mb-3 flex items-center gap-2 relative">
                কিভাবে পেমেন্ট পাবেন?
                <span class="w-1.5 h-1.5 bg-primary-500 rounded-full"></span>
            </h4>
            <p class="text-xs text-primary-400 font-bold leading-relaxed relative">টাইমওয়ালে কাজ করার পর "Withdraw"
                সেকশনে গিয়ে আমাদের সাইটে ক্রেডিটস ট্র্যান্সফার করুন। ট্র্যান্সফার করার ২-৫ মিনিটের মধ্যে আপনার পয়েন্ট
                আমাদের মেইন ব্যালেন্সে যোগ হয়ে যাবে।</p>
        </div>

        <div class="p-8 bg-amber-500/10 rounded-[32px] border border-amber-500/20 relative overflow-hidden group">
            <div class="absolute -right-10 -bottom-10 w-40 h-40 bg-amber-500/10 rounded-full blur-3xl"></div>
            <h4 class="text-sm font-black text-amber-500 uppercase mb-3 flex items-center gap-2 relative">
                পয়েন্ট রুলস ও ফি (Rules)
                <span class="w-1.5 h-1.5 bg-amber-500 rounded-full"></span>
            </h4>
            <p class="text-xs text-amber-500/80 font-bold leading-relaxed relative italic">
                নোট: অফার ওয়াল থেকে অর্জিত পয়েন্ট মূল ব্যালেন্সে যুক্ত হওয়ার সময় <span
                    class="text-white bg-amber-600 px-1 rounded">৩০% প্ল্যাটফর্ম মেইনটেন্যান্স ফি</span> প্রযোজ্য।
            </p>
        </div>
    </div>
</x-user-layout>
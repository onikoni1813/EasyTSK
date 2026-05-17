<x-user-layout>
    <div class="min-h-[70vh] flex items-center justify-center py-12 px-4">
        <div class="max-w-md w-full text-center">
            <div class="relative mb-12">
                <!-- Glowing background effect -->
                <div class="absolute inset-0 bg-primary-500/20 blur-[100px] rounded-full"></div>

                <!-- Icon -->
                <div
                    class="relative w-32 h-32 bg-dark-card border border-white/10 rounded-[40px] flex items-center justify-center mx-auto shadow-2xl transform hover:rotate-12 transition-transform duration-500">
                    <svg class="w-16 h-16 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z">
                        </path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>

                    <!-- Animated pulse ring -->
                    <div class="absolute inset-0 border-4 border-primary-500/30 rounded-[40px] animate-ping opacity-20">
                    </div>
                </div>
            </div>

            <h1 class="text-4xl font-black text-white mb-4 uppercase tracking-tighter italic">
                মডিউল <span class="text-primary-500 italic">রক্ষণাবেক্ষণ</span>
            </h1>

            <div class="h-1 w-20 bg-gradient-to-r from-primary-600 to-primary-400 mx-auto mb-8 rounded-full"></div>

            <p class="text-slate-400 font-medium text-lg leading-relaxed mb-10">
                এই মডিউলটি বর্তমানে সাময়িকভাবে বন্ধ আছে। আমাদের টিম এটি আরও উন্নত করার জন্য কাজ করছে। অনুগ্রহ করে
                কিছুক্ষণ পর আবার চেষ্টা করুন।
            </p>

            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('dashboard') }}"
                    class="px-8 py-4 bg-primary-600 hover:bg-primary-500 text-white font-black rounded-2xl uppercase tracking-widest transition-all shadow-lg shadow-primary-900/40 transform hover:-translate-y-1">
                    ড্যাশবোর্ডে ফিরে যান
                </a>
                <button onclick="window.history.back()"
                    class="px-8 py-4 bg-white/5 border border-white/10 hover:bg-white/10 text-slate-300 font-black rounded-2xl uppercase tracking-widest transition-all">
                    পিছনে যান
                </button>
            </div>

            <div
                class="mt-12 flex items-center justify-center gap-2 text-[10px] font-black text-slate-500 uppercase tracking-[0.3em]">
                <span class="w-2 h-2 bg-primary-500 rounded-full animate-pulse"></span>
                System Operational: Standby Mode
            </div>
        </div>
    </div>
</x-user-layout>
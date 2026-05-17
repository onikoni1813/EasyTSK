<x-user-layout>
    <div class="min-h-[70vh] flex items-center justify-center py-12 px-4">
        <div class="max-w-md w-full text-center">
            <div class="relative mb-12 text-rose-500">
                <!-- Glowing background effect -->
                <div class="absolute inset-0 bg-rose-500/20 blur-[100px] rounded-full"></div>

                <!-- Icon -->
                <div
                    class="relative w-32 h-32 bg-dark-card border border-white/10 rounded-[40px] flex items-center justify-center mx-auto shadow-2xl">
                    <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                        </path>
                    </svg>
                </div>
            </div>

            <h1 class="text-4xl font-black text-white mb-4 uppercase tracking-tighter italic">
                VPN <span class="text-rose-500">শনাক্ত হয়েছে!</span>
            </h1>

            <div class="h-1 w-20 bg-gradient-to-r from-rose-600 to-rose-400 mx-auto mb-8 rounded-full"></div>

            <p class="text-slate-400 font-medium text-lg leading-relaxed mb-10 italic uppercase tracking-tight">
                টাস্ক মডিউল ব্যবহার করতে হলে আপনাকে অবশ্যই VPN বা প্রক্সি বন্ধ করতে হবে। এটি আমাদের বিজ্ঞাপনদাতাদের
                নিরাপত্তা নীতিমালা।
            </p>

            <div class="glass-card p-6 border-rose-500/20 bg-rose-500/5 mb-10 rounded-3xl">
                <p class="text-[10px] font-black text-rose-500 uppercase tracking-[0.2em] mb-2">Protocol Violation</p>
                <p class="text-xs font-bold text-slate-300">Proxy/VPN Connection Detected from IP: {{ request()->ip() }}
                </p>
            </div>

            <div class="flex flex-col gap-4">
                <button onclick="window.location.reload()"
                    class="px-8 py-4 bg-rose-600 hover:bg-rose-500 text-white font-black rounded-2xl uppercase tracking-widest transition-all shadow-lg shadow-rose-900/40 transform hover:-translate-y-1">
                    VPN বন্ধ করে রিলোড দিন
                </button>
                <a href="{{ route('dashboard') }}"
                    class="text-xs font-black text-slate-500 uppercase tracking-widest hover:text-white transition-colors">
                    ড্যাশবোর্ডে ফিরে যান
                </a>
            </div>
        </div>
    </div>
</x-user-layout>
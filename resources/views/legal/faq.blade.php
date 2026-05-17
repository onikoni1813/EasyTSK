<x-user-layout>
    <div class="max-w-4xl mx-auto py-12">
        <div class="bg-dark-card border border-white/5 rounded-[40px] p-8 md:p-12 shadow-sm">
            <h1 class="text-3xl font-black text-white mb-8 uppercase tracking-tight">
                সাধারণ <span class="text-primary-500">জিজ্ঞাসা (FAQ)</span>
            </h1>

            <div class="space-y-6" x-data="{ active: null }">
                <div class="border border-white/5 rounded-2xl overflow-hidden">
                    <button @click="active = active === 1 ? null : 1"
                        class="w-full text-left p-4 bg-white/5 flex justify-between items-center transition-all hover:bg-white/10">
                        <span class="font-bold text-white uppercase text-sm tracking-tight">কিভাবে কাজ শুরু করব?</span>
                        <svg class="w-5 h-5 text-primary-500 transition-transform"
                            :class="active === 1 ? 'rotate-180' : ''" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7">
                            </path>
                        </svg>
                    </button>
                    <div x-show="active === 1" class="p-4 text-slate-400 text-sm font-medium border-t border-white/5"
                        x-cloak>
                        প্রথমে একটি একাউন্ট খুলুন। এরপর 'কাজসমূহ' সেকশনে গিয়ে যেকোনো টাস্ক বা অফারওয়ালের কাজ সম্পন্ন
                        করুন।
                    </div>
                </div>

                <div class="border border-white/5 rounded-2xl overflow-hidden">
                    <button @click="active = active === 2 ? null : 2"
                        class="w-full text-left p-4 bg-white/5 flex justify-between items-center transition-all hover:bg-white/10">
                        <span class="font-bold text-white uppercase text-sm tracking-tight">ন্যূনতম কত টাকা হলে উইথড্র
                            করা যাবে?</span>
                        <svg class="w-5 h-5 text-primary-500 transition-transform"
                            :class="active === 2 ? 'rotate-180' : ''" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7">
                            </path>
                        </svg>
                    </button>
                    <div x-show="active === 2" class="p-4 text-slate-400 text-sm font-medium border-t border-white/5"
                        x-cloak>
                        আমাদের প্ল্যাটফর্মে ন্যূনতম উইথড্র অ্যামাউন্ট আপনার ওয়ালেট সেকশনে দেখা যাবে। সাধারণত এটি খুব
                        অল্প হয়ে থাকে।
                    </div>
                </div>

                <div class="border border-white/5 rounded-2xl overflow-hidden">
                    <button @click="active = active === 3 ? null : 3"
                        class="w-full text-left p-4 bg-white/5 flex justify-between items-center transition-all hover:bg-white/10">
                        <span class="font-bold text-white uppercase text-sm tracking-tight">পেমেন্ট পেতে কত সময়
                            লাগে?</span>
                        <svg class="w-5 h-5 text-primary-500 transition-transform"
                            :class="active === 3 ? 'rotate-180' : ''" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7">
                            </path>
                        </svg>
                    </button>
                    <div x-show="active === 3" class="p-4 text-slate-400 text-sm font-medium border-t border-white/5"
                        x-cloak>
                        উইথড্র রিকোয়েস্ট করার পর ২৪-৪৮ ঘণ্টার মধ্যে আপনার বিকাশ বা নগদ নম্বরে টাকা পৌঁছে যাবে।
                    </div>
                </div>
            </div>

            <div class="mt-12 pt-8 border-t border-white/5">
                <a href="{{ auth()->check() ? route('dashboard') : route('home') }}"
                    class="inline-flex items-center text-primary-500 font-bold hover:underline">
                    ← {{ auth()->check() ? 'ড্যাশবোর্ডে ফিরে যান' : 'হোম পেজে ফিরে যান' }}
                </a>
            </div>
        </div>
    </div>
</x-user-layout>
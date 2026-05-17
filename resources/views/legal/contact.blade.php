<x-user-layout>
    <div class="max-w-4xl mx-auto py-12">
        <div class="bg-dark-card border border-white/5 rounded-[40px] p-8 md:p-12 shadow-sm">
            <h1 class="text-3xl font-black text-white mb-8 uppercase tracking-tight">
                আমাদের সাথে <span class="text-primary-500">যোগাযোগ করুন</span>
            </h1>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="space-y-6 text-slate-400 font-medium">
                    <p>আপনার কোনো সমস্যা বা প্রশ্ন থাকলে সরাসরি আমাদের সাপোর্ট টিকেট সেকশনে মেসেজ দিতে পারেন অথবা নিচের
                        মাধ্যমে যোগাযোগ করুন।</p>

                    <!--<div class="flex items-center gap-4 text-white">
                        <div
                            class="w-12 h-12 bg-primary-500/10 rounded-2xl flex items-center justify-center text-primary-500 text-xl border border-primary-500/20">
                            📧</div>
                        <div>
                            <p class="text-[10px] uppercase font-black tracking-widest text-slate-500">ইমেইল করুন</p>
                            <p class="font-bold">support@easytsk.com</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-4 text-white">
                        <div
                            class="w-12 h-12 bg-blue-500/10 rounded-2xl flex items-center justify-center text-blue-500 text-xl border border-blue-500/20">
                            💬</div>
                        <div>
                            <p class="text-[10px] uppercase font-black tracking-widest text-slate-500">টেলিগ্রাম সাপোর্ট
                            </p>
                            <p class="font-bold">@easytsk_support</p>
                        </div>-->
                </div>
            </div>

            <div class="p-6 bg-white/5 rounded-3xl border border-white/5">
                <h3 class="text-lg font-bold text-white mb-4 uppercase tracking-tight">সাপোর্ট টিকেট</h3>
                <p class="text-sm text-slate-400 mb-6">দ্রুত সমাধানের জন্য আমাদের সাপোর্ট টিকেট সিস্টেম ব্যবহার
                    করুন।</p>
                <a href="{{ route('support.index') }}"
                    class="inline-flex w-full items-center justify-center px-6 py-4 bg-primary-600 text-white font-black rounded-2xl uppercase tracking-widest hover:bg-primary-500 transition-all shadow-lg shadow-primary-900/40">
                    সাপোর্ট টিকেট খুলুন 🚀
                </a>
            </div>
        </div>

        <div class="mt-12 pt-8 border-t border-white/5">
            <a href="{{ route('dashboard') }}"
                class="inline-flex items-center text-primary-500 font-bold hover:underline">
                ← ড্যাশবোর্ডে ফিরে যান
            </a>
        </div>
    </div>
    </div>
</x-user-layout>
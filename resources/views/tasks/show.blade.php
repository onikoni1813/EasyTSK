<x-user-layout>
    <div class="mb-10">
        <a href="{{ route('tasks.index') }}"
            class="inline-flex items-center gap-2 px-6 py-3 bg-dark-card border border-white/5 rounded-2xl text-[10px] font-black uppercase tracking-widest text-slate-400 hover:bg-white/5 hover:text-primary-500 transition-all shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
            পিছে ফিরে যান
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
        <!-- Sidebar: Info -->
        <div class="lg:col-span-1 space-y-8">
            <div class="p-8 bg-dark-card border border-white/5 rounded-[40px] shadow-sm relative overflow-hidden group">
                <div
                    class="absolute -right-4 -top-4 w-24 h-24 bg-primary-500/10 rounded-full group-hover:scale-150 transition-transform duration-700">
                </div>
                <div class="relative">
                    <h3 class="text-sm font-black text-white mb-6 uppercase tracking-widest flex items-center gap-2">
                        টাস্ক ইনফো
                        <span class="w-1.5 h-1.5 bg-primary-500 rounded-full"></span>
                    </h3>

                    <div class="space-y-5">
                        <div class="flex justify-between items-center p-4 bg-white/5 rounded-2xl">
                            <span
                                class="text-[10px] font-black text-slate-500 uppercase tracking-widest">পুরস্কার</span>
                            <span class="text-lg font-black text-primary-500">{{ number_format($task->points) }} <span
                                    class="text-[10px] opacity-70">PTS</span></span>
                        </div>

                        <div class="flex justify-between items-center p-4 border border-white/5 rounded-2xl">
                            <span class="text-[10px] font-black text-slate-500 uppercase tracking-widest">কোটা
                                বাকি</span>
                            <span class="text-sm font-black text-slate-300">{{ $task->quota_remaining }} টি</span>
                        </div>

                        <div class="flex justify-between items-center p-4 border border-white/5 rounded-2xl">
                            <span class="text-[10px] font-black text-slate-500 uppercase tracking-widest">টাস্ক
                                ক্যাটাগরি</span>
                            <span
                                class="px-3 py-1 text-[9px] font-black uppercase bg-indigo-500/10 text-indigo-500 rounded-xl border border-indigo-500/20">Custom</span>
                        </div>
                    </div>
                </div>
            </div>

            <div x-data="{ showRules: false }"
                class="p-8 bg-slate-900 rounded-[40px] shadow-2xl text-white relative overflow-hidden border border-white/5">
                <div class="absolute top-0 right-0 p-8 opacity-10">
                    <svg class="w-16 h-16" fill="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z" />
                    </svg>
                </div>
                <h4 class="text-xs font-black mb-3 uppercase tracking-widest text-primary-400 italic">সতর্কতা / WARNING
                </h4>
                <p class="text-xs font-medium leading-relaxed text-slate-400">
                    ভুল প্রমাণ বা ফেক স্ক্রিনশট দিলে আপনার অ্যাকাউন্ট <span
                        class="text-rose-500 font-black underline decoration-rose-500/30">স্থায়ীভাবে ব্যান</span> করা
                    হতে পারে এবং আপনার ট্রাস্ট স্কোর কমে যাবে।
                </p>
                <div @click="showRules = true" class="mt-6 flex items-center gap-2 group cursor-pointer">
                    <span
                        class="text-[10px] font-black text-white group-hover:text-primary-400 transition-colors uppercase tracking-widest">নিয়মাবলি
                        দেখুন →</span>
                </div>

                <!-- Rules Modal -->
                <template x-teleport="body">
                    <div x-show="showRules" x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                        x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
                        x-transition:leave-end="opacity-0"
                        class="fixed inset-0 z-[999] flex items-center justify-center p-4 bg-black/80 backdrop-blur-sm"
                        x-cloak>

                        <div @click.away="showRules = false"
                            class="bg-dark-card border border-white/10 w-full max-w-lg rounded-[48px] overflow-hidden shadow-2xl relative"
                            x-show="showRules" x-transition:enter="transition ease-out duration-300"
                            x-transition:enter-start="scale-95 translate-y-4"
                            x-transition:enter-end="scale-100 translate-y-0">

                            <!-- Modal Decoration -->
                            <div class="absolute -top-24 -right-24 w-64 h-64 bg-primary-500/10 rounded-full blur-3xl">
                            </div>

                            <div class="p-10 relative">
                                <div class="flex items-center justify-between mb-8">
                                    <h3
                                        class="text-xl font-black text-white uppercase tracking-tight flex items-center gap-3">
                                        <span
                                            class="w-10 h-10 bg-primary-500/10 text-primary-500 rounded-2xl flex items-center justify-center text-lg border border-primary-500/20">📋</span>
                                        কাজের নিয়মাবলি
                                    </h3>
                                    <button @click="showRules = false"
                                        class="w-10 h-10 bg-white/5 rounded-2xl flex items-center justify-center text-slate-500 hover:text-rose-500 hover:bg-rose-500/10 transition-all">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </button>
                                </div>

                                <div class="space-y-6">
                                    <div class="p-6 bg-white/5 rounded-3xl border border-white/5 space-y-4">
                                        <div class="flex gap-4">
                                            <span
                                                class="shrink-0 w-6 h-6 bg-primary-500 text-dark font-black text-[10px] rounded-lg flex items-center justify-center">১</span>
                                            <p
                                                class="text-[11px] font-bold text-slate-300 leading-relaxed uppercase tracking-tight">
                                                <span class="text-primary-500">সঠিক প্রমাণ দিন:</span> কাজের নির্দেশনায়
                                                যা চাওয়া হয়েছে, ঠিক সেই মুহূর্তের ক্লিয়ার স্ক্রিনশট বা টেক্সট প্রমাণ
                                                হিসেবে জমা দিন।
                                            </p>
                                        </div>
                                        <div class="flex gap-4">
                                            <span
                                                class="shrink-0 w-6 h-6 bg-rose-500 text-white font-black text-[10px] rounded-lg flex items-center justify-center">২</span>
                                            <p
                                                class="text-[11px] font-bold text-slate-300 leading-relaxed uppercase tracking-tight">
                                                <span class="text-rose-500">ফেক সাবমিট কড়া নিষেধ:</span> ইন্টারনেট থেকে
                                                ডাউনলোড করা ছবি বা অন্যের জমা দেওয়া ছবি দিলে আপনার একাউন্ট সাথে সাথে
                                                ব্যান হতে পারে।
                                            </p>
                                        </div>
                                        <div class="flex gap-4">
                                            <span
                                                class="shrink-0 w-6 h-6 bg-amber-500 text-dark font-black text-[10px] rounded-lg flex items-center justify-center">৩</span>
                                            <p
                                                class="text-[11px] font-bold text-slate-300 leading-relaxed uppercase tracking-tight">
                                                <span class="text-amber-500">ভিপিএন (VPN) সতর্কতা:</span> নির্দেশ না
                                                থাকলে কখনোই ভিপিএন বা প্রক্সি ব্যবহার করে কাজ জমা দেবেন না।
                                            </p>
                                        </div>
                                        <div class="flex gap-4">
                                            <span
                                                class="shrink-0 w-6 h-6 bg-indigo-500 text-white font-black text-[10px] rounded-lg flex items-center justify-center">৪</span>
                                            <p
                                                class="text-[11px] font-bold text-slate-300 leading-relaxed uppercase tracking-tight">
                                                <span class="text-indigo-500">ডাবল একাউন্ট পলিসি:</span> একই ওয়াইফাই বা
                                                ডিভাইস থেকে একাধিক একাউন্ট খুলে কাজ করা সম্পূর্ণ নিষিদ্ধ।
                                            </p>
                                        </div>
                                        <div class="flex gap-4">
                                            <span
                                                class="shrink-0 w-6 h-6 bg-emerald-500 text-dark font-black text-[10px] rounded-lg flex items-center justify-center">৫</span>
                                            <p
                                                class="text-[11px] font-bold text-slate-300 leading-relaxed uppercase tracking-tight">
                                                <span class="text-emerald-500">ট্রাস্ট স্কোর পেনাল্টি:</span> ভুল কাজ
                                                জমা দিলে আপনার ট্রাস্ট স্কোর কমে যাবে এবং দামি কাজগুলো আর পাবেন না।
                                            </p>
                                        </div>
                                    </div>

                                    <button @click="showRules = false"
                                        class="w-full py-5 bg-primary-600 hover:bg-primary-500 text-white rounded-[24px] text-[11px] font-black uppercase tracking-widest transition-all shadow-xl shadow-primary-900/40">
                                        আমি নিয়মগুলো বুঝেছি
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
        </div>

        <!-- Main Content: Instructions & Form -->
        <div class="lg:col-span-2 space-y-10">
            <div class="p-10 bg-dark-card border border-white/5 rounded-[48px] shadow-sm">
                <h1 class="text-3xl font-black text-white mb-8 leading-tight tracking-tight">{{ $task->title }}</h1>

                {{-- টাস্কের বিস্তারিত (উপরে) --}}
                <div class="mb-10">
                    <h4 class="text-[10px] font-black text-primary-500 uppercase tracking-widest mb-4 inline-block bg-primary-500/10 px-4 py-1.5 rounded-full border border-primary-500/20">
                        টাস্কের বিস্তারিত</h4>
                    <div class="p-8 bg-white/5 border border-white/5 rounded-[32px] text-sm font-medium text-slate-300 leading-relaxed">
                        {!! nl2br(e($task->description)) !!}
                    </div>
                </div>

                {{-- Instruction Images (নিচে) --}}
                @if(!empty($task->instruction_images))
                    <div class="mb-8">
                        <h4 class="text-[10px] font-black text-primary-500 uppercase tracking-widest mb-3 inline-block bg-primary-500/10 px-4 py-1.5 rounded-full border border-primary-500/20">
                            📸 নির্দেশনার ছবি ({{ count($task->instruction_images) }}টি)
                        </h4>
                        <div class="grid grid-cols-1 gap-4">
                            @foreach($task->instruction_images as $imgIdx => $imgPath)
                                <div class="rounded-[28px] overflow-hidden border border-white/10 shadow-xl cursor-zoom-in"
                                    onclick="openLightbox('{{ Storage::url($imgPath) }}')">
                                    <img src="{{ Storage::url($imgPath) }}"
                                        alt="Instruction {{ $imgIdx + 1 }}"
                                        class="w-full h-auto object-contain">
                                    <div class="py-1.5 text-center text-[9px] text-slate-600 uppercase tracking-widest bg-black/20">
                                        ছবি {{ $imgIdx + 1 }} — ক্লিক করে বড় করুন
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Lightbox --}}
                    <div id="instr-lightbox" onclick="closeLightbox()"
                        class="hidden fixed inset-0 z-[200] bg-black/95 flex items-center justify-center p-4 cursor-zoom-out">
                        <img id="instr-lightbox-img" src="" class="max-w-full max-h-full rounded-2xl shadow-2xl">
                    </div>
                @endif

                @if($task->external_link)
                    <div class="mb-12">
                        <a href="{{ $task->external_link }}" target="_blank"
                            class="flex items-center justify-center gap-4 px-10 py-6 bg-blue-600 text-white rounded-[32px] text-sm font-black uppercase tracking-widest hover:bg-blue-500 transition-all shadow-xl shadow-blue-900/40 group">
                            🔗 টাস্ক লিংক ওপেন করুন
                            <svg class="w-5 h-5 group-hover:translate-x-1 group-hover:-translate-y-1 transition-transform"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                            </svg>
                        </a>
                        <p class="text-center text-[10px] font-bold text-slate-500 mt-4 uppercase tracking-widest">লিংকটি নতুন ট্যাবে ওপেন হবে</p>
                    </div>
                @endif

                <div class="h-px bg-white/5 mb-12"></div>

                <form action="{{ route('tasks.submit', $task) }}" method="POST" enctype="multipart/form-data"
                    onsubmit="return disableSubmit(this)">
                    @csrf

                    @if($errors->any())
                        <div
                            class="mb-8 p-6 bg-rose-500/10 border border-rose-500/20 rounded-[32px] animate-in fade-in slide-in-from-top-4 duration-300">
                            <div class="flex items-center gap-3 mb-3">
                                <div
                                    class="w-8 h-8 bg-rose-500/20 text-rose-500 rounded-xl flex items-center justify-center">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                                        </path>
                                    </svg>
                                </div>
                                <h5 class="text-xs font-black text-white uppercase tracking-widest">সাবমিশন ত্রুটি / ERROR
                                </h5>
                            </div>
                            <ul class="space-y-1">
                                @foreach($errors->all() as $error)
                                    <li class="text-[10px] font-bold text-rose-400 uppercase tracking-tight">• {{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <h4
                        class="text-[10px] font-black text-primary-500 uppercase tracking-widest mb-8 bg-primary-500/10 px-5 py-2 rounded-full inline-block border border-primary-500/20">
                        ✅ প্রমাণ জমা দিন</h4>

                    <div class="space-y-8">
                        @if($task->requires_text_proof)
                            <div>
                                <label class="block mb-3 text-[10px] font-black text-slate-400 uppercase tracking-widest">📝
                                    টেক্সট প্রমাণ এখানে লিখুন বা পেস্ট করুন</label>
                                <textarea name="proof_text" rows="4"
                                    class="w-full p-6 bg-white/5 border border-white/5 rounded-[32px] text-sm font-medium text-white focus:bg-white/10 focus:ring-primary-500 focus:border-primary-500 transition-all"
                                    placeholder="এখানে লিখুন বা পেস্ট করুন..." required></textarea>
                                <p
                                    class="mt-3 text-[10px] font-bold text-rose-400 uppercase tracking-tight flex items-center gap-1.5">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                    </svg>
                                    ⚠️ ভুল প্রমাণ দিলে টাস্ক রিজেক্ট হবে।
                                </p>
                            </div>
                        @endif

                        @if($task->requires_email_proof)
                            <div class="space-y-4">
                                {{-- Gmail Field --}}
                                <div>
                                    <label class="block mb-3 text-[10px] font-black text-slate-400 uppercase tracking-widest">📧
                                        Gmail Address জমা দিন</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-6 flex items-center pointer-events-none">
                                            <svg class="w-5 h-5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                        <input type="email" name="proof_email"
                                            class="w-full pl-14 pr-6 py-5 bg-white/5 border border-white/5 rounded-[32px] text-sm font-medium text-white focus:bg-white/10 focus:border-primary-500 outline-none transition-all"
                                            placeholder="example@gmail.com" required
                                            value="{{ old('proof_email') }}" />
                                    </div>
                                </div>

                                {{-- Password Field --}}
                                <div>
                                    <label class="block mb-3 text-[10px] font-black text-slate-400 uppercase tracking-widest">🔐
                                        Gmail Password জমা দিন</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-6 flex items-center pointer-events-none">
                                            <svg class="w-5 h-5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                            </svg>
                                        </div>
                                        <input type="text" name="proof_password"
                                            class="w-full pl-14 pr-6 py-5 bg-white/5 border border-white/5 rounded-[32px] text-sm font-medium text-white focus:bg-white/10 focus:border-primary-500 outline-none transition-all"
                                            placeholder="Gmail Password" required
                                            value="{{ old('proof_password') }}" />
                                    </div>
                                </div>

                                <p class="text-[10px] font-bold text-amber-400 uppercase tracking-tight flex items-center gap-1.5">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                    </svg>
                                    ⚠️ সঠিক তথ্য দিন — রিভিউ করে পয়েন্ট দেওয়া হবে।
                                </p>
                            </div>
                        @endif

                        @if($task->requires_image_proof)
                            <div>
                                <label
                                    class="block mb-3 text-[10px] font-black text-slate-400 uppercase tracking-widest">স্ক্রিনশট
                                    আপলোড (Screenshot Proof)</label>
                                <div class="group relative">
                                    <label
                                        class="flex flex-col items-center justify-center w-full h-56 border-2 border-white/5 border-dashed rounded-[40px] cursor-pointer bg-white/5 hover:bg-white/10 hover:border-primary-500 transition-all">
                                        <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                            <div
                                                class="w-16 h-16 bg-white/5 rounded-2xl flex items-center justify-center text-slate-500 mb-4 group-hover:scale-110 group-hover:text-primary-500 transition-all shadow-sm">
                                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                                    </path>
                                                </svg>
                                            </div>
                                            <p class="text-xs font-black text-slate-400 uppercase tracking-tighter">ক্লিক
                                                করে স্ক্রিনশট অ্যাটাচ করুন</p>
                                            <p class="text-[9px] text-slate-500 mt-1 uppercase tracking-widest">PNG, JPG or
                                                JPEG (Max 4MB)</p>
                                        </div>
                                        <input name="proof_image" type="file" id="proof_image" class="hidden" required
                                            onchange="previewImage(this)" />
                                    </label>
                                    <div id="image-preview"
                                        class="hidden absolute inset-0 rounded-[40px] overflow-hidden bg-dark-card pointer-events-none border-2 border-primary-500 shadow-2xl shadow-primary-900/40">
                                        <img id="preview-img" class="w-full h-full object-cover">
                                        <button type="button" onclick="resetPreview()"
                                            class="absolute top-4 right-4 bg-white/90 backdrop-blur-sm p-3 rounded-2xl shadow-xl pointer-events-auto">
                                            <svg class="w-4 h-4 text-rose-500" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <button type="submit" id="submit-btn"
                            class="flex items-center justify-center w-full py-6 bg-primary-600 text-white border-0 rounded-[32px] text-sm font-black uppercase tracking-widest hover:bg-white hover:text-dark-card transition-all shadow-xl shadow-primary-900/40 hover:shadow-white/10 disabled:opacity-50 disabled:cursor-not-allowed">
                            <span id="submit-text">সাবমিট টাস্ক (COMPLETE TASK)</span>
                            <span id="submit-spinner" class="hidden ml-2">
                                <svg class="animate-spin w-5 h-5" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                        stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                                </svg>
                            </span>
                            <svg id="submit-icon" class="w-5 h-5 ml-2 group-hover:scale-110 transition-transform"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7"></path>
                            </svg>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            function previewImage(input) {
                const preview = document.getElementById('image-preview');
                const img = document.getElementById('preview-img');
                if (input.files && input.files[0]) {
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        img.src = e.target.result;
                        preview.classList.remove('hidden');
                    }
                    reader.readAsDataURL(input.files[0]);
                }
            }

            function resetPreview() {
                const preview = document.getElementById('image-preview');
                const input = document.getElementById('proof_image');
                input.value = '';
                preview.classList.add('hidden');
            }

            // ── Double-Click / Idempotency Protection ──────────────────────
            function disableSubmit(form) {
                const btn = document.getElementById('submit-btn');
                const text = document.getElementById('submit-text');
                const spinner = document.getElementById('submit-spinner');
                const icon = document.getElementById('submit-icon');

                if (btn.disabled) return false; // Already submitted

                btn.disabled = true;
                text.textContent = 'সাবমিট হচ্ছে... (SUBMITTING)';
                icon.classList.add('hidden');
                spinner.classList.remove('hidden');

                // Re-enable after 15 seconds if something goes wrong
                setTimeout(function () {
                    if (btn.disabled) {
                        btn.disabled = false;
                        text.textContent = 'সাবমিট টাস্ক (COMPLETE TASK)';
                        spinner.classList.add('hidden');
                        icon.classList.remove('hidden');
                    }
                }, 15000);

                return true;
            }

            // ── Instruction Image Lightbox ──────────────────────────────────
            function openLightbox(src) {
                document.getElementById('instr-lightbox-img').src = src;
                document.getElementById('instr-lightbox').classList.remove('hidden');
            }
            function closeLightbox() {
                document.getElementById('instr-lightbox').classList.add('hidden');
            }
        </script>
    @endpush
</x-user-layout>
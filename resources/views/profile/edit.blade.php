<x-user-layout>
    <div class="mb-10">
        <h2 class="text-3xl font-black text-white tracking-tight leading-tight uppercase">প্রোফাইল <span
                class="text-primary-500">সেটিং</span></h2>
        <p class="text-sm text-slate-400 font-medium mt-1">আপনার একাউন্ট তথ্য ও নিরাপত্তা পরিচালনা করুন।</p>
    </div>

    <div class="space-y-10">
        <!-- Balance Overview (Premium Card) -->
        <div
            class="p-8 bg-dark-card border border-white/5 rounded-[40px] shadow-sm flex flex-col md:flex-row items-center justify-between gap-6 relative overflow-hidden group">
            <div
                class="absolute -bottom-10 -right-10 w-64 h-64 bg-primary-600/10 rounded-full blur-3xl group-hover:bg-primary-600/20 transition-all duration-700">
            </div>

            <div class="relative">
                <p class="text-[10px] font-black uppercase tracking-widest text-primary-500 mb-2">বর্তমান মেইন ব্যালেন্স
                </p>
                <div class="flex items-baseline gap-2">
                    <p class="text-5xl font-black tracking-tighter text-white">
                        {{ number_format(auth()->user()->points) }}</p>
                    <span class="text-xs font-black uppercase tracking-widest text-slate-500">PTS</span>
                </div>
            </div>

            <div class="flex gap-8 relative">
                <div class="text-center md:text-right border-l md:border-l-0 md:border-r border-white/5 px-6">
                    <p class="text-[10px] font-black uppercase tracking-widest text-slate-500 mb-1">ট্রাস্ট স্কোর</p>
                    <p class="text-2xl font-black text-white">{{ (int) auth()->user()->trust_score }}%</p>
                </div>
                <div class="text-center md:text-right">
                    <p class="text-[10px] font-black uppercase tracking-widest text-slate-500 mb-1">ভেরিফিকেশন</p>
                    <span
                        class="px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-widest {{ auth()->user()->kyc_status == 'approved' ? 'bg-emerald-500/10 text-emerald-500' : 'bg-rose-500/10 text-rose-500' }}">
                        {{ auth()->user()->kyc_status ?? 'Unverified' }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Social Actions / Tutorials -->
        @php
            $youtubeLink = setting('youtube_tutorial_link', '');
            $telegramLink = setting('telegram_channel_link', '');
        @endphp

        @if($youtubeLink || $telegramLink)
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                @if($youtubeLink)
                    <a href="{{ $youtubeLink }}" target="_blank"
                        class="flex items-center gap-4 p-5 bg-rose-600 hover:bg-rose-500 active:bg-rose-700 text-white rounded-[24px] shadow-lg shadow-rose-900/40 transition-all hover:-translate-y-1 group">
                        <div
                            class="w-12 h-12 bg-white/20 rounded-2xl flex items-center justify-center shrink-0 group-hover:scale-110 transition-transform">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z">
                                </path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <h4 class="text-sm font-black uppercase tracking-widest mb-1">Work Tutorial</h4>
                            <p class="text-[10px] font-bold text-rose-200 leading-snug">Having trouble understanding the work?
                                Watch our video tutorial!</p>
                        </div>
                    </a>
                @endif

                @if($telegramLink)
                    <a href="{{ $telegramLink }}" target="_blank"
                        class="flex items-center gap-4 p-5 bg-[#0088cc] hover:bg-[#0077b5] active:bg-[#006699] text-white rounded-[24px] shadow-lg shadow-[#0088cc]/30 transition-all hover:-translate-y-1 group">
                        <div
                            class="w-12 h-12 bg-white/20 rounded-2xl flex items-center justify-center shrink-0 group-hover:scale-110 transition-transform">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M11.944 0A12 12 0 0 0 0 12a12 12 0 0 0 12 12 12 12 0 0 0 12-12A12 12 0 0 0 12 0a12 12 0 0 0-.056 0zm4.962 7.224c.1-.002.321.023.465.14a.506.506 0 0 1 .171.325c.016.093.036.306.02.472-.18 1.898-.962 6.502-1.36 8.627-.168.9-.499 1.201-.82 1.23-.696.065-1.225-.46-1.9-.902-1.056-.693-1.653-1.124-2.678-1.8-1.185-.78-.417-1.21.258-1.91.177-.184 3.247-2.977 3.307-3.23.007-.032.014-.15-.056-.212s-.174-.041-.249-.024c-.106.024-1.793 1.14-5.061 3.345-.48.33-.913.49-1.302.48-.428-.008-1.252-.241-1.865-.44-.752-.245-1.349-.374-1.297-.789.027-.216.325-.437.893-.663 3.498-1.524 5.83-2.529 6.998-3.014 3.332-1.386 4.025-1.627 4.476-1.635z">
                                </path>
                            </svg>
                        </div>
                        <div>
                            <h4 class="text-sm font-black uppercase tracking-widest mb-1">Live Updates</h4>
                            <p class="text-[10px] font-bold text-blue-100 leading-snug">Join our Telegram to get new job updates
                                and payment proof!</p>
                        </div>
                    </a>
                @endif
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
            <div class="space-y-10">
                <div class="p-10 bg-dark-card border border-white/5 rounded-[48px] shadow-sm">
                    <h3 class="text-lg font-black text-white mb-8 flex items-center gap-2">
                        ব্যক্তিগত তথ্য
                        <span class="w-1.5 h-1.5 bg-primary-500 rounded-full"></span>
                    </h3>
                    <div>
                        @include('profile.partials.update-profile-information-form')
                    </div>
                </div>

                <div class="p-10 bg-dark-card border border-white/5 rounded-[48px] shadow-sm">
                    <h3 class="text-lg font-black text-white mb-8 flex items-center gap-2">
                        পাসওয়ার্ড পরিবর্তন
                        <span class="w-1.5 h-1.5 bg-indigo-500 rounded-full"></span>
                    </h3>
                    <div class="max-w-xl">
                        @include('profile.partials.update-password-form')
                    </div>
                </div>
            </div>

            <div class="space-y-10">
                <!-- KYC Status Section -->
                <div class="p-10 bg-dark-card border border-white/5 rounded-[48px] shadow-sm relative overflow-hidden">
                    <div class="absolute top-0 right-0 p-10 opacity-5">
                        <svg class="w-20 h-20 text-white" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4zm-2 16l-4-4 1.41-1.41L10 14.17l6.59-6.59L18 9l-8 8z" />
                        </svg>
                    </div>

                    <h3 class="text-lg font-black text-white mb-8 flex items-center gap-2 relative">
                        KYC ভেরিফিকেশন
                        <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></span>
                    </h3>

                    <div
                        class="p-8 rounded-[32px] flex flex-col gap-6 relative {{ auth()->user()->kyc_status == 'approved' ? 'bg-emerald-500/5 border border-emerald-500/20' : (auth()->user()->kyc_status == 'pending' ? 'bg-amber-500/5 border border-amber-500/20' : 'bg-white/5 border border-white/5') }}">
                        <div class="flex items-center justify-between w-full">
                            <div class="flex flex-col">
                                <span class="text-[10px] font-black uppercase tracking-widest text-slate-500">বর্তমান
                                    অবস্থা</span>
                                <span
                                    class="text-lg font-black uppercase tracking-tighter {{ auth()->user()->kyc_status == 'approved' ? 'text-emerald-500' : (auth()->user()->kyc_status == 'pending' ? 'text-amber-500' : 'text-slate-400') }}">
                                    {{ auth()->user()->kyc_status ?? 'NOT VERIFIED' }}
                                </span>
                            </div>
                            <div
                                class="w-12 h-12 rounded-2xl flex items-center justify-center {{ auth()->user()->kyc_status == 'approved' ? 'bg-emerald-600 text-white' : 'bg-white/5 text-slate-500 border border-white/5' }} shadow-sm">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z">
                                    </path>
                                </svg>
                            </div>
                        </div>

                        @if(auth()->user()->kyc_status != 'approved' && auth()->user()->kyc_status != 'pending')
                            @php $requireKYC = \App\Models\Setting::get('require_kyc_for_withdrawal', false); @endphp
                            @if($requireKYC)
                                <p class="text-xs font-medium text-slate-400 leading-relaxed">পেমেন্ট উইথড্র করার জন্য আপনার
                                    আইডি ভেরিফাই করা বাধ্যতামূলক। দ্রুত ভেরিফিকেশন সম্পন্ন করুন।</p>
                                <a href="{{ route('kyc.index') }}"
                                    class="flex items-center justify-center w-full py-5 bg-white text-dark-card border-0 rounded-2xl text-[10px] font-black uppercase tracking-widest hover:bg-primary-500 hover:text-white transition-all shadow-xl shadow-black/20">Verify
                                    Identity Now →</a>
                            @else
                                <p class="text-xs font-medium text-slate-400 leading-relaxed">আপনার অ্যাকাউন্ট আরো সুরক্ষিত করতে
                                    আইডি ভেরিফিকেশন সম্পন্ন করুন (ঐচ্ছিক)।</p>
                                <a href="{{ route('kyc.index') }}"
                                    class="flex items-center justify-center w-full py-5 bg-white/5 text-slate-400 border border-white/5 rounded-2xl text-[10px] font-black uppercase tracking-widest hover:bg-white/10 hover:text-white transition-all">Verify
                                    Now (Optional)</a>
                            @endif
                        @endif
                    </div>
                </div>

                <div class="p-10 bg-rose-500/5 border border-rose-500/20 rounded-[48px] shadow-sm">
                    <h3 class="text-lg font-black text-rose-500 mb-8 flex items-center gap-2">
                        একাউন্ট বন্ধ করুন
                        <span class="w-1.5 h-1.5 bg-rose-500 rounded-full"></span>
                    </h3>
                    <div class="max-w-xl text-white">
                        @include('profile.partials.delete-user-form')
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-user-layout>
<section>
    <header>
        <h2 class="text-lg font-black text-white uppercase tracking-tight">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-1 text-sm text-slate-400 font-medium">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-8 space-y-8">
        @csrf
        @method('patch')

        <!-- Section: Basic Info -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="space-y-2">
                <x-input-label for="full_name" :value="__('Full Name (আসল নাম)')" class="text-slate-500 uppercase text-[10px] font-black tracking-widest ml-1" />
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-500 group-focus-within:text-primary-500 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                    <x-text-input id="full_name" name="full_name" type="text" class="block w-full pl-11 bg-white/5 border-white/5 text-white focus:bg-white/10 focus:ring-primary-500 rounded-2xl transition-all" :value="old('full_name', $user->full_name)" autocomplete="name" placeholder="John Doe" />
                </div>
                <x-input-error class="mt-2" :messages="$errors->get('full_name')" />
            </div>

            <div class="space-y-2">
                <x-input-label for="email" :value="__('Email (ইমেইল)')" class="text-slate-500 uppercase text-[10px] font-black tracking-widest ml-1" />
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-500 group-focus-within:text-primary-500 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <x-text-input id="email" name="email" type="email" class="block w-full pl-11 bg-white/5 border-white/5 text-white focus:bg-white/10 focus:ring-primary-500 rounded-2xl transition-all" :value="old('email', $user->email)" required autocomplete="username" placeholder="user@example.com" />
                </div>
                <x-input-error class="mt-2" :messages="$errors->get('email')" />
            </div>
        </div>

        <!-- Section: Payments -->
        <div class="pt-4 pb-2 border-t border-white/5">
            <h4 class="text-[10px] font-black text-primary-500 uppercase tracking-[0.2em] mb-6">Payment Methods (পেমেন্ট তথ্য)</h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <x-input-label for="bkash_number" :value="__('Bkash Number')" class="text-slate-500 uppercase text-[10px] font-black tracking-widest ml-1" />
                    <div class="relative group">
                        <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-[10px] font-black text-slate-500 group-focus-within:text-pink-500 transition-colors">BK</span>
                        <x-text-input id="bkash_number" name="bkash_number" type="text" class="block w-full pl-11 bg-white/5 border-white/5 text-white focus:bg-white/10 focus:ring-primary-500 rounded-2xl transition-all" :value="old('bkash_number', $user->bkash_number)" placeholder="017XXXXXXXX" />
                    </div>
                    <x-input-error class="mt-2" :messages="$errors->get('bkash_number')" />
                </div>

                <div class="space-y-2">
                    <x-input-label for="nagad_number" :value="__('Nagad Number')" class="text-slate-500 uppercase text-[10px] font-black tracking-widest ml-1" />
                    <div class="relative group">
                        <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-[10px] font-black text-slate-500 group-focus-within:text-orange-500 transition-colors">NG</span>
                        <x-text-input id="nagad_number" name="nagad_number" type="text" class="block w-full pl-11 bg-white/5 border-white/5 text-white focus:bg-white/10 focus:ring-primary-500 rounded-2xl transition-all" :value="old('nagad_number', $user->nagad_number)" placeholder="01XXXXXXXXX" />
                    </div>
                    <x-input-error class="mt-2" :messages="$errors->get('nagad_number')" />
                </div>
            </div>
        </div>

        <!-- Section: Social -->
        <div class="pt-4 pb-2 border-t border-white/5">
            <h4 class="text-[10px] font-black text-primary-500 uppercase tracking-[0.2em] mb-6">Social Accounts (সামাজিক যোগাযোগ)</h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <x-input-label for="facebook_link" :value="__('Facebook Profile Link')" class="text-slate-500 uppercase text-[10px] font-black tracking-widest ml-1" />
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-500 group-focus-within:text-blue-500 transition-colors">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" />
                            </svg>
                        </div>
                        <x-text-input id="facebook_link" name="facebook_link" type="text" class="block w-full pl-11 bg-white/5 border-white/5 text-white focus:bg-white/10 focus:ring-primary-500 rounded-2xl transition-all" :value="old('facebook_link', $user->facebook_link)" placeholder="https://facebook.com/username" />
                    </div>
                    <x-input-error class="mt-2" :messages="$errors->get('facebook_link')" />
                </div>

                <div class="space-y-2">
                    <x-input-label for="telegram_username" :value="__('Telegram Username')" class="text-slate-500 uppercase text-[10px] font-black tracking-widest ml-1" />
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-500 group-focus-within:text-sky-500 transition-colors">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M11.944 0C5.346 0 0 5.346 0 11.944c0 6.598 5.346 11.944 11.944 11.944 6.598 0 11.944-5.346 11.944-11.944C23.888 5.346 18.542 0 11.944 0zm5.206 8.134l-1.636 7.708c-.124.54-.442.673-.891.423l-2.487-1.833-1.2 1.154c-.132.132-.245.245-.502.245l.178-2.536 4.616-4.17c.199-.176-.044-.275-.308-.098l-5.705 3.593-2.457-.768c-.534-.167-.546-.534.111-.79l9.605-3.7c.445-.164.834.102.686.837z" />
                            </svg>
                        </div>
                        <x-text-input id="telegram_username" name="telegram_username" type="text" class="block w-full pl-11 bg-white/5 border-white/5 text-white focus:bg-white/10 focus:ring-primary-500 rounded-2xl transition-all" :value="old('telegram_username', $user->telegram_username)" placeholder="@username" />
                    </div>
                    <x-input-error class="mt-2" :messages="$errors->get('telegram_username')" />
                </div>
            </div>
        </div>

        <div class="flex items-center justify-between pt-6 border-t border-white/5">
            <button type="submit" class="px-12 py-5 bg-white text-dark-card rounded-[24px] text-[10px] font-black uppercase tracking-widest hover:bg-primary-500 hover:text-white transition-all shadow-xl shadow-black/20">
                {{ __('Update Profile') }}
            </button>

            @if (session('status') === 'profile-updated')
            <div x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 4000)" class="flex items-center gap-2 text-emerald-500">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                <span class="text-[10px] font-black uppercase tracking-widest">{{ __('Saved successfully') }}</span>
            </div>
            @endif
        </div>
    </form>
</section>
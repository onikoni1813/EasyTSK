<section>
    <header>
        <h2 class="text-lg font-black text-white uppercase tracking-tight">
            {{ __('Update Password') }}
        </h2>

        <p class="mt-1 text-sm text-slate-400 font-medium">
            {{ __('Ensure your account is using a long, random password to stay secure.') }}
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('put')

        <div>
            <x-input-label for="update_password_current_password" :value="__('Current Password')" class="text-slate-500 uppercase text-[10px] font-black tracking-widest mb-2" />
            <x-text-input id="update_password_current_password" name="current_password" type="password" class="mt-1 block w-full bg-white/5 border-white/5 text-white focus:bg-white/10 focus:ring-primary-500 rounded-2xl" autocomplete="current-password" />
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="update_password_password" :value="__('New Password')" class="text-slate-500 uppercase text-[10px] font-black tracking-widest mb-2" />
            <x-text-input id="update_password_password" name="password" type="password" class="mt-1 block w-full bg-white/5 border-white/5 text-white focus:bg-white/10 focus:ring-primary-500 rounded-2xl" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="update_password_password_confirmation" :value="__('Confirm Password')" class="text-slate-500 uppercase text-[10px] font-black tracking-widest mb-2" />
            <x-text-input id="update_password_password_confirmation" name="password_confirmation" type="password" class="mt-1 block w-full bg-white/5 border-white/5 text-white focus:bg-white/10 focus:ring-primary-500 rounded-2xl" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center gap-4">
            <button type="submit" class="px-8 py-4 bg-white text-dark-card rounded-2xl text-[10px] font-black uppercase tracking-widest hover:bg-primary-500 hover:text-white transition-all shadow-xl shadow-black/20">
                {{ __('Save') }}
            </button>

            @if (session('status') === 'password-updated')
            <p
                x-data="{ show: true }"
                x-show="show"
                x-transition
                x-init="setTimeout(() => show = false, 2000)"
                class="text-sm text-slate-500 font-bold uppercase tracking-widest">{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>
<section class="space-y-6">
    <header>
        <h2 class="text-lg font-black text-rose-500 uppercase tracking-tight">
            {{ __('Delete Account') }}
        </h2>

        <p class="mt-1 text-sm text-slate-400 font-medium leading-relaxed">
            {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.') }}
        </p>
    </header>

    <button
        class="px-8 py-4 bg-rose-500 text-white rounded-2xl text-[10px] font-black uppercase tracking-widest hover:bg-rose-600 transition-all shadow-xl shadow-rose-900/40"
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')">{{ __('Delete Account') }}</button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-10 bg-dark-card border border-white/5 rounded-[40px]">
            @csrf
            @method('delete')

            <h2 class="text-xl font-black text-white uppercase tracking-tight">
                {{ __('Are you sure you want to delete your account?') }}
            </h2>

            <p class="mt-4 text-sm text-slate-400 font-medium leading-relaxed">
                {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.') }}
            </p>

            <div class="mt-8">
                <x-input-label for="password" value="{{ __('Password') }}" class="sr-only" />

                <x-text-input
                    id="password"
                    name="password"
                    type="password"
                    class="block w-3/4 bg-white/5 border-white/5 text-white focus:bg-white/10 focus:ring-rose-500 rounded-2xl"
                    placeholder="{{ __('Password') }}" />

                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
            </div>

            <div class="mt-10 flex justify-end gap-4">
                <button type="button" x-on:click="$dispatch('close')" class="px-8 py-4 bg-white/5 text-slate-400 border border-white/5 rounded-2xl text-[10px] font-black uppercase tracking-widest hover:bg-white/10 transition-all">
                    {{ __('Cancel') }}
                </button>

                <button type="submit" class="px-10 py-4 bg-rose-500 text-white rounded-2xl text-[10px] font-black uppercase tracking-widest hover:bg-rose-600 transition-all shadow-xl shadow-rose-900/40">
                    {{ __('Delete Account') }}
                </button>
            </div>
        </form>
    </x-modal>
</section>
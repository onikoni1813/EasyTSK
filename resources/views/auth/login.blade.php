<x-guest-layout>
    <div class="mb-8">
        <h2 class="text-2xl font-black text-white mb-1">লগইন করুন</h2>
        <p class="text-slate-400 text-sm">আপনার কাজ শুরু করতে একাউন্টে প্রবেশ করুন।</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-6">
        @csrf

        <!-- Email or Mobile -->
        <div>
            <label for="login" class="block mb-2 text-xs font-bold text-slate-300 uppercase tracking-wider">ইমেইল অথবা মোবাইল নম্বর</label>
            <input id="login" type="text" name="login" :value="old('login')" required autofocus autocomplete="username"
                class="block w-full px-4 py-3 bg-slate-800/50 border border-slate-700 text-white text-sm rounded-xl focus:ring-green-500 focus:border-green-500 transition-all placeholder:text-slate-500"
                placeholder="ইমেইল অথবা মোবাইল দিন" />
            <x-input-error :messages="$errors->get('login')" class="mt-2" />
        </div>

        <!-- Password -->
        <div>
            <div class="flex items-center justify-between mb-2">
                <label for="password" class="block text-xs font-bold text-slate-300 uppercase tracking-wider">পাসওয়ার্ড</label>
                @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="text-[10px] font-bold text-green-500 hover:text-green-400 uppercase">পাসওয়ার্ড ভুলে গেছেন?</a>
                @endif
            </div>
            <input id="password" type="password" name="password" required autocomplete="current-password"
                class="block w-full px-4 py-3 bg-slate-800/50 border border-slate-700 text-white text-sm rounded-xl focus:ring-green-500 focus:border-green-500 transition-all placeholder:text-slate-500"
                placeholder="••••••••" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="flex items-center">
            <input id="remember_me" type="checkbox" name="remember" class="w-4 h-4 text-green-600 bg-slate-800 border-slate-700 rounded focus:ring-green-500 focus:ring-offset-slate-900">
            <label for="remember_me" class="ml-2 text-sm text-slate-400 font-medium">আমাকে মনে রাখুন</label>
        </div>

        <div>
            <button type="submit" class="w-full py-4 btn-green rounded-xl text-sm font-black uppercase transition-all">
                প্রবেশ করুন
            </button>
        </div>
    </form>

    <div class="mt-8 pt-8 border-t border-slate-700/50 text-center">
        <p class="text-sm text-slate-400 font-medium">একাউন্ট নেই? <a href="{{ route('register') }}" class="text-green-500 font-black hover:underline">নতুন একাউন্ট খুলুন</a></p>
    </div>
</x-guest-layout>
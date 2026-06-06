<x-guest-layout>
    <div class="mb-8">
        <h2 class="text-2xl font-black text-white mb-1">নতুন একাউন্ট খুলুন</h2>
        <p class="text-slate-400 text-sm">বিনামূল্যে জয়েন করুন এবং আজই আয় শুরু করুন।</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-5">
        @csrf

        <!-- Full Name -->
        <div>
            <label for="full_name" class="block mb-2 text-xs font-bold text-slate-300 uppercase tracking-wider">আপনার
                পুরো নাম</label>
            <input id="full_name" type="text" name="full_name" value="{{ old('full_name') }}" required autofocus
                autocomplete="name"
                class="block w-full px-4 py-3 bg-slate-800/50 border border-slate-700 text-white text-sm rounded-xl focus:ring-green-500 focus:border-green-500 transition-all placeholder:text-slate-500"
                placeholder="যেমন: মোঃ আব্দুল করিম" />
            <x-input-error :messages="$errors->get('full_name')" class="mt-2" />
        </div>

        <!-- Mobile Number -->
        <div>
            <label for="mobile_number"
                class="block mb-2 text-xs font-bold text-slate-300 uppercase tracking-wider">মোবাইল নম্বর</label>
            <input id="mobile_number" type="text" name="mobile_number" value="{{ old('mobile_number') }}" required
                class="block w-full px-4 py-3 bg-slate-800/50 border border-slate-700 text-white text-sm rounded-xl focus:ring-green-500 focus:border-green-500 transition-all placeholder:text-slate-500"
                placeholder="017XXXXXXXX" />
            <x-input-error :messages="$errors->get('mobile_number')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div>
            <label for="email" class="block mb-2 text-xs font-bold text-slate-300 uppercase tracking-wider">ইমেইল এড্রেস
                (Optional)</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" autocomplete="username"
                oninput="this.value = this.value.toLowerCase()"
                class="block w-full px-4 py-3 bg-slate-800/50 border border-slate-700 text-white text-sm rounded-xl focus:ring-green-500 focus:border-green-500 transition-all placeholder:text-slate-500"
                placeholder="example@mail.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label for="password"
                    class="block mb-2 text-xs font-bold text-slate-300 uppercase tracking-wider">পাসওয়ার্ড</label>
                <input id="password" type="password" name="password" required autocomplete="new-password"
                    class="block w-full px-4 py-3 bg-slate-800/50 border border-slate-700 text-white text-sm rounded-xl focus:ring-green-500 focus:border-green-500 transition-all placeholder:text-slate-500"
                    placeholder="••••••••" />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>
            <div>
                <label for="password_confirmation"
                    class="block mb-2 text-xs font-bold text-slate-300 uppercase tracking-wider">কনফার্ম
                    পাসওয়ার্ড</label>
                <input id="password_confirmation" type="password" name="password_confirmation" required
                    autocomplete="new-password"
                    class="block w-full px-4 py-3 bg-slate-800/50 border border-slate-700 text-white text-sm rounded-xl focus:ring-green-500 focus:border-green-500 transition-all placeholder:text-slate-500"
                    placeholder="••••••••" />
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
            </div>
        </div>

        <!-- Referral Code (Optional) -->
        <div>
            <label for="referred_by"
                class="block mb-2 text-xs font-bold text-slate-300 uppercase tracking-wider">রেফারেল কোড
                (ঐচ্ছিক)</label>
            <input id="referred_by" type="text" name="referred_by" value="{{ old('referred_by', request('ref') ?? session('ref') ?? request()->cookie('ref') ?? '') }}"
                class="block w-full px-4 py-3 bg-slate-800/50 border border-slate-700 text-white text-sm rounded-xl focus:ring-green-500 focus:border-green-500 transition-all placeholder:text-slate-500 font-bold tracking-widest uppercase"
                placeholder="REF12345" />
            <x-input-error :messages="$errors->get('referred_by')" class="mt-2" />
        </div>

        <!-- Hidden Fingerprint Input -->
        <input type="hidden" name="device_fingerprint" id="device_fingerprint" value="">
        <x-input-error :messages="$errors->get('device_fingerprint')"
            class="mt-2 text-center text-rose-500 font-bold" />

        <!-- Honeypot: Anti-bot trap — hidden from humans, filled by bots -->
        <div style="position: absolute; left: -9999px;" aria-hidden="true">
            <label for="website_url">Website URL</label>
            <input type="text" name="website_url" id="website_url" value="" tabindex="-1" autocomplete="off" />
        </div>

        <div class="pt-2">
            <button type="submit" class="w-full py-4 btn-green rounded-xl text-sm font-black uppercase transition-all">
                একাউন্ট খুলুন
            </button>
        </div>

        <p class="text-[10px] text-slate-500 text-center font-medium leading-relaxed">
            একাউন্ট খোলার মাধ্যমে আপনি আমাদের <a href="{{ route('terms') }}"
                class="underline hover:text-green-500">শর্তাবলী</a> এবং <a href="{{ route('privacy') }}"
                class="underline hover:text-green-500">গোপনীয়তা নীতির</a> সাথে একমত পোষণ করছেন।
        </p>
    </form>

    <div class="mt-8 pt-6 border-t border-slate-700/50 text-center">
        <p class="text-sm text-slate-400 font-medium">ইতিমধ্যেই একাউন্ট আছে? <a href="{{ route('login') }}"
                class="text-green-500 font-black hover:underline">লগইন করুন</a></p>
    </div>

    <script type="module">
        import fpPromise from 'https://openfpcdn.io/fingerprintjs/v4';
        fpPromise.load()
            .then(fp => fp.get())
            .then(result => {
                const visitorId = result.visitorId;
                document.getElementById('device_fingerprint').value = visitorId;
            });
    </script>
</x-guest-layout>
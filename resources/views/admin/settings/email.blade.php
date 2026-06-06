<x-admin-layout>
    <!-- Header Section -->
    <div class="mb-10">
        <h1 class="text-3xl font-black text-white tracking-tighter uppercase italic">Email <span
                class="text-primary-500">Protocol</span></h1>
        <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] mt-2">Configure SMTP communication &
            outbound signals</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Configuration -->
        <div class="lg:col-span-2">
            <div class="glass-card p-10 relative overflow-hidden group">
                <div class="absolute top-0 right-0 p-10 opacity-5 group-hover:scale-110 transition-transform">
                    <svg class="w-32 h-32 text-indigo-500" fill="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z" />
                    </svg>
                </div>

                <form action="{{ route('admin.settings.email.update') }}" method="POST" class="relative">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-10">
                        <div class="space-y-6">
                            <div>
                                <label
                                    class="block mb-3 text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1">SMTP
                                    Host</label>
                                <input type="text" name="mail_host" value="{{ $settings['mail_host'] }}"
                                    placeholder="smtp.mailtrap.io"
                                    class="w-full bg-white/5 border border-white/10 rounded-[20px] p-4 text-white font-bold text-sm focus:bg-indigo-500/10 focus:border-indigo-500 transition-all outline-none">
                            </div>
                            <div>
                                <label
                                    class="block mb-3 text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1">SMTP
                                    Port</label>
                                <input type="number" name="mail_port" value="{{ $settings['mail_port'] }}"
                                    placeholder="587"
                                    class="w-full bg-white/5 border border-white/10 rounded-[20px] p-4 text-white font-bold text-sm focus:bg-indigo-500/10 focus:border-indigo-500 transition-all outline-none">
                            </div>
                            <div>
                                <label
                                    class="block mb-3 text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1">Encryption</label>
                                <select name="mail_encryption"
                                    class="w-full bg-[#1e2433] border border-white/10 rounded-[20px] p-4 text-white font-bold text-sm focus:bg-indigo-500/10 focus:border-indigo-500 transition-all outline-none appearance-none">
                                    <option value="tls" {{ $settings['mail_encryption'] == 'tls' ? 'selected' : '' }} class="bg-[#1e2433] text-white">TLS</option>
                                    <option value="ssl" {{ $settings['mail_encryption'] == 'ssl' ? 'selected' : '' }} class="bg-[#1e2433] text-white">SSL</option>
                                    <option value="null" {{ $settings['mail_encryption'] == 'null' ? 'selected' : '' }} class="bg-[#1e2433] text-white">None</option>
                                </select>
                            </div>
                        </div>

                        <div class="space-y-6">
                            <div>
                                <label
                                    class="block mb-3 text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1">Username</label>
                                <input type="text" name="mail_username" value="{{ $settings['mail_username'] }}"
                                    placeholder="API Key / User"
                                    class="w-full bg-white/5 border border-white/10 rounded-[20px] p-4 text-white font-bold text-sm focus:bg-indigo-500/10 focus:border-indigo-500 transition-all outline-none">
                            </div>
                            <div>
                                <label
                                    class="block mb-3 text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1">Password</label>
                                <input type="password" name="mail_password"
                                    value="{{ $settings['mail_password'] ?? '' }}"
                                    placeholder="SMTP Password / API Secret"
                                    class="w-full bg-white/5 border border-white/10 rounded-[20px] p-4 text-white font-bold text-sm focus:bg-indigo-500/10 focus:border-indigo-500 transition-all outline-none">
                            </div>
                            <div>
                                <label
                                    class="block mb-3 text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1">From
                                    Address</label>
                                <input type="email" name="mail_from_address"
                                    value="{{ $settings['mail_from_address'] }}" placeholder="noreply@domain.com"
                                    class="w-full bg-white/5 border border-white/10 rounded-[20px] p-4 text-white font-bold text-sm focus:bg-indigo-500/10 focus:border-indigo-500 transition-all outline-none">
                            </div>
                        </div>
                    </div>

                    <button type="submit"
                        class="premium-btn w-full py-6 rounded-[32px] text-sm uppercase tracking-widest font-black">
                        Commit SMTP Protocol
                    </button>
                </form>
            </div>
        </div>

        <!-- Connection Test -->
        <div class="lg:col-span-1 space-y-8">
            <div class="glass-card p-10 group bg-emerald-500/5 border-emerald-500/10">
                <div class="flex items-center gap-3 mb-8">
                    <div
                        class="w-10 h-10 bg-emerald-500/10 text-emerald-500 rounded-xl flex items-center justify-center border border-emerald-500/20">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h3 class="text-[11px] font-black text-white uppercase tracking-[0.2em]">Signal Test</h3>
                </div>

                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-8 leading-relaxed italic">
                    Verify SMTP connectivity by sending a test transmission to any authorized address.
                </p>

                <form action="{{ route('admin.settings.email.test') }}" method="POST" class="space-y-6">
                    @csrf
                    <div>
                        <input type="email" name="test_email" required placeholder="Target Email Address"
                            class="w-full bg-white/5 border border-white/10 rounded-[20px] p-4 text-white font-bold text-sm focus:bg-emerald-500/10 transition-all outline-none">
                    </div>
                    <button type="submit"
                        class="w-full py-4 bg-emerald-600 text-white text-[10px] font-black rounded-[20px] uppercase tracking-widest hover:bg-emerald-500 transition-all shadow-lg active:scale-95">
                        Transmit Test Signal
                    </button>
                </form>
            </div>

            <div class="glass-card p-10 bg-indigo-500/5 border-indigo-500/10">
                <h4
                    class="text-[10px] font-black uppercase text-indigo-400 tracking-widest mb-6 flex items-center gap-2">
                    System Intelligence
                    <span class="w-1.5 h-1.5 bg-indigo-500/30 rounded-full"></span>
                </h4>
                <div class="space-y-4 text-[11px] font-black uppercase tracking-wider text-slate-400 leading-relaxed">
                    <p>Dynamic configuration overrides Laravel default <code class="text-indigo-400">mail.php</code>
                        settings at runtime.</p>
                    <p class="pt-4 border-t border-white/5">Settings are cached automatically by the application kernel.
                    </p>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
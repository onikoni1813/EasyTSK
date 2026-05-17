<x-user-layout>
    <div class="max-w-2xl mx-auto py-6 px-4 sm:py-12 sm:px-6">
        <div class="p-6 sm:p-10 bg-dark-card border border-white/5 rounded-[32px] sm:rounded-[48px] shadow-2xl text-center relative overflow-hidden">
            <div class="absolute -top-10 -left-10 w-40 h-40 bg-primary-500/10 rounded-full blur-3xl opacity-50"></div>
            <div class="relative">
                <span class="px-3 py-1 bg-primary-500/10 text-primary-500 rounded-full text-[9px] sm:text-[10px] font-black uppercase tracking-widest mb-4 sm:mb-6 inline-block border border-primary-500/20">Ads View</span>
                <h1 class="text-2xl sm:text-3xl font-black text-white mb-3 sm:mb-4 leading-tight">বিজ্ঞাপন দেখে <span class="text-primary-500">আয় করুন</span></h1>
                <p class="text-xs sm:text-sm text-slate-400 mb-8 sm:mb-10 font-medium">নিচের বাটনে ক্লিক করে অ্যাডটি ওপেন করুন এবং কোড পেতে নির্দিষ্ট সময় অপেক্ষা করুন।</p>

                <div id="countdown-wrap" class="mb-8 sm:mb-12 p-8 sm:p-12 bg-white/5 rounded-[32px] sm:rounded-[40px] border border-white/5 shadow-inner group transition-all duration-500">
                    <div id="timer" class="text-5xl sm:text-7xl font-black text-primary-500 mb-2 sm:mb-3 tracking-tighter tabular-nums drop-shadow-sm group-hover:scale-110 transition-transform">00</div>
                    <div class="text-[9px] sm:text-[10px] font-black uppercase text-slate-500 tracking-[0.2em]">সেকেন্ড অপেক্ষা করুন</div>
                </div>

                <div id="action-area" class="space-y-4 sm:space-y-6">
                    <button id="start-btn" onclick="startTask()" class="flex items-center justify-center w-full px-6 sm:px-10 py-5 sm:py-6 text-xs sm:text-sm font-black text-white bg-primary-600 rounded-[24px] sm:rounded-[32px] hover:bg-slate-900 transition-all shadow-xl shadow-primary-200 group">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-3 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        🚀 অ্যাড ওপেন করুন
                    </button>

                    <div id="code-area" class="hidden animate-in fade-in slide-in-from-bottom-4 duration-700">
                        <div class="p-6 sm:p-8 bg-emerald-500/10 border border-emerald-500/20 rounded-[24px] sm:rounded-[32px] mb-6 sm:mb-8 relative overflow-hidden">
                            <div class="absolute top-0 right-0 p-4 opacity-5 text-emerald-500">
                                <svg class="w-16 h-16 sm:w-20 sm:h-20" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z" />
                                </svg>
                            </div>
                            <span class="text-[9px] sm:text-[10px] font-black text-emerald-500 uppercase tracking-widest block mb-1">আপনার ইউনিক কোড</span>
                            <div class="flex flex-col sm:flex-row items-center justify-center gap-3 sm:gap-4">
                                <span id="the-code" class="text-xl sm:text-3xl font-black text-white tracking-[0.2em] sm:tracking-[0.3em] select-all drop-shadow-sm break-all">-------</span>
                                <button onclick="copyCode()" class="w-10 h-10 bg-white/10 rounded-xl flex items-center justify-center text-white hover:bg-emerald-500 transition-all shadow-lg active:scale-95 group/copy">
                                    <svg class="w-5 h-5 group-hover/copy:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                        <form id="verify-form" onsubmit="verifyCode(event)" class="space-y-4 sm:space-y-6">
                            @csrf
                            <input type="hidden" name="task_id" value="{{ $task->id }}">
                            <div class="relative">
                                <input type="text" name="code" id="code-input" placeholder="কোডটি এখানে দিন" required
                                    class="block w-full px-6 sm:px-8 py-5 sm:py-6 text-xs sm:text-sm text-white bg-white/5 rounded-[24px] sm:rounded-[32px] border border-white/5 focus:bg-white/10 focus:ring-primary-500 focus:border-primary-500 text-center font-black tracking-widest transition-all">
                            </div>
                            <button type="submit" class="w-full py-5 sm:py-6 text-xs sm:text-sm font-black text-white bg-emerald-600 rounded-[24px] sm:rounded-[32px] hover:bg-emerald-500 transition-all shadow-xl shadow-emerald-900/40">
                                পয়েন্ট বুঝে নিন (CLAIM POINTS)
                            </button>
                        </form>
                    </div>
                </div>

                <div class="mt-12 p-8 bg-white/5 rounded-[32px] border border-white/5 text-left relative overflow-hidden">
                    <div class="absolute top-2 right-2 p-4 opacity-10 text-amber-500">
                        <svg class="w-12 h-12" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <h4 class="text-[10px] font-black text-amber-500 uppercase tracking-widest mb-4">গুরুত্বপূর্ণ নির্দেশনা:</h4>
                    <ul class="text-xs text-slate-400 space-y-3 font-medium">
                        <li class="flex items-start gap-3">
                            <span class="w-1.5 h-1.5 bg-amber-500 rounded-full mt-1.5"></span>
                            অ্যাডটি নতুন ট্যাবে ওপেন হবে, সেটি লোড হতে দিন এবং বন্ধ করবেন না।
                        </li>
                        <li class="flex items-start gap-3">
                            <span class="w-1.5 h-1.5 bg-amber-500 rounded-full mt-1.5"></span>
                            অ্যাড পেজটি লোড হওয়ার পর কমপক্ষে ৩০ সেকেন্ড ভিজিট করুন।
                        </li>
                        <li class="flex items-start gap-3">
                            <span class="w-1.5 h-1.5 bg-amber-500 rounded-full mt-1.5"></span>
                            অটোমেটিক কোড জেনারেট হলে সেটি কপি করে এখানে সাবমিট করুন।
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        let timeLeft = Number(atob('{{ base64_encode($timerSeconds ?? 30) }}'));
        let timerId = null;
        let adOpened = false;
        const adLink = atob('{{ base64_encode($adsterraLink) }}');

        function startTask() {
            if (adOpened) return;

            window.open(adLink, '_blank');
            adOpened = true;

            const btn = document.getElementById('start-btn');
            btn.disabled = true;
            btn.classList.add('opacity-50', 'cursor-not-allowed');
            btn.innerHTML = 'অ্যাড খোলা হয়েছে, দয়া করে অপেক্ষা করুন...';

            timerId = setInterval(() => {
                timeLeft--;
                document.getElementById('timer').innerText = timeLeft < 10 ? '0' + timeLeft : timeLeft;

                if (timeLeft <= 0) {
                    clearInterval(timerId);
                    generateCode();
                }
            }, 1000);
        }

        function copyCode() {
            const code = document.getElementById('the-code').innerText;
            navigator.clipboard.writeText(code).then(() => {
                alert('✅ কোডটি কপি করা হয়েছে!');
            });
        }

        function generateCode() {
            fetch("{{ route('adsterra.generate_code', $task) }}", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                })
                .then(r => r.json())
                .then(data => {
                    document.getElementById('start-btn').classList.add('hidden');
                    document.getElementById('code-area').classList.remove('hidden');
                    document.getElementById('the-code').innerText = data.code;
                    document.getElementById('timer').innerText = '✅';
                    document.getElementById('timer').classList.replace('text-primary-600', 'text-emerald-500');
                    document.getElementById('countdown-wrap').classList.add('border-emerald-200', 'bg-emerald-50/30');
                })
                .catch(err => {
                    alert('Something went wrong. Please reload the page.');
                });
        }

        function verifyCode(e) {
            e.preventDefault();
            const form = document.getElementById('verify-form');
            const btn = form.querySelector('button');
            const originalText = btn.innerText;

            btn.disabled = true;
            btn.innerText = 'ভেরিফাই করা হচ্ছে...';

            const formData = new FormData(form);

            fetch("{{ route('adsterra.verify_code') }}", {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                })
                .then(r => r.json())
                .then(data => {
                    if (data.success) {
                        alert(data.message);
                        window.location.href = "{{ route('adsterra.index') }}";
                    } else {
                        alert(data.message);
                        btn.disabled = false;
                        btn.innerText = 'আবার কোড দিন';
                    }
                })
                .catch(err => {
                    alert('কোডটি ভুল। আবার চেষ্টা করুন।');
                    btn.disabled = false;
                    btn.innerText = originalText;
                });
        }
    </script>
    @endpush
</x-user-layout>
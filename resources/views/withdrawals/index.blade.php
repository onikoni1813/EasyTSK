<x-user-layout>
    <div class="mb-10 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-3xl font-black text-white tracking-tight uppercase">ওয়ালেট ও <span
                    class="text-primary-500">উইথড্র</span></h2>
            <p class="text-sm text-slate-400 font-medium mt-1">আপনার কষ্টার্জিত টাকা সরাসরি বিকাশ বা নগদে লাইভ উইথড্র
                নিন।</p>
        </div>
        <div class="bg-primary-600 px-6 py-3 rounded-2xl shadow-lg shadow-primary-900/40 text-white">
            <span class="text-[10px] font-black uppercase tracking-widest block opacity-70">মেইন ব্যালেন্স</span>
            <span class="text-xl font-black tracking-tighter">৳ {{ number_format($user->points / $rate, 2) }} <span
                    class="text-xs font-bold opacity-70">BDT</span></span>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
        <!-- Withdrawal Form -->
        <div class="p-8 bg-dark-card border border-white/5 rounded-[40px] shadow-sm relative overflow-hidden group">
            <div
                class="absolute top-0 right-0 p-8 opacity-5 group-hover:scale-110 transition-transform duration-700 text-primary-500">
                <svg class="w-24 h-24" fill="currentColor" viewBox="0 0 24 24">
                    <path
                        d="M11.8 10.9c-2.27-.59-3-1.2-3-2.15 0-1.09 1.01-1.85 2.7-1.85 1.78 0 2.44.85 2.5 2.1h2.21c-.07-1.72-1.12-3.3-3.21-3.81V3h-3v2.16c-1.94.42-3.5 1.68-3.5 3.61 0 2.31 1.91 3.46 4.7 4.13 2.5.6 3 1.48 3 2.41 0 .69-.49 1.79-2.7 1.79-2.06 0-2.87-.92-2.98-2.1h-2.2c.12 2.19 1.76 3.42 3.68 3.83V21h3v-2.15c1.95-.37 3.5-1.5 3.5-3.55 0-2.84-2.43-3.81-4.7-4.4z" />
                </svg>
            </div>

            <h3 class="text-lg font-black text-white mb-6 flex items-center gap-2 relative">
                পেমেন্ট রিকোয়েস্ট করুন
                <span class="w-1.5 h-1.5 bg-primary-500 rounded-full"></span>
            </h3>

            @if($hasPending)
                <div class="h-full flex flex-col items-center justify-center p-8 mt-10 text-center">
                    <div class="w-20 h-20 bg-amber-500/10 text-amber-500 rounded-3xl flex items-center justify-center mb-6 shadow-xl shadow-amber-900/20 border border-amber-500/20">
                        <svg class="w-10 h-10 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                    </div>
                    <h4 class="text-2xl font-black text-white mb-2 uppercase tracking-tight italic">Pending <span class="text-amber-500">Lock</span></h4>
                    <div class="h-1 w-12 bg-gradient-to-r from-amber-600 to-amber-400 mx-auto mb-6 rounded-full"></div>
                    <p class="text-[11px] text-slate-400 font-bold leading-relaxed mb-4 uppercase tracking-widest">
                        আপনার একটি রিকোয়েস্ট ইতিমধ্যে প্রসেস হচ্ছে। সেটি সম্পন্ন হওয়ার পর নতুন রিকোয়েস্ট করতে পারবেন।
                    </p>
                    <button type="button" onclick="document.querySelector('[x-data]').__x.$data.tab = 'withdraws'" class="mt-4 px-6 py-3 bg-white/5 hover:bg-white/10 text-white text-[10px] font-black tracking-widest uppercase rounded-2xl transition-all border border-white/5">
                        হিস্টরি দেখুন 
                    </button>
                </div>
            @else
        <form action="{{ route('withdrawals.store') }}" method="POST" class="space-y-6 relative" @submit="isSubmitting = true" x-data="{
                isSubmitting: false,
                amount: null,
                method: '{{ $activeMethods->first()?->name }}',
                methods: {{ $activeMethods->map(fn($m) => [
                    'name'         => $m->name,
                    'label'        => $m->label,
                    'min_amount'   => $m->min_amount,
                    'charge_type'  => $m->charge_type,
                    'charge_value' => $m->charge_value,
                    'charge_label' => $m->charge_label,
                ])->values()->toJson() }},
                balance: {{ $user->points / $rate }},
                get currentMethod() {
                    return this.methods.find(m => m.name === this.method) || this.methods[0];
                },
                get currentMin() { return parseFloat(this.currentMethod?.min_amount ?? 0); },
                get isInsufficient() { return this.amount > this.balance; },
                get isBelowMin() { return this.amount > 0 && this.amount < this.currentMin; },
                get canSubmit() { return this.amount > 0 && !this.isInsufficient && !this.isBelowMin; },
                get chargeLabel() {
                    const m = this.currentMethod;
                    if (!m || m.charge_value <= 0) return 'উইথড্র চার্জ: ফ্রি!';
                    return m.charge_type === 'fixed'
                        ? 'চার্জ (৳ ' + m.charge_value + ' ফ্ল্যাট)'
                        : 'চার্জ (' + m.charge_value + '%)';
                },
                get fee() {
                    if (!this.amount || this.amount <= 0) return 0;
                    const m = this.currentMethod;
                    if (!m || m.charge_value <= 0) return 0;
                    return m.charge_type === 'fixed'
                        ? parseFloat(m.charge_value).toFixed(2)
                        : (this.amount * (m.charge_value / 100)).toFixed(2);
                },
                get receivable() {
                    if (!this.amount || this.amount <= 0) return 0;
                    let res = this.amount - this.fee;
                    return res > 0 ? res.toFixed(2) : 0;
                }
            }">
            @csrf
            <div>
                <label class="block mb-3 text-[10px] font-black uppercase tracking-widest text-slate-500">মেথড সিলেক্ট করুন</label>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                    @foreach($activeMethods as $wm)
                    <label
                        class="relative flex items-center gap-3 p-4 bg-white/5 border-2 border-transparent rounded-[24px] cursor-pointer hover:bg-white/10 hover:border-primary-500 transition-all group/radio"
                        :class="method === '{{ $wm->name }}' ? 'border-primary-500 bg-white/10' : ''">
                        <input type="radio" name="method" value="{{ $wm->name }}" x-model="method" required class="hidden">
                        <div class="w-5 h-5 border-2 border-white/10 rounded-full transition-all flex items-center justify-center"
                            :class="method === '{{ $wm->name }}' ? 'border-primary-500 bg-primary-500' : ''">
                            <span class="w-2 h-2 bg-white rounded-full"></span>
                        </div>
                        <span class="text-lg">{{ $wm->icon_emoji }}</span>
                        <div class="flex-1 min-w-0">
                            <span class="text-xs font-black text-slate-300 group-hover/radio:text-primary-500 block"
                                :class="method === '{{ $wm->name }}' ? 'text-primary-500' : ''">{{ $wm->label }}</span>
                            <span class="text-[8px] font-bold text-slate-600 block">Min: ৳{{ number_format($wm->min_amount, 0) }} | {{ $wm->charge_label }}</span>
                        </div>
                    </label>
                    @endforeach
                </div>
                @if($activeMethods->isEmpty())
                    <p class="text-xs text-rose-400 font-bold mt-4">⚠️ কোনো Withdrawal Method সক্রিয় নেই। Admin-এর সাথে যোগাযোগ করুন।</p>
                @endif
            </div>

                <div>
                    <label for="account"
                        class="block mb-3 text-[10px] font-black uppercase tracking-widest text-slate-500">একাউন্ট
                        নম্বর</label>
                    <input type="text" id="account" name="account"
                        value="{{ auth()->user()->bkash_number ?? auth()->user()->nagad_number }}"
                        class="w-full p-4 bg-white/5 border border-white/5 rounded-[24px] text-sm font-bold text-white focus:bg-white/10 focus:ring-primary-500 focus:border-primary-500 transition-all"
                        required placeholder="017XXXXXXXX">
                </div>

                @php
                    $withdrawalNotice = \App\Models\Setting::get('withdrawal_notice');
                @endphp

                <div class="space-y-6">
                    @if(empty(auth()->user()->facebook_link) || empty(auth()->user()->telegram_username))
                        <div class="p-1 bg-amber-500 rounded-[32px] shadow-xl shadow-amber-900/20">
                            <div class="bg-dark-card rounded-[28px] p-6 text-center">
                                <div
                                    class="inline-flex items-center justify-center w-12 h-12 bg-amber-500/10 rounded-2xl text-amber-500 mb-4">
                                    <svg class="w-6 h-6 animate-pulse" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                                        </path>
                                    </svg>
                                </div>
                                <h4 class="text-sm font-black text-white mb-2 uppercase tracking-tight">⚠️ প্রোফাইল তথ্য
                                    অসম্পূর্ণ</h4>
                                <p class="text-[10px] text-amber-500/80 font-medium mb-5 px-4 leading-relaxed">
                                    উইথড্র করার আগে প্রোফাইলে আপনার আসল ফেসবুক লিংক এবং টেলিগ্রাম ইউজারনেম সেভ করুন। ফেক
                                    আইডি দিলে পেমেন্ট বাতিল হবে।
                                </p>
                                <a href="{{ route('profile.edit') }}"
                                    class="inline-flex items-center px-6 py-3 bg-amber-500 text-dark font-black rounded-xl text-[10px] uppercase tracking-widest transition-all">
                                    প্রোফাইল আপডেট করুন ⚙️
                                </a>
                            </div>
                        </div>
                    @endif

                    @if($withdrawalNotice)
                        <div
                            class="p-5 bg-primary-500/10 border border-primary-500/20 rounded-[24px] flex items-start gap-3">
                            <svg class="w-5 h-5 text-primary-500 shrink-0 mt-0.5" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <p class="text-xs text-primary-400 font-medium leading-relaxed">{{ $withdrawalNotice }}</p>
                        </div>
                    @endif

                    @php
                        $requireKYC = \App\Models\Setting::get('require_kyc_for_withdrawal', false);
                    @endphp
                    @if($requireKYC && auth()->user()->kyc_status !== 'approved')
                        <div class="p-5 bg-rose-500/10 border border-rose-500/20 rounded-[24px] flex items-start gap-3">
                            <svg class="w-5 h-5 text-rose-500 shrink-0 mt-0.5" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                                </path>
                            </svg>
                            <div>
                                <p class="text-xs text-rose-400 font-black uppercase tracking-widest mb-1">KYC ভেরিফিকেশন
                                    প্রয়োজন</p>
                                <p class="text-[10px] text-rose-500 font-medium leading-relaxed mb-3">উইথড্র করার আগে আপনাকে
                                    অবশ্যই আইডি ভেরিফাই করতে হবে।</p>
                                <a href="{{ route('kyc.index') }}"
                                    class="text-[10px] font-black text-rose-500 underline uppercase hover:text-rose-400">ভেরিফাই
                                    করুন →</a>
                            </div>
                        </div>
                    @endif

                    <div>
                        <label for="amount_bdt"
                            class="block mb-3 text-[10px] font-black uppercase tracking-widest text-slate-500">পরিমাণ
                            (BDT)</label>
                        <div class="relative group/input">
                            <input type="number" id="amount_bdt" name="amount_bdt" :min="currentMin" step="1"
                                x-model="amount"
                                class="w-full p-4 bg-white/5 border-2 rounded-[24px] text-sm font-black text-white focus:bg-white/10 transition-all pr-16"
                                :class="isInsufficient || isBelowMin ? 'border-rose-500/50 focus:border-rose-500' : 'border-white/5 focus:border-primary-500'"
                                required placeholder="0.00">
                            <span
                                class="absolute right-6 top-1/2 -translate-y-1/2 text-[10px] font-black text-slate-500 uppercase tracking-widest">BDT</span>
                        </div>

                        <!-- Insufficient Balance Warning -->
                        <div class="mt-3 flex items-center gap-2 text-rose-500 animate-pulse" x-show="isInsufficient"
                            x-transition>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                                </path>
                            </svg>
                            <span class="text-[10px] font-black uppercase tracking-widest">পর্যাপ্ত ব্যালেন্স
                                নেই।</span>
                        </div>

                        <!-- Minimum Limit Warning -->
                        <div class="mt-3 flex items-center gap-2 text-amber-500" x-show="isBelowMin" x-transition>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span class="text-[10px] font-black uppercase tracking-widest">মিনিমাম উইথড্র সীমা: ৳ <span
                                    x-text="currentMin"></span></span>
                        </div>

                        <!-- Fee Calculation UI -->
                        <div class="mt-4 p-5 bg-white/5 rounded-[24px] border border-white/5 space-y-3"
                            x-show="amount > 0 && !isInsufficient && !isBelowMin" x-transition>
                            <div
                                class="flex justify-between items-center text-[10px] font-black uppercase tracking-widest">
                                <span class="text-slate-500" x-text="chargeLabel"></span>
                                <span class="text-rose-400" x-show="fee > 0">- ৳ <span x-text="fee"></span></span>
                            </div>
                            <div class="h-px bg-white/5" x-show="fee > 0"></div>
                            <div class="flex justify-between items-center text-[11px] font-black uppercase tracking-widest"
                                x-show="fee > 0">
                                <span class="text-slate-300">আপনি পাবেন:</span>
                                <span class="text-emerald-400">৳ <span x-text="receivable"></span></span>
                            </div>
                        </div>

                        <div class="mt-3 flex justify-between text-[10px] font-black uppercase tracking-widest">
                            <span class="text-slate-500 italic">ব্যালেন্স: ৳
                                {{ number_format($user->points / $rate, 2) }}</span>
                            <span class="text-primary-500">{{ $rate }} PTS = ৳ ১</span>
                        </div>
                    </div>

                    <button type="submit" :disabled="!canSubmit || isSubmitting"
                        class="flex items-center justify-center w-full py-5 border-0 rounded-[24px] text-sm font-black uppercase tracking-widest transition-all shadow-xl shadow-black/20"
                        :class="(canSubmit && !isSubmitting) ? 'bg-white text-dark-card hover:bg-primary-500 hover:shadow-primary-900/40' : 'bg-white/5 text-slate-500 cursor-not-allowed'">
                        <span x-show="!isSubmitting">কনফার্ম রিকোয়েস্ট</span>
                        <span x-show="isSubmitting">প্রসেস হচ্ছে...</span>
                        <svg class="w-5 h-5 ml-2 group-hover:translate-x-1 transition-transform" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                        </svg>
                    </button>
                </div>
            </form>
            @endif
        </div>
        <div class="p-8 bg-dark-card border border-white/5 rounded-[40px] shadow-sm flex flex-col overflow-hidden"
            x-data="{ tab: 'withdraws' }">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
                <h3 class="text-lg font-black text-white flex items-center gap-2">
                    অ্যাক্টিভিটি হিস্টরি
                    <span class="w-1.5 h-1.5 bg-primary-500 rounded-full animate-pulse"></span>
                </h3>

                <div class="flex bg-white/5 p-1 rounded-2xl border border-white/5">
                    <button @click="tab = 'withdraws'"
                        :class="tab === 'withdraws' ? 'bg-white text-dark-card shadow-lg' : 'text-slate-500 hover:text-slate-300'"
                        class="px-5 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all duration-300">
                        উইথড্র
                    </button>
                    <button @click="tab = 'tasks'"
                        :class="tab === 'tasks' ? 'bg-white text-dark-card shadow-lg' : 'text-slate-500 hover:text-slate-300'"
                        class="px-5 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all duration-300">
                        টাস্ক
                    </button>
                </div>
            </div>

            <!-- Withdraw History Table -->
            <div x-show="tab === 'withdraws'" x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0"
                class="relative overflow-x-auto flex-1">
                <table class="w-full text-left">
                    <thead>
                        <tr
                            class="text-[10px] font-black text-slate-500 uppercase tracking-widest border-b border-white/5">
                            <th class="pb-4 pr-4">তারিখ</th>
                            <th class="pb-4 pr-4">মেথড</th>
                            <th class="pb-4 pr-4">পরিমাণ</th>
                            <th class="pb-4 pr-4">অবস্থা</th>
                            <th class="pb-4">Admin Reply / TrxID</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5">
                        @forelse($withdrawals as $withdraw)
                                            <tr class="group">
                                                <td class="py-5 pr-4">
                                                    <span
                                                        class="text-xs font-black text-white block">{{ $withdraw->created_at->format('d M') }}</span>
                                                    <span
                                                        class="text-[9px] font-bold text-slate-500 block">{{ $withdraw->created_at->format('Y') }}</span>
                                                </td>
                                                <td class="py-5 pr-4 text-xs font-black text-slate-400 uppercase">{{ $withdraw->method }}
                                                </td>
                                                <td class="py-5 pr-4 text-xs font-black text-primary-500">৳
                                                    {{ number_format($withdraw->amount_bdt, 2) }}</td>
                                                <td class="py-5 pr-4">
                                                    <span
                                                        class="px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-widest
                                                        {{ $withdraw->status == 'pending' ? 'bg-amber-500/10 text-amber-500 border border-amber-500/20' :
                            ($withdraw->status == 'approved' ? 'bg-emerald-500/10 text-emerald-500 border border-emerald-500/20' : 'bg-rose-500/10 text-rose-500 border border-rose-500/20') }}">
                                                        {{ $withdraw->status }}
                                                    </span>
                                                </td>
                                                <td class="py-5">
                                                    @if($withdraw->admin_feedback)
                                                        <span
                                                            class="text-[10px] font-bold text-slate-300 break-all">{{ $withdraw->admin_feedback }}</span>
                                                    @else
                                                        <span
                                                            class="text-[9px] font-bold text-slate-600 uppercase italic tracking-tighter">Processing...</span>
                                                    @endif
                                                </td>
                                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-20 text-center text-slate-500 italic text-xs">উইথড্র হিস্টরি পাওয়া
                                    যায়নি</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                @if($withdrawals->hasPages())
                    <div class="mt-8 pt-6 border-t border-white/5">
                        {{ $withdrawals->links() }}
                    </div>
                @endif
            </div>

            <!-- Task History Table -->
            <div x-show="tab === 'tasks'" x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0"
                class="relative overflow-x-auto flex-1">
                <table class="w-full text-left">
                    <thead>
                        <tr
                            class="text-[10px] font-black text-slate-500 uppercase tracking-widest border-b border-white/5">
                            <th class="pb-4 pr-4">টাস্ক</th>
                            <th class="pb-4 pr-4 text-center">পয়েন্ট</th>
                            <th class="pb-4 text-right">অবস্থা</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5">
                        @forelse($submissions as $sub)
                                            <tr class="group">
                                                <td class="py-5 pr-4">
                                                    <span
                                                        class="text-xs font-black text-white block truncate max-w-[150px]">{{ $sub->task->title }}</span>
                                                    <span
                                                        class="text-[9px] font-bold text-slate-500 block">{{ $sub->created_at->diffForHumans() }}</span>
                                                </td>
                                                <td class="py-5 pr-4 text-center">
                                                    <span
                                                        class="text-xs font-black text-primary-500">{{ number_format($sub->task->points) }}</span>
                                                </td>
                                                <td class="py-5 text-right">
                                                    <span
                                                        class="px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-widest
                                                        {{ $sub->status == 'pending' ? 'bg-amber-500/10 text-amber-500 border border-amber-500/20' :
                            ($sub->status == 'approved' ? 'bg-emerald-500/10 text-emerald-500 border border-emerald-500/20' : 'bg-rose-500/10 text-rose-500 border border-rose-500/20') }}">
                                                        {{ $sub->status }}
                                                    </span>
                                                </td>
                                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="py-20 text-center text-slate-500 italic text-xs">টাস্ক হিস্টরি পাওয়া
                                    যায়নি</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="mt-6 p-4 bg-white/5 rounded-2xl border border-white/5 text-center">
                    <p class="text-[9px] font-black text-slate-500 uppercase tracking-widest">টাস্ক অ্যাপ্রুভ হতে ২৪
                        ঘণ্টা সময় লাগতে পারে</p>
                </div>
            </div>
        </div>
    </div>
</x-user-layout>
<x-admin-layout>
    <!-- Header Section -->
    <div class="mb-10 flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div>
            <h1 class="text-3xl font-black text-white tracking-tighter uppercase italic">Agent <span
                    class="text-primary-500">Dossier</span></h1>
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] mt-2 flex items-center gap-2">
                <span class="w-1.5 h-1.5 bg-primary-500 rounded-full animate-pulse"></span>
                Authorized intelligence portfolio: {{ $user->name }}
            </p>
        </div>
        <a href="{{ route('admin.users.index') }}"
            class="px-6 py-2.5 bg-white/5 border border-white/10 rounded-xl text-[10px] font-black text-slate-400 hover:text-white transition-all uppercase tracking-widest">
            Back to Directory
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8" x-data="{ showBanModal: false }">
        <!-- Sidebar: Identity Info -->
        <div class="lg:col-span-1 space-y-8">
            <div class="glass-card p-10 text-center relative overflow-hidden group">
                <div class="absolute inset-x-0 top-0 h-32 bg-gradient-to-b from-primary-500/10 to-transparent"></div>

                <div class="relative">
                    <div
                        class="w-28 h-28 bg-primary-600 rounded-[32px] flex items-center justify-center text-white font-black text-5xl mx-auto mb-6 shadow-2xl shadow-primary-900/40 border border-primary-500/50 transform group-hover:scale-105 transition-transform">
                        {{ substr($user->name, 0, 1) }}
                    </div>
                    <h3 class="text-2xl font-black text-white tracking-tight uppercase italic">{{ $user->name }}</h3>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mt-1">{{ $user->email }}
                    </p>

                    <div class="grid grid-cols-2 gap-4 mt-10">
                        <div class="p-4 bg-white/5 rounded-2xl border border-white/5 text-left">
                            <p class="text-[9px] font-black uppercase text-slate-400 mb-2 tracking-widest">Available</p>
                            <p class="text-xl font-black text-primary-500">{{ number_format($user->points) }} <span
                                    class="text-[10px] text-slate-400">PTS</span></p>
                        </div>
                        <div class="p-4 bg-white/5 rounded-2xl border border-white/5 text-left">
                            <p class="text-[9px] font-black uppercase text-slate-400 mb-2 tracking-widest">Integrity</p>
                            @php
                                $score = $user->trust_score;
                                $scoreColor = 'text-rose-500';
                                if ($score >= 80)
                                    $scoreColor = 'text-emerald-500';
                                elseif ($score >= 50)
                                    $scoreColor = 'text-orange-500';
                            @endphp
                            <p class="text-xl font-black {{ $scoreColor }}">
                                {{ $user->trust_score }}%
                            </p>
                        </div>
                    </div>

                    <div class="mt-8 space-y-3">
                        @if($user->is_banned)
                            <div
                                class="p-4 bg-rose-500/10 text-rose-500 text-[9px] font-black rounded-2xl border border-rose-500/20 uppercase tracking-widest leading-relaxed">
                                Access Terminated: {{ $user->ban_reason }}
                            </div>
                            <form action="{{ route('admin.users.unban', $user) }}" method="POST">
                                @csrf
                                <button type="submit"
                                    class="w-full py-4 bg-emerald-600 text-white text-[10px] font-black rounded-2xl uppercase tracking-widest hover:bg-emerald-500 transition-all shadow-lg">Restore
                                    Access</button>
                            </form>
                        @else
                            <button @click="showBanModal = true"
                                class="w-full py-4 bg-rose-600 text-white text-[10px] font-black rounded-2xl uppercase tracking-widest hover:bg-rose-500 transition-all shadow-lg">Terminate
                                Protocol</button>
                        @endif
                    </div>
                </div>
            </div>

            <div class="glass-card p-8">
                <h4
                    class="text-[10px] font-black uppercase text-slate-400 tracking-widest mb-6 flex items-center gap-2">
                    Personnel Metadata
                    <span class="w-1.5 h-1.5 bg-slate-700 rounded-full"></span>
                </h4>
                <div class="space-y-4 text-[11px] font-black uppercase tracking-wider">
                    <div class="flex justify-between items-center py-2 border-b border-white/5">
                        <span class="text-slate-400">Recruitment</span>
                        <span class="text-white">{{ $user->created_at->format('M d, Y') }}</span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-white/5">
                        <span class="text-slate-400">Network Node</span>
                        <span class="text-white font-mono">{{ $user->registration_ip ?? 'INTERNAL' }}</span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-white/5">
                        <span class="text-slate-400">Operation Zone</span>
                        <span class="text-white">{{ $user->country ?? 'GLOBAL' }}</span>
                    </div>
                    <div class="flex justify-between items-center py-2">
                        <span class="text-slate-400">Settlement Hub</span>
                        <span class="text-emerald-500 font-mono">{{ $user->bkash_number ?? 'NOT_SET' }}</span>
                    </div>
                </div>
            </div>

            <div class="glass-card p-8 bg-indigo-500/5 border-indigo-500/10">
                <h4
                    class="text-[10px] font-black uppercase text-indigo-400 tracking-widest mb-6 flex items-center gap-2">
                    Marketing & Tracking
                    <span class="w-1.5 h-1.5 bg-indigo-500/30 rounded-full"></span>
                </h4>
                <div class="space-y-4 text-[11px] font-black uppercase tracking-wider">
                    <div class="flex justify-between items-center py-2 border-b border-indigo-500/5">
                        <span class="text-slate-400 text-[10px]">Source</span>
                        <span
                            class="{{ $user->utm_source ? 'text-indigo-400' : 'text-slate-500' }}">{{ $user->utm_source ?? 'Organic' }}</span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-indigo-500/5">
                        <span class="text-slate-400 text-[10px]">Campaign</span>
                        <span
                            class="{{ $user->utm_campaign ? 'text-indigo-400' : 'text-slate-500' }}">{{ $user->utm_campaign ?? 'N/A' }}</span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-indigo-500/5">
                        <span class="text-slate-400 text-[10px]">Medium</span>
                        <span
                            class="{{ $user->utm_medium ? 'text-indigo-400' : 'text-slate-500' }}">{{ $user->utm_medium ?? 'N/A' }}</span>
                    </div>
                    <div class="flex justify-between items-center py-2">
                        <span class="text-slate-400 text-[10px]">Referral Used</span>
                        <span class="{{ $user->referrer ? 'text-indigo-400 font-mono' : 'text-slate-500' }}">
                            {{ optional($user->referrer)->referral_code ?? 'None' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-8">
            <!-- Balance Adjuster -->
            <div class="glass-card p-8 relative overflow-hidden group">
                <div class="absolute top-0 right-0 p-8 opacity-5 group-hover:opacity-10 transition-opacity">
                    <svg class="w-24 h-24 text-primary-500" fill="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-13V4m0 16v-2m-2-5a3 3 0 11-6 0 3 3 0 016 0zM16 12a3 3 0 116 0 3 3 0 01-6 0z">
                        </path>
                    </svg>
                </div>
                <h3 class="text-[11px] font-black text-white uppercase tracking-[0.2em] mb-8 flex items-center gap-3">
                    Bounty Compensation
                    <span class="px-2 py-0.5 bg-amber-500/10 text-amber-500 text-[8px] rounded uppercase">Direct
                        Protocol</span>
                </h3>
                <form action="{{ route('admin.users.adjust_balance', $user) }}" method="POST"
                    class="grid grid-cols-1 md:grid-cols-3 gap-6 items-end">
                    @csrf
                    <div>
                        <label
                            class="block mb-3 text-[9px] font-black uppercase text-slate-400 tracking-widest ml-1 leading-none">Yield
                            Adjustment (PTS)</label>
                        <input type="number" name="points" placeholder="e.g. 1000 or -500" required
                            class="w-full bg-white/5 border border-white/5 rounded-2xl p-4 text-white font-black text-sm focus:bg-white/10 focus:ring-primary-500 transition-all placeholder:text-slate-400">
                    </div>
                    <div class="md:col-span-2 flex gap-4 items-end">
                        <div class="flex-grow">
                            <label
                                class="block mb-3 text-[9px] font-black uppercase text-slate-400 tracking-widest ml-1 leading-none">Internal
                                Justification</label>
                            <input type="text" name="reason" placeholder="Specify operational reason..." required
                                class="w-full bg-white/5 border border-white/5 rounded-2xl p-4 text-white font-bold text-sm focus:bg-white/10 focus:ring-primary-500 transition-all placeholder:text-slate-400">
                        </div>
                        <button type="submit"
                            class="premium-btn px-8 py-4 rounded-2xl text-[10px] whitespace-nowrap">Commit
                            Adjustment</button>
                    </div>
                </form>
            </div>

            {{-- Warning Center --}}
            <div class="glass-card p-8">
                <h3 class="text-[11px] font-black text-white uppercase tracking-[0.2em] mb-8">Incident Signal</h3>
                <form action="{{ route('admin.users.warn', $user) }}" method="POST" class="flex gap-4">
                    @csrf
                    <input type="text" name="message" placeholder="Transmit warning override to agent HUD..." required
                        class="w-full bg-white/5 border border-white/5 rounded-2xl p-4 text-white font-bold text-sm focus:bg-white/10 focus:ring-amber-500 transition-all placeholder:text-slate-400">
                    <button type="submit"
                        class="bg-amber-600 hover:bg-amber-500 text-white font-black text-[10px] px-8 py-4 rounded-2xl uppercase tracking-widest transition-all shadow-lg whitespace-nowrap">Deploy
                        Signal</button>
                </form>
            </div>

            {{-- ═══════════════════════════════════════════ --}}
            {{-- Role & Sub-Admin Permission Management      --}}
            {{-- ═══════════════════════════════════════════ --}}
            @if(auth()->user()->is_admin)
            <div class="glass-card p-8 border border-indigo-500/10 bg-indigo-500/5">
                <div class="flex items-center gap-3 mb-8">
                    <div class="w-10 h-10 bg-indigo-500/10 text-indigo-400 rounded-xl flex items-center justify-center border border-indigo-500/20">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-[11px] font-black text-indigo-400 uppercase tracking-[0.2em]">Role & Access Control</h3>
                        <p class="text-[9px] text-slate-500 uppercase tracking-widest mt-0.5">
                            বর্তমান রোল:
                            <span class="font-black
                                {{ $user->is_admin ? 'text-rose-400' : ($user->is_sub_admin ? 'text-indigo-400' : ($user->is_moderator ? 'text-amber-400' : 'text-slate-400')) }}">
                                {{ $user->is_admin ? 'Full Admin' : ($user->is_sub_admin ? 'Sub Admin' : ($user->is_moderator ? 'Moderator' : 'Regular User')) }}
                            </span>
                        </p>
                    </div>
                </div>

                @unless($user->is_admin)
                {{-- Role Assignment --}}
                <form action="{{ route('admin.users.set_role', $user) }}" method="POST" class="mb-8">
                    @csrf
                    <label class="block mb-3 text-[9px] font-black uppercase text-slate-400 tracking-widest ml-1">রোল পরিবর্তন করুন</label>
                    <div class="flex gap-3">
                        <select name="role" class="flex-1 bg-white/5 border border-white/5 rounded-2xl p-4 text-white font-black text-xs focus:bg-white/10 appearance-none cursor-pointer">
                            <option value="user"      {{ !$user->is_moderator && !$user->is_sub_admin ? 'selected' : '' }} class="bg-dark">👤 সাধারণ ইউজার</option>
                            <option value="moderator" {{ $user->is_moderator ? 'selected' : '' }} class="bg-dark">🔰 মডারেটর (টাস্ক রিভিউ)</option>
                            <option value="sub_admin" {{ $user->is_sub_admin ? 'selected' : '' }} class="bg-dark">⚡ সাব-অ্যাডমিন (কাস্টম পারমিশন)</option>
                        </select>
                        <button type="submit" class="px-6 py-4 bg-indigo-600 hover:bg-indigo-500 text-white text-[10px] font-black uppercase tracking-widest rounded-2xl transition-all whitespace-nowrap">
                            আপডেট রোল
                        </button>
                    </div>
                </form>

                {{-- Permission Grid (only for sub-admins) --}}
                @if($user->is_sub_admin)
                <form action="{{ route('admin.users.update_permissions', $user) }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label class="block mb-4 text-[9px] font-black uppercase text-indigo-400 tracking-widest ml-1">
                            পারমিশন কনফিগার করুন (কোন কোন সেকশন অ্যাক্সেস করতে পারবে)
                        </label>
                        @php
                            $allPerms = [
                                'submissions' => ['label' => 'টাস্ক সাবমিশন', 'icon' => '📋', 'desc' => 'Approve/Reject submissions'],
                                'withdrawals' => ['label' => 'উইথড্রয়াল', 'icon' => '💸', 'desc' => 'View & process withdrawals'],
                                'users'       => ['label' => 'ইউজার ম্যানেজ', 'icon' => '👥', 'desc' => 'View & manage users'],
                                'tasks'       => ['label' => 'টাস্ক ম্যানেজ', 'icon' => '🎯', 'desc' => 'Create/Edit/Delete tasks'],
                                'kyc'         => ['label' => 'KYC ভেরিফাই', 'icon' => '🪪', 'desc' => 'Approve/reject KYC'],
                                'support'     => ['label' => 'সাপোর্ট টিকেট', 'icon' => '🎧', 'desc' => 'Handle support tickets'],
                                'settings'    => ['label' => 'সেটিংস', 'icon' => '⚙️', 'desc' => 'App settings access'],
                                'fraud'       => ['label' => 'ফ্রড লগ', 'icon' => '🛡️', 'desc' => 'View fraud logs & ban users'],
                            ];
                            $currentPerms = $user->sub_admin_permissions ?? [];
                        @endphp
                        <div class="grid grid-cols-2 gap-3">
                            @foreach($allPerms as $key => $perm)
                            <label class="flex items-center gap-3 p-4 bg-white/5 rounded-2xl border border-white/5 cursor-pointer hover:border-indigo-500/30 hover:bg-indigo-500/5 transition-all group has-[:checked]:border-indigo-500/40 has-[:checked]:bg-indigo-500/10">
                                <input type="checkbox" name="permissions[]" value="{{ $key }}"
                                    {{ in_array($key, $currentPerms) ? 'checked' : '' }}
                                    class="w-4 h-4 rounded accent-indigo-500">
                                <div>
                                    <p class="text-[10px] font-black text-white uppercase tracking-tight">{{ $perm['icon'] }} {{ $perm['label'] }}</p>
                                    <p class="text-[8px] text-slate-500 uppercase tracking-widest mt-0.5">{{ $perm['desc'] }}</p>
                                </div>
                            </label>
                            @endforeach
                        </div>
                    </div>
                    <button type="submit" class="w-full py-4 bg-indigo-600 hover:bg-indigo-500 text-white text-[10px] font-black uppercase tracking-widest rounded-2xl transition-all shadow-lg">
                        ✅ পারমিশন সেভ করুন
                    </button>
                </form>
                @else
                <div class="p-4 bg-white/5 rounded-2xl border border-white/5 text-center">
                    <p class="text-[9px] font-black text-slate-500 uppercase tracking-widest">
                        সাব-অ্যাডমিন রোল দিলে পারমিশন কনফিগার করার অপশন দেখাবে।
                    </p>
                </div>
                @endif
                @else
                <div class="p-4 bg-rose-500/10 rounded-2xl border border-rose-500/20 text-center">
                    <p class="text-[9px] font-black text-rose-400 uppercase tracking-widest">
                        🛑 Full Admin এর রোল পরিবর্তন করা যাবে না।
                    </p>
                </div>
                @endunless
            </div>
            @endif

            <!-- Operation Log -->
            <div class="glass-card overflow-hidden">
                <div class="px-8 py-5 border-b border-white/5 bg-white/5">
                    <h3 class="text-[11px] font-black text-white uppercase tracking-[0.2em]">Operational Cycle Log</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-white/5 border-b border-white/5">
                                <th class="px-8 py-4 text-[9px] font-black uppercase text-slate-400 tracking-widest">
                                    Execution Time</th>
                                <th class="px-8 py-4 text-[9px] font-black uppercase text-slate-400 tracking-widest">
                                    Protocol Action</th>
                                <th
                                    class="px-8 py-4 text-[9px] font-black uppercase text-slate-400 tracking-widest text-right">
                                    Delta</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5">
                            @forelse($recentTransactions as $tx)
                                <tr class="hover:bg-white/[0.02] transition-colors">
                                    <td class="px-8 py-4 text-slate-400 font-mono text-[10px] uppercase">
                                        {{ $tx->created_at->format('M d | H:i') }}
                                    </td>
                                    <td class="px-8 py-4 text-white font-black text-[11px] uppercase tracking-tight italic">
                                        {{ $tx->description }}
                                    </td>
                                    <td class="px-8 py-4 text-right">
                                        <span
                                            class="font-black text-[11px] {{ $tx->amount_points > 0 ? 'text-emerald-500' : 'text-rose-500' }}">
                                            {{ $tx->amount_points > 0 ? '+' : '' }}{{ number_format($tx->amount_points) }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3"
                                        class="px-8 py-10 text-center text-slate-400 text-[10px] font-black uppercase tracking-widest">
                                        No operation cycles recorded</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Termination Modal -->
            <div x-show="showBanModal" x-cloak
                class="fixed inset-0 z-[100] bg-black/80 backdrop-blur-md flex items-center justify-center p-4">
                <div
                    class="glass-card w-full max-w-md p-10 transform transition-all animate-in fade-in zoom-in duration-200 text-center">
                    <div
                        class="w-16 h-16 bg-rose-500/10 text-rose-500 rounded-3xl flex items-center justify-center mb-6 mx-auto">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-black text-white mb-2 uppercase tracking-tight">Access Termination</h3>
                    <p class="text-[10px] font-black text-slate-400 mb-8 uppercase tracking-widest leading-relaxed">
                        Agent will be immediately blacklisted from the system network.</p>

                    <form action="{{ route('admin.users.ban', $user) }}" method="POST">
                        @csrf
                        <textarea name="reason" rows="3" required placeholder="Mandatory termination justification..."
                            class="w-full bg-white/5 border border-white/5 rounded-2xl p-5 text-white font-bold text-sm focus:bg-white/10 focus:ring-rose-500 transition-all placeholder:text-slate-400 mb-8"></textarea>

                        <div class="grid grid-cols-2 gap-4">
                            <button type="button" @click="showBanModal = false"
                                class="py-4 bg-white/5 text-slate-400 font-black rounded-2xl uppercase tracking-widest hover:bg-white/10 transition-all text-[11px]">Abort</button>
                            <button type="submit"
                                class="py-4 bg-rose-600 text-white font-black rounded-2xl uppercase tracking-widest hover:bg-rose-500 transition-all shadow-lg text-[11px]">Execute
                                Terminate</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
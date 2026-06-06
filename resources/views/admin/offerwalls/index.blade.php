<x-admin-layout>
    <div class="mb-10 flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div>
            <h1 class="text-3xl font-black text-white tracking-tighter uppercase italic">Offerwall <span
                    class="text-primary-500">Networks</span></h1>
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] mt-2 flex items-center gap-2">
                <span class="w-1.5 h-1.5 bg-primary-500 rounded-full animate-pulse"></span>
                Dynamic postback integration center — no code required
            </p>
        </div>
        <a href="{{ route('admin.offerwalls.create') }}"
            class="premium-btn px-6 py-3 rounded-2xl text-xs inline-flex items-center gap-2 w-fit">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Add Network
        </a>
    </div>

    @if (session('success'))
        <div
            class="mb-6 p-4 bg-emerald-500/10 border border-emerald-500/20 rounded-2xl text-emerald-500 text-sm font-bold animate-pulse">
            {{ session('success') }}
        </div>
    @endif

    {{-- Quick Guide --}}
    <div class="glass-card p-6 mb-8 border-primary-500/20">
        <div class="flex items-center gap-3 mb-4">
            <span class="text-xl">📡</span>
            <h4 class="text-[10px] font-black text-primary-400 uppercase tracking-widest">কিভাবে কাজ করে?</h4>
        </div>
        <div class="text-[10px] font-bold text-slate-400 leading-relaxed space-y-1">
            <p>1️⃣ নিচে "Add Network" বাটনে ক্লিক করে নতুন এড নেটওয়ার্ক তৈরি করুন</p>
            <p>2️⃣ Postback URL কপি করে এড নেটওয়ার্কের ড্যাশবোর্ডে পেস্ট করুন</p>
            <p>3️⃣ ইউজার টাস্ক কমপ্লিট করলে, নেটওয়ার্ক অটোমেটিক পোস্টব্যাক পাঠাবে এবং পয়েন্ট ক্রেডিট হবে</p>
        </div>
    </div>

    {{-- Offerwalls Table --}}
    <div class="glass-card overflow-x-auto">
        <table class="w-full text-left border-collapse min-w-[800px]">
            <thead>
                <tr class="bg-white/5 border-b border-white/5">
                    <th class="px-6 py-5 text-[10px] font-black uppercase text-slate-400 tracking-widest">Network</th>
                    <th class="px-6 py-5 text-[10px] font-black uppercase text-slate-400 tracking-widest">Postback URL</th>
                    <th class="px-6 py-5 text-[10px] font-black uppercase text-slate-400 tracking-widest text-center">Security</th>
                    <th class="px-6 py-5 text-[10px] font-black uppercase text-slate-400 tracking-widest text-center">Stats</th>
                    <th class="px-6 py-5 text-[10px] font-black uppercase text-slate-400 tracking-widest text-center">Status</th>
                    <th class="px-6 py-5 text-[10px] font-black uppercase text-slate-400 tracking-widest text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/5">
                @forelse($offerwalls as $ow)
                    <tr class="hover:bg-white/[0.02] transition-colors">
                        {{-- Network Name --}}
                        <td class="px-6 py-5">
                            <div class="flex items-center gap-3">
                                <span class="text-2xl">{{ $ow->icon_emoji }}</span>
                                <div>
                                    <div class="font-black text-white text-sm tracking-tight">{{ $ow->name }}</div>
                                    <div class="text-[9px] font-bold text-slate-500 uppercase tracking-widest mt-0.5">
                                        {{ $ow->display_name }} · {{ $ow->reward_type }} · {{ $ow->platform_share_pct }}% share
                                    </div>
                                </div>
                            </div>
                        </td>

                        {{-- Postback URL --}}
                        <td class="px-6 py-5">
                            <div class="flex items-center gap-2" x-data="{ copied: false }">
                                <code class="text-[9px] font-mono text-primary-400 bg-white/5 px-2 py-1 rounded-lg truncate max-w-[200px]">{{ $ow->getPostbackUrl() }}</code>
                                <button type="button"
                                    @click="navigator.clipboard.writeText('{{ $ow->getPostbackUrl() }}'); copied = true; setTimeout(() => copied = false, 2000)"
                                    class="p-1.5 bg-primary-500/10 rounded-lg border border-primary-500/20 text-primary-500 hover:bg-primary-500 hover:text-dark transition-all text-[9px]"
                                    :class="copied && 'bg-emerald-500 text-white border-emerald-500'">
                                    <span x-show="!copied">📋</span>
                                    <span x-show="copied" x-cloak>✅</span>
                                </button>
                            </div>
                        </td>

                        {{-- Security --}}
                        <td class="px-6 py-5 text-center">
                            @php
                                $secColors = [
                                    'hmac_sha256'  => 'emerald',
                                    'secret_match' => 'amber',
                                    'ip_whitelist' => 'indigo',
                                    'none'         => 'slate',
                                ];
                                $c = $secColors[$ow->security_type] ?? 'slate';
                            @endphp
                            <span class="px-2 py-1 bg-{{ $c }}-500/10 border border-{{ $c }}-500/20 text-[8px] font-black text-{{ $c }}-400 tracking-wider rounded-lg uppercase">
                                {{ str_replace('_', ' ', $ow->security_type) }}
                            </span>
                        </td>

                        {{-- Stats --}}
                        <td class="px-6 py-5 text-center">
                            <div class="flex flex-col items-center gap-1">
                                <span class="text-xs font-black text-emerald-400">{{ number_format($ow->success_count) }}</span>
                                <span class="text-[8px] font-bold text-slate-500 uppercase tracking-widest">success</span>
                                @if($ow->failed_count > 0)
                                    <span class="text-[9px] font-black text-rose-400">{{ $ow->failed_count }} failed</span>
                                @endif
                            </div>
                        </td>

                        {{-- Status Toggle --}}
                        <td class="px-6 py-5 text-center">
                            <form action="{{ route('admin.offerwalls.toggle', $ow) }}" method="POST" class="inline-block">
                                @csrf
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" name="is_active" value="1" {{ $ow->is_active ? 'checked' : '' }}
                                        class="sr-only peer" onchange="this.form.submit()">
                                    <div
                                        class="w-10 h-5 bg-white/10 rounded-full peer peer-checked:bg-primary-500 after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-slate-400 after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:after:translate-x-5">
                                    </div>
                                    <span class="ml-2 text-[9px] font-black uppercase tracking-widest {{ $ow->is_active ? 'text-primary-400' : 'text-slate-500' }}">
                                        {{ $ow->is_active ? 'ON' : 'OFF' }}
                                    </span>
                                </label>
                            </form>
                        </td>

                        {{-- Actions --}}
                        <td class="px-6 py-5 text-right">
                            <div class="flex items-center justify-end gap-2">
                                {{-- Logs --}}
                                <a href="{{ route('admin.offerwalls.logs', $ow) }}"
                                    class="p-2 bg-indigo-500/10 rounded-xl border border-indigo-500/10 text-indigo-500 hover:bg-indigo-500 hover:text-white transition-all"
                                    title="Postback Logs">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                    </svg>
                                </a>
                                {{-- Edit --}}
                                <a href="{{ route('admin.offerwalls.edit', $ow) }}"
                                    class="p-2 bg-amber-500/10 rounded-xl border border-amber-500/10 text-amber-500 hover:bg-amber-500 hover:text-dark transition-all">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </a>
                                {{-- Delete --}}
                                <form action="{{ route('admin.offerwalls.destroy', $ow) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="p-2 bg-rose-500/10 rounded-xl border border-rose-500/10 text-rose-500 hover:bg-rose-500 hover:text-white transition-all"
                                        onclick="return confirm('{{ $ow->name }} ডিলিট করবেন? এই অ্যাকশন undo করা যাবে না।')">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-16 text-center text-slate-500 italic text-xs uppercase tracking-widest">
                            কোনো অফারওয়াল যোগ করা হয়নি। "Add Network" বাটনে ক্লিক করে শুরু করুন।
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-admin-layout>

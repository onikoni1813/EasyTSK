<x-admin-layout>
    <div class="mb-10 flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div>
            <h1 class="text-3xl font-black text-white tracking-tighter uppercase italic">Central <span
                    class="text-primary-500">Blog Posts</span></h1>
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] mt-2 flex items-center gap-2">
                <span class="w-1.5 h-1.5 bg-primary-500 rounded-full animate-pulse"></span>
                API-Driven Stateless Blog Content Hub
            </p>
        </div>
        <div>
            <a href="{{ route('admin.posts.create') }}"
                class="premium-btn py-4 px-6 rounded-2xl text-xs font-black uppercase tracking-wider inline-flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Create Central Post
            </a>
        </div>
    </div>

    @if (session('success'))
        <div class="mb-6 p-4 bg-emerald-500/10 border border-emerald-500/20 rounded-2xl text-emerald-500 text-sm font-bold">
            {{ session('success') }}
        </div>
    @endif

    <div class="glass-card p-6 mb-8 border border-primary-500/10 bg-primary-500/5 rounded-3xl">
        <div class="flex items-start gap-4">
            <span class="text-2xl">💡</span>
            <div>
                <h4 class="text-xs font-black text-white uppercase tracking-wider mb-1">Dynamic Substitutions Guide</h4>
                <p class="text-[11px] font-bold text-slate-400 leading-relaxed uppercase tracking-wide">
                    আপনি আপনার পোস্টে ৩টি আলাদা আলাদা ব্যানার অ্যাড এবং ডাইরেক্ট লিংক ডাইনামিকভাবে ব্যবহার করতে পারবেন।
                    কন্টেন্টের যেকোনো জায়গায় নিচের প্লেসহোল্ডারগুলো ব্যবহার করুন:
                    <br>
                    • <span class="text-emerald-400 font-mono font-black">{ad_code_1}</span> — ১ম ব্যানার কোড (টপ অ্যাড)
                    <br>
                    • <span class="text-emerald-400 font-mono font-black">{ad_code_2}</span> — ২য় ব্যানার কোড (মিডল
                    অ্যাড)
                    <br>
                    • <span class="text-emerald-400 font-mono font-black">{ad_code_3}</span> — ৩য় ব্যানার কোড (বটম
                    অ্যাড)
                    <br>
                    • <span class="text-indigo-400 font-mono font-black">{direct_link}</span> — ডোমেনের নিজস্ব ডাইরেক্ট
                    লিংক
                    <br>
                    • <span class="text-amber-400 font-mono font-black">{user_id}</span> — ইউজার আইডি (API কলের `uid`
                    থেকে)
                    <br>
                    • <span class="text-amber-400 font-mono font-black">{task_id}</span> — টাস্ক আইডি (API কলের `tid`
                    থেকে)
                </p>
            </div>
        </div>
    </div>

    <div class="glass-card overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-white/5 border-b border-white/5">
                    <th class="px-6 py-5 text-[10px] font-black uppercase text-slate-400 tracking-widest">Post Info</th>
                    <th class="px-6 py-5 text-[10px] font-black uppercase text-slate-400 tracking-widest">Active Dynamic
                        Placeholders</th>
                    <th class="px-6 py-5 text-[10px] font-black uppercase text-slate-400 tracking-widest text-center">
                        Connected API URL</th>
                    <th class="px-6 py-5 text-[10px] font-black uppercase text-slate-400 tracking-widest text-right">
                        Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/5">
                @forelse($posts as $post)
                    <tr class="hover:bg-white/[0.02] transition-colors">
                        <td class="px-6 py-5">
                            <div class="font-black text-white text-sm tracking-tight truncate max-w-md">{{ $post->title }}
                            </div>
                            <div class="text-[9px] font-bold text-slate-500 uppercase tracking-widest mt-1">ID:
                                {{ $post->id }} • Created: {{ $post->created_at->format('M d, Y H:i') }}</div>
                        </td>
                        <td class="px-6 py-5">
                            <div class="flex flex-wrap gap-1.5">
                                @if(str_contains($post->content, '{ad_code_1}') || str_contains($post->content, '{ad_code}'))
                                    <span
                                        class="px-2 py-0.5 bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-[9px] font-black uppercase tracking-wider rounded">AD
                                        1</span>
                                @endif
                                @if(str_contains($post->content, '{ad_code_2}'))
                                    <span
                                        class="px-2 py-0.5 bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-[9px] font-black uppercase tracking-wider rounded">AD
                                        2</span>
                                @endif
                                @if(str_contains($post->content, '{ad_code_3}'))
                                    <span
                                        class="px-2 py-0.5 bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-[9px] font-black uppercase tracking-wider rounded">AD
                                        3</span>
                                @endif
                                @if(str_contains($post->content, '{direct_link}'))
                                    <span
                                        class="px-2 py-0.5 bg-indigo-500/10 border border-indigo-500/20 text-indigo-400 text-[9px] font-black uppercase tracking-wider rounded">DIRECT
                                        LINK</span>
                                @endif
                                @if(!str_contains($post->content, '{ad_code_1}') && !str_contains($post->content, '{ad_code}') && !str_contains($post->content, '{ad_code_2}') && !str_contains($post->content, '{ad_code_3}') && !str_contains($post->content, '{direct_link}'))
                                    <span class="text-[9px] font-bold text-slate-500 uppercase tracking-widest">STATIC
                                        CONTENT</span>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-5 text-center font-mono text-[10px] text-slate-400">
                            <span class="bg-dark/50 px-3 py-1.5 rounded-xl border border-white/5 select-all">
                                {{ url('/api/blog-posts/' . $post->id) }}
                            </span>
                        </td>
                        <td class="px-6 py-5 text-right">
                            <div class="flex items-center justify-end gap-3">
                                <a href="{{ route('admin.posts.edit', $post) }}"
                                    class="p-2 bg-amber-500/10 rounded-xl border border-amber-500/10 text-amber-500 hover:bg-amber-500 hover:text-dark transition-all">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                        </path>
                                    </svg>
                                </a>

                                <form action="{{ route('admin.posts.destroy', $post) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="p-2 bg-rose-500/10 rounded-xl border border-rose-500/10 text-rose-500 hover:bg-rose-500 hover:text-white transition-all group/icon"
                                        onclick="return confirm('Delete this central post? Subdomains calling this post will receive 404.')">
                                        <svg class="w-4 h-4 group-hover/icon:scale-110 transition-transform" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                            </path>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4"
                            class="px-6 py-16 text-center text-slate-500 italic text-xs uppercase tracking-widest">No
                            central blog posts created yet</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($posts->hasPages())
        <div class="mt-6">
            {{ $posts->links() }}
        </div>
    @endif
</x-admin-layout>
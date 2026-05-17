<x-admin-layout>
    <div class="mb-10">
        <a href="{{ route('admin.posts.index') }}"
            class="text-[10px] font-black text-primary-500 uppercase tracking-widest hover:text-primary-400 transition-all flex items-center gap-2 mb-4 leading-none">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18">
                </path>
            </svg>
            Back to Posts Hub
        </a>
        <h1 class="text-3xl font-black text-white tracking-tighter uppercase italic">Edit <span
                class="text-primary-500">Central Post</span></h1>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2">
            <div class="glass-card p-8">
                <form action="{{ route('admin.posts.update', $post) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')
                    <div>
                        <label for="title"
                            class="block mb-3 text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 leading-none">Post
                            Title</label>
                        <input type="text" id="title" name="title" placeholder="How to Earn Money Daily with Easy Tasks"
                            value="{{ old('title', $post->title) }}"
                            class="w-full bg-white/5 border border-white/5 rounded-2xl p-4 text-white font-black text-sm focus:bg-white/10 focus:ring-primary-500 transition-all placeholder:text-slate-500 outline-none"
                            required>
                        @error('title') <p class="text-rose-500 text-xs mt-2">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="adsterra_link"
                            class="block mb-3 text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 leading-none">Fallback
                            Adsterra Direct Link <span class="text-amber-400">(Optional)</span></label>
                        <input type="url" id="adsterra_link" name="adsterra_link"
                            placeholder="https://www.highcpmgate.com/..."
                            value="{{ old('adsterra_link', $post->adsterra_link) }}"
                            class="w-full bg-white/5 border border-white/5 rounded-2xl p-4 text-white font-mono text-xs focus:bg-white/10 focus:ring-amber-500 transition-all placeholder:text-slate-500 outline-none">
                        <p class="text-[9px] font-bold text-slate-500 uppercase tracking-wide mt-2 ml-1">Used when no
                            domain-specific direct_link is configured.</p>
                        @error('adsterra_link') <p class="text-rose-500 text-xs mt-2">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <div class="flex items-center justify-between mb-3">
                            <label for="content"
                                class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 leading-none">Post
                                Body Content (HTML Allowed)</label>
                            <span
                                class="text-[9px] font-black text-emerald-400 uppercase tracking-widest italic">Supports
                                HTML & placeholders</span>
                        </div>

                        <!-- Quick Insert Buttons Bar (Extremely Easy Click-to-Insert!) -->
                        <div
                            class="mb-4 bg-white/5 p-3 rounded-2xl border border-white/5 flex flex-wrap gap-2 items-center">
                            <span class="text-[9px] font-black text-slate-400 uppercase tracking-wider mr-2 ml-1">Quick
                                Click-to-Insert:</span>
                            <button type="button" onclick="insertPlaceholder('{ad_code_1}')"
                                class="px-3 py-2 bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-[10px] font-black uppercase tracking-wider rounded-xl hover:bg-emerald-500 hover:text-dark transition-all flex items-center gap-1">
                                ➕ Banner Ad 1 (Top)
                            </button>
                            <button type="button" onclick="insertPlaceholder('{ad_code_2}')"
                                class="px-3 py-2 bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-[10px] font-black uppercase tracking-wider rounded-xl hover:bg-emerald-500 hover:text-dark transition-all flex items-center gap-1">
                                ➕ Banner Ad 2 (Middle)
                            </button>
                            <button type="button" onclick="insertPlaceholder('{ad_code_3}')"
                                class="px-3 py-2 bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-[10px] font-black uppercase tracking-wider rounded-xl hover:bg-emerald-500 hover:text-dark transition-all flex items-center gap-1">
                                ➕ Banner Ad 3 (Bottom)
                            </button>
                            <button type="button" onclick="insertPlaceholder('{direct_link}')"
                                class="px-3 py-2 bg-indigo-500/10 border border-indigo-500/20 text-indigo-400 text-[10px] font-black uppercase tracking-wider rounded-xl hover:bg-indigo-500 hover:text-white transition-all flex items-center gap-1">
                                ➕ Direct Link
                            </button>
                            <button type="button" onclick="insertPlaceholder('{secret_code}')"
                                class="px-3 py-2 bg-rose-500/10 border border-rose-500/20 text-rose-400 text-[10px] font-black uppercase tracking-wider rounded-xl hover:bg-rose-500 hover:text-dark transition-all flex items-center gap-1">
                                🔑 Secret Code
                            </button>
                        </div>

                        <textarea id="content" name="content" rows="18"
                            placeholder="Click any button above to instantly insert ad placements and direct links into your post content at your cursor!"
                            class="w-full bg-white/5 border border-white/5 rounded-2xl p-5 text-white font-bold text-xs focus:bg-white/10 focus:ring-primary-500 transition-all placeholder:text-slate-500 outline-none resize-none"
                            required>{{ old('content', $post->content) }}</textarea>
                        @error('content') <p class="text-rose-500 text-xs mt-2">{{ $message }}</p> @enderror
                    </div>

                    <button type="submit" class="w-full premium-btn py-5 rounded-[32px] text-xs">
                        Save Central Post Changes
                    </button>
                </form>
            </div>
        </div>

        <div class="lg:col-span-1 space-y-8">
            <div class="glass-card p-8">
                <h3 class="text-[11px] font-black text-white uppercase tracking-[0.2em] mb-6">Writing Guide</h3>
                <div class="space-y-4">
                    <div class="p-4 bg-white/5 border border-white/5 rounded-2xl">
                        <span class="font-mono text-emerald-400 font-black text-xs">{ad_code_1}</span>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wide mt-2">
                            এটি যেকোনো জায়গায় বসালে ওই ডোমেনের ১ম ব্যানার অ্যাড লোড হবে।
                        </p>
                    </div>
                    <div class="p-4 bg-white/5 border border-white/5 rounded-2xl">
                        <span class="font-mono text-emerald-400 font-black text-xs">{ad_code_2}</span>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wide mt-2">
                            এটি যেকোনো জায়গায় বসালে ওই ডোমেনের ২য় ব্যানার অ্যাড লোড হবে।
                        </p>
                    </div>
                    <div class="p-4 bg-white/5 border border-white/5 rounded-2xl">
                        <span class="font-mono text-emerald-400 font-black text-xs">{ad_code_3}</span>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wide mt-2">
                            এটি যেকোনো জায়গায় বসালে ওই ডোমেনের ৩য় ব্যানার অ্যাড লোড হবে।
                        </p>
                    </div>
                    <div class="p-4 bg-white/5 border border-white/5 rounded-2xl">
                        <span class="font-mono text-indigo-400 font-black text-xs">{direct_link}</span>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wide mt-2">
                            এটি বাটন বা লিংকের `href` এর মধ্যে বসিয়ে দিলে ডোমেনের ডাইরেক্ট লিংক রুট হবে।
                        </p>
                    </div>
                    <div class="p-4 bg-white/5 border border-white/5 rounded-2xl">
                        <span class="font-mono text-amber-400 font-black text-xs">{user_id}</span>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wide mt-2">
                            ইউজারের আইডি (API কলের সময় `uid` প্যারামিটার থেকে নেওয়া)।
                        </p>
                    </div>
                    <div class="p-4 bg-white/5 border border-white/5 rounded-2xl">
                        <span class="font-mono text-amber-400 font-black text-xs">{task_id}</span>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wide mt-2">
                            টাস্কের আইডি (API কলের সময় `tid` প্যারামিটার থেকে নেওয়া)।
                        </p>
                    </div>
                    <div class="p-4 bg-white/5 border border-white/5 rounded-2xl">
                        <span class="font-mono text-rose-400 font-black text-xs">{secret_code}</span>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wide mt-2">
                            জেনারেটেড সিক্রেট কোড। কন্টেন্টের ভিতরে বসালে ইউজার+টাস্ক অনুযায়ী অটো-জেনারেট হবে।
                        </p>
                    </div>
                </div>
            </div>

            <div class="glass-card p-8">
                <h3 class="text-[11px] font-black text-white uppercase tracking-[0.2em] mb-4">Sample Template</h3>
                <pre
                    class="bg-dark p-4 rounded-2xl text-[9px] font-mono text-slate-400 overflow-x-auto leading-relaxed border border-white/5 select-all">
<div class="top-ad">
  {ad_code_1}
</div>

<p>Post content here...</p>

<div class="mid-ad">
  {ad_code_2}
</div>

<a href="{direct_link}" class="btn">
  CLICK TO UNLOCK TASK
</a>

<div class="bottom-ad">
  {ad_code_3}
</div></pre>
            </div>
        </div>
    </div>

    <script>
        function insertPlaceholder(placeholder) {
            const textarea = document.getElementById('content');
            const startPos = textarea.selectionStart;
            const endPos = textarea.selectionEnd;
            const text = textarea.value;
            textarea.value = text.substring(0, startPos) + placeholder + text.substring(endPos, text.length);
            textarea.focus();
            textarea.selectionStart = textarea.selectionEnd = startPos + placeholder.length;
        }
    </script>
</x-admin-layout>
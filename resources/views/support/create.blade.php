<x-user-layout>
    <div class="mb-10">
        <h2 class="text-3xl font-black text-white tracking-tight leading-tight uppercase">নতুন <span
                class="text-primary-500">টিকেট</span></h2>
        <p class="text-sm text-slate-400 font-medium mt-1">আপনার যেকোনো সমস্যার সমাধান পেতে বিস্তারিত তথ্যসহ টিকেট
            খুলুন।</p>
    </div>

    <div class="p-6 md:p-10 bg-dark-card border border-white/5 rounded-[32px] md:rounded-[48px] shadow-sm max-w-3xl">
        <form action="{{ route('support.store') }}" method="POST" class="space-y-8">
            @csrf
            <div>
                <label for="subject"
                    class="block mb-3 text-[10px] font-black text-slate-500 uppercase tracking-widest">বিষয়
                    (Subject)</label>
                <input type="text" name="subject" id="subject" placeholder="সংক্ষেপে আপনার সমস্যাটি লিখুন"
                    class="block w-full px-6 py-4 bg-white/5 border border-white/5 rounded-2xl font-black text-white focus:bg-white/10 focus:ring-primary-500 focus:border-primary-500 transition-all placeholder:text-slate-600"
                    required>
            </div>

            <div>
                <label for="priority"
                    class="block mb-3 text-[10px] font-black text-slate-500 uppercase tracking-widest">প্রাইোরিটি
                    (Priority)</label>
                <select name="priority" id="priority"
                    class="block w-full px-6 py-4 bg-white/5 border border-white/5 rounded-2xl font-black text-white focus:bg-white/10 focus:ring-primary-500 focus:border-primary-500 transition-all">
                    <option value="low" class="bg-dark-card">Low (সাধারণ)</option>
                    <option value="normal" class="bg-dark-card" selected>Normal (মাঝারি)</option>
                    <option value="high" class="bg-dark-card">High (জরুরি)</option>
                </select>
            </div>

            <div>
                <label for="message"
                    class="block mb-3 text-[10px] font-black text-slate-500 uppercase tracking-widest">বিস্তারিত মেসেজ
                    (Message)</label>
                <textarea name="message" id="message" rows="6" placeholder="আপনার সমস্যাটি বিস্তারিতভাবে এখানে লিখুন..."
                    class="block w-full px-6 py-4 bg-white/5 border border-white/5 rounded-2xl font-black text-white focus:bg-white/10 focus:ring-primary-500 focus:border-primary-500 transition-all placeholder:text-slate-600"
                    required></textarea>
            </div>

            <div class="flex flex-col sm:flex-row items-center gap-4 pt-4">
                <button type="submit"
                    class="flex-1 w-full sm:w-auto px-10 py-5 bg-white text-dark-card rounded-2xl text-[10px] font-black uppercase tracking-widest hover:bg-primary-500 hover:text-white transition-all shadow-xl shadow-black/20">
                    টিকেট খুলুন (Create Ticket)
                </button>
                <a href="{{ route('support.index') }}"
                    class="w-full sm:w-auto px-10 py-5 bg-white/5 text-slate-400 border border-white/5 rounded-2xl text-[10px] font-black uppercase tracking-widest hover:bg-white/10 transition-all text-center">
                    ফিরে যান
                </a>
            </div>
        </form>
    </div>
</x-user-layout>
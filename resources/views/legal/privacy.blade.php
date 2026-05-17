<x-user-layout>
    <div class="max-w-4xl mx-auto py-12">
        <div class="bg-dark-card border border-white/5 rounded-[40px] p-8 md:p-12 shadow-sm">
            <h1 class="text-3xl font-black text-white mb-8 uppercase tracking-tight">
                গোপনীয়তা <span class="text-primary-500">নীতি</span>
            </h1>

            <div class="prose prose-invert max-w-none text-slate-400 font-medium space-y-6">
                <section>
                    <h2 class="text-xl font-bold text-white mb-3">আমরা কি তথ্য সংগ্রহ করি?</h2>
                    <p>আপনার নাম, ইমেইল, মোবাইল নম্বর এবং ডিভাইস আইডেন্টিফায়ার (ফিংগারপ্রিন্ট) সংগ্রহ করা হয় শুধুমাত্র
                        একাউন্টের নিরাপত্তা এবং ডুপ্লিকেট ইউজার প্রতিরোধের জন্য।</p>
                </section>

                <section>
                    <h2 class="text-xl font-bold text-white mb-3">তথ্য ব্যবহারের মাধ্যম</h2>
                    <p>আপনার ব্যক্তিগত তথ্য তৃতীয় পক্ষের কাছে বিক্রি করা হয় না। এটি শুধুমাত্র আমাদের সার্ভিস
                        ইম্প্রুভমেন্ট এবং পেমেন্ট ভেরিফিকেশনের জন্য ব্যবহৃত হয়।</p>
                </section>

                <section>
                    <h2 class="text-xl font-bold text-white mb-3">কুকিজ পলিসি</h2>
                    <p>আপনার লগইন সেশন মনে রাখার জন্য আমরা ব্রাউজার কুকিজ ব্যবহার করি। আপনি চাইলে আপনার ব্রাউজার সেটিং
                        থেকে এটি নিয়ন্ত্রণ করতে পারেন।</p>
                </section>
            </div>

            <div class="mt-12 pt-8 border-t border-white/5">
                <a href="{{ auth()->check() ? route('dashboard') : route('home') }}"
                    class="inline-flex items-center text-primary-500 font-bold hover:underline">
                    ← {{ auth()->check() ? 'ড্যাশবোর্ডে ফিরে যান' : 'হোম পেজে ফিরে যান' }}
                </a>
            </div>
        </div>
    </div>
</x-user-layout>
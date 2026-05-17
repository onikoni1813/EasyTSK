<x-user-layout>
    <div class="max-w-4xl mx-auto py-12">
        <div class="bg-dark-card border border-white/5 rounded-[40px] p-8 md:p-12 shadow-sm">
            <h1 class="text-3xl font-black text-white mb-8 uppercase tracking-tight">
                শর্তাবলী ও <span class="text-primary-500">নিয়মাবলী</span>
            </h1>

            <div class="prose prose-invert max-w-none text-slate-400 font-medium space-y-6">
                <section>
                    <h2 class="text-xl font-bold text-white mb-3">১. একাউন্ত ও নিরাপত্তা</h2>
                    <p>আমাদের প্ল্যাটফর্মে একজন ব্যবহারকারী শুধুমাত্র একটি একাউন্ট ব্যবহার করতে পারবেন। একাধিক একাউন্ট
                        বা ফেক ইনফরমেশন ব্যবহার করলে একাউন্ট স্থায়ীভাবে ব্যান করা হবে।</p>
                </section>

                <section>
                    <h2 class="text-xl font-bold text-white mb-3">২. কাজের নিয়ম</h2>
                    <p>প্রতিটি টাস্ক বা অফার নিখুঁতভাবে সম্পন্ন করতে হবে। ভুল তথ্য বা স্প্যামিং করলে আপনার পেমেন্ট
                        রিজেক্ট হতে পারে।</p>
                </section>

                <section>
                    <h2 class="text-xl font-bold text-white mb-3">৩. পেমেন্ট পলিসি</h2>
                    <p>উইথড্র করার পর সাধারণত ২৪ থেকে ৪৮ ঘণ্টার মধ্যে পেমেন্ট প্রসেস করা হয়। শুক্র ও শনিবার পেমেন্টে
                        কিছুটা দেরি হতে পারে।</p>
                </section>

                <section>
                    <h2 class="text-xl font-bold text-white mb-3">৪. রেফারেল বোনাস</h2>
                    <p>রেফারেল বোনাস পাওয়ার জন্য আপনার রেফার করা মেম্বারকে অবশ্যই এক্টিভ থাকতে হবে এবং নির্দিষ্ট পরিমাণ
                        কাজ সম্পন্ন করতে হবে।</p>
                </section>

                <section>
                    <h2 class="text-xl font-bold text-white mb-3">৫. পরিবর্তন ও পরিমার্জন</h2>
                    <p>কর্তৃপক্ষ যেকোনো সময় নিয়মাবলী পরিবর্তন করার ক্ষমতা রাখে। নিয়মিত এই পেজটি ভিজিট করে আপডেট জেনে
                        নিন।</p>
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
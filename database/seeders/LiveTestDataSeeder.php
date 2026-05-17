<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TaskDomain;
use App\Models\BlogPost;

class LiveTestDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Clear existing data to keep testing clean
        TaskDomain::truncate();
        BlogPost::truncate();

        // 2. Add 5 Connected Domains with custom Ad Networks & Direct Links
        $subdomains = [
            [
                'domain' => 'http://localhost',
                'ad_code_1' => '<div style="background:linear-gradient(135deg, #a855f7, #7c3aed); color:white; padding:20px; border-radius:16px; font-weight:900; text-align:center; box-shadow:0 8px 30px rgba(168,85,247,0.3); border:1px solid rgba(255,255,255,0.1); margin:15px 0;">💜 [LOCAL HOST - XAMPP TEST BANNER AD 1 (TOP)]</div>',
                'ad_code_2' => '<div style="background:linear-gradient(135deg, #7c3aed, #6d28d9); color:white; padding:25px; border-radius:16px; font-weight:900; text-align:center; box-shadow:0 8px 30px rgba(124,58,237,0.3); border:1px solid rgba(255,255,255,0.1); margin:15px 0;">💜 [LOCAL HOST - XAMPP TEST BANNER AD 2 (MIDDLE)]</div>',
                'ad_code_3' => '<div style="background:linear-gradient(135deg, #6d28d9, #5b21b6); color:white; padding:20px; border-radius:16px; font-weight:900; text-align:center; box-shadow:0 8px 30px rgba(109,40,217,0.3); border:1px solid rgba(255,255,255,0.1); margin:15px 0;">💜 [LOCAL HOST - XAMPP TEST BANNER AD 3 (BOTTOM)]</div>',
                'direct_link' => 'http://127.0.0.1:8000/localhost-direct-link',
                'is_active' => true
            ],
            [
                'domain' => 'http://sub1.localhost',
                'ad_code_1' => '<div style="background:linear-gradient(135deg, #10b981, #059669); color:white; padding:20px; border-radius:16px; font-weight:900; text-align:center; box-shadow:0 8px 30px rgba(16,185,129,0.3); border:1px solid rgba(255,255,255,0.1); margin:15px 0;">🟢 [SUB 1 - ADSTERRA PREMIUM BANNER AD 1 (TOP)]</div>',
                'ad_code_2' => '<div style="background:linear-gradient(135deg, #059669, #047857); color:white; padding:25px; border-radius:16px; font-weight:900; text-align:center; box-shadow:0 8px 30px rgba(5,150,105,0.3); border:1px solid rgba(255,255,255,0.1); margin:15px 0;">🟢 [SUB 1 - ADSTERRA PREMIUM BANNER AD 2 (MIDDLE)]</div>',
                'ad_code_3' => '<div style="background:linear-gradient(135deg, #047857, #065f46); color:white; padding:20px; border-radius:16px; font-weight:900; text-align:center; box-shadow:0 8px 30px rgba(4,120,87,0.3); border:1px solid rgba(255,255,255,0.1); margin:15px 0;">🟢 [SUB 1 - ADSTERRA PREMIUM BANNER AD 3 (BOTTOM)]</div>',
                'direct_link' => 'http://127.0.0.1:8000/sub1-exclusive-direct-link',
                'is_active' => true
            ],
            [
                'domain' => 'http://sub2.localhost',
                'ad_code_1' => '<div style="background:linear-gradient(135deg, #f43f5e, #e11d48); color:white; padding:20px; border-radius:16px; font-weight:900; text-align:center; box-shadow:0 8px 30px rgba(244,63,94,0.3); border:1px solid rgba(255,255,255,0.1); margin:15px 0;">🔴 [SUB 2 - MONETAG EXCLUSIVE BANNER AD 1 (TOP)]</div>',
                'ad_code_2' => '<div style="background:linear-gradient(135deg, #e11d48, #be123c); color:white; padding:25px; border-radius:16px; font-weight:900; text-align:center; box-shadow:0 8px 30px rgba(225,29,72,0.3); border:1px solid rgba(255,255,255,0.1); margin:15px 0;">🔴 [SUB 2 - MONETAG EXCLUSIVE BANNER AD 2 (MIDDLE)]</div>',
                'ad_code_3' => '<div style="background:linear-gradient(135deg, #be123c, #9f1239); color:white; padding:20px; border-radius:16px; font-weight:900; text-align:center; box-shadow:0 8px 30px rgba(190,18,60,0.3); border:1px solid rgba(255,255,255,0.1); margin:15px 0;">🔴 [SUB 2 - MONETAG EXCLUSIVE BANNER AD 3 (BOTTOM)]</div>',
                'direct_link' => 'http://127.0.0.1:8000/sub2-exclusive-direct-link',
                'is_active' => true
            ],
            [
                'domain' => 'http://sub3.localhost',
                'ad_code_1' => '<div style="background:linear-gradient(135deg, #3b82f6, #2563eb); color:white; padding:20px; border-radius:16px; font-weight:900; text-align:center; box-shadow:0 8px 30px rgba(59,130,246,0.3); border:1px solid rgba(255,255,255,0.1); margin:15px 0;">🔵 [SUB 3 - PROPELLER ADS HIGH CPM BANNER AD 1 (TOP)]</div>',
                'ad_code_2' => '<div style="background:linear-gradient(135deg, #2563eb, #1d4ed8); color:white; padding:25px; border-radius:16px; font-weight:900; text-align:center; box-shadow:0 8px 30px rgba(37,99,235,0.3); border:1px solid rgba(255,255,255,0.1); margin:15px 0;">🔵 [SUB 3 - PROPELLER ADS HIGH CPM BANNER AD 2 (MIDDLE)]</div>',
                'ad_code_3' => '<div style="background:linear-gradient(135deg, #1d4ed8, #1e40af); color:white; padding:20px; border-radius:16px; font-weight:900; text-align:center; box-shadow:0 8px 30px rgba(29,78,216,0.3); border:1px solid rgba(255,255,255,0.1); margin:15px 0;">🔵 [SUB 3 - PROPELLER ADS HIGH CPM BANNER AD 3 (BOTTOM)]</div>',
                'direct_link' => 'http://127.0.0.1:8000/sub3-exclusive-direct-link',
                'is_active' => true
            ],
            [
                'domain' => 'http://sub4.localhost',
                'ad_code_1' => '<div style="background:linear-gradient(135deg, #eab308, #ca8a04); color:white; padding:20px; border-radius:16px; font-weight:900; text-align:center; box-shadow:0 8px 30px rgba(234,179,8,0.3); border:1px solid rgba(255,255,255,0.1); margin:15px 0;">🟡 [SUB 4 - YLLIX NATIVE BANNER AD 1 (TOP)]</div>',
                'ad_code_2' => '<div style="background:linear-gradient(135deg, #ca8a04, #a16207); color:white; padding:25px; border-radius:16px; font-weight:900; text-align:center; box-shadow:0 8px 30px rgba(202,138,4,0.3); border:1px solid rgba(255,255,255,0.1); margin:15px 0;">🟡 [SUB 4 - YLLIX NATIVE BANNER AD 2 (MIDDLE)]</div>',
                'ad_code_3' => '<div style="background:linear-gradient(135deg, #a16207, #854d0e); color:white; padding:20px; border-radius:16px; font-weight:900; text-align:center; box-shadow:0 8px 30px rgba(161,98,7,0.3); border:1px solid rgba(255,255,255,0.1); margin:15px 0;">🟡 [SUB 4 - YLLIX NATIVE BANNER AD 3 (BOTTOM)]</div>',
                'direct_link' => 'http://127.0.0.1:8000/sub4-exclusive-direct-link',
                'is_active' => true
            ],
        ];

        foreach ($subdomains as $sub) {
            TaskDomain::create($sub);
        }

        // 3. Create 5 beautiful blog posts containing all the dynamic placeholders
        $posts = [
            [
                'title' => 'Top 5 Legal Ways to Make Money From Home in 2026',
                'content' => '
                    <h2 class="text-xl font-bold text-white mb-4">Introduction to Micro Jobs</h2>
                    <p class="text-sm text-slate-300 mb-6">Micro jobs represent the fastest way for beginners to start generating daily pocket money without any technical expertise.</p>
                    
                    {ad_code_1}
                    
                    <h2 class="text-xl font-bold text-white my-4">Method 1: Dynamic Link Clicks</h2>
                    <p class="text-sm text-slate-300 mb-6">Simply click on the verified links from the portal, wait for the timer, and claim your code easily.</p>
                    
                    {ad_code_2}
                    
                    <h2 class="text-xl font-bold text-white my-4">Claim Your Rewards Below</h2>
                    <p class="text-sm text-slate-300 mb-6">Click the official link below to unlock the task timer and generate your verification code instantly.</p>
                    
                    <div class="text-center my-6">
                        <a href="{direct_link}" target="_blank" class="inline-flex items-center justify-center bg-gradient-to-r from-primary-500 to-indigo-600 text-white font-black text-xs uppercase tracking-widest px-8 py-4 rounded-full shadow-lg hover:shadow-primary-500/20 transition-all">
                            🚀 UNLOCK TASK CODE
                        </a>
                    </div>
                    
                    {ad_code_3}
                '
            ],
            [
                'title' => 'Complete Beginner Guide to Earning on MicroJob Portal',
                'content' => '
                    <h2 class="text-xl font-bold text-white mb-4">Getting Started</h2>
                    <p class="text-sm text-slate-300 mb-6">Welcome to our earning network. Our tasks are fully automated and verified instantly.</p>
                    
                    {ad_code_1}
                    
                    <h2 class="text-xl font-bold text-white my-4">Avoid Multi-Accounts</h2>
                    <p class="text-sm text-slate-300 mb-6">Using multiple accounts or bots will result in permanent ban and loss of earnings.</p>
                    
                    {ad_code_2}
                    
                    <div class="text-center my-6">
                        <a href="{direct_link}" target="_blank" class="inline-flex items-center justify-center bg-gradient-to-r from-primary-500 to-pink-500 text-white font-black text-xs uppercase tracking-widest px-8 py-4 rounded-full shadow-lg hover:shadow-primary-500/20 transition-all">
                            💥 START VERIFICATION
                        </a>
                    </div>
                    
                    {ad_code_3}
                '
            ],
            [
                'title' => 'How to Earn $10 Daily from Simple Copy-Paste Tasks',
                'content' => '
                    <h2 class="text-xl font-bold text-white mb-4">Copy-Paste Earning Strategy</h2>
                    <p class="text-sm text-slate-300 mb-6">Copy codes generated on our dynamic subdomains and paste them inside the MicroJob portal task submission box.</p>
                    
                    {ad_code_1}
                    
                    {ad_code_2}
                    
                    <div class="text-center my-6">
                        <a href="{direct_link}" target="_blank" class="inline-flex items-center justify-center bg-gradient-to-r from-emerald-500 to-teal-500 text-white font-black text-xs uppercase tracking-widest px-8 py-4 rounded-full shadow-lg hover:shadow-emerald-500/20 transition-all">
                            ⚡ OPEN TASK GATEWAY
                        </a>
                    </div>
                    
                    {ad_code_3}
                '
            ],
            [
                'title' => 'The Secret to Maximizing Earning from High CPM Ads',
                'content' => '
                    <h2 class="text-xl font-bold text-white mb-4">Ad Rotation Network</h2>
                    <p class="text-sm text-slate-300 mb-6">By using our premium ad network domains, you get access to top tier CPM campaigns automatically.</p>
                    
                    {ad_code_1}
                    
                    <div class="text-center my-6">
                        <a href="{direct_link}" target="_blank" class="inline-flex items-center justify-center bg-gradient-to-r from-amber-500 to-orange-500 text-white font-black text-xs uppercase tracking-widest px-8 py-4 rounded-full shadow-lg hover:shadow-orange-500/20 transition-all">
                            👑 REVEAL SECRET KEY
                        </a>
                    </div>
                    
                    {ad_code_2}
                    
                    {ad_code_3}
                '
            ],
            [
                'title' => 'Instant Withdrawal Proof & Live Payment Methods Glimpse',
                'content' => '
                    <h2 class="text-xl font-bold text-white mb-4">Withdrawal Channels</h2>
                    <p class="text-sm text-slate-300 mb-6">Withdraw earnings instantly using bKash, Nagad, Rocket, or Binance Pay once you hit the minimum threshold.</p>
                    
                    {ad_code_1}
                    
                    {ad_code_2}
                    
                    <div class="text-center my-6">
                        <a href="{direct_link}" target="_blank" class="inline-flex items-center justify-center bg-gradient-to-r from-purple-500 to-indigo-500 text-white font-black text-xs uppercase tracking-widest px-8 py-4 rounded-full shadow-lg hover:shadow-purple-500/20 transition-all">
                            💸 GET VERIFICATION PIN
                        </a>
                    </div>
                    
                    {ad_code_3}
                '
            ]
        ];

        foreach ($posts as $post) {
            BlogPost::create($post);
        }
    }
}

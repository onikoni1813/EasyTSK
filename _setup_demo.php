<?php
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== Current Data ===\n";
echo "Domains: " . \App\Models\TaskDomain::count() . "\n";
echo "Blog Posts: " . \App\Models\BlogPost::count() . "\n";
echo "Tasks: " . \App\Models\Task::count() . "\n";
echo "Users: " . \App\Models\User::count() . "\n\n";

// Show existing domains
foreach (\App\Models\TaskDomain::all() as $d) {
    echo "Domain #{$d->id}: {$d->domain} (active: " . ($d->is_active ? 'yes' : 'no') . ")\n";
}

echo "\n=== Creating Demo Data ===\n";

// 1. Create a demo user if none exists
$user = \App\Models\User::first();
if (!$user) {
    $user = \App\Models\User::create([
        'name' => 'Demo User',
        'email' => 'demo@easytsk.com',
        'password' => bcrypt('password123'),
        'is_admin' => true,
        'points' => 5000,
        'trust_score' => 100,
    ]);
    echo "Created demo user: demo@easytsk.com / password123\n";
} else {
    echo "Using existing user: {$user->email}\n";
}

// 2. Create a demo domain with realistic ad codes
$domain = \App\Models\TaskDomain::where('domain', 'like', '%demo-sub.easytsk.com%')->first();
if (!$domain) {
    $domain = \App\Models\TaskDomain::create([
        'domain' => 'https://demo-sub.easytsk.com',
        'ad_code_1' => '<div style="background:#ff6b35;color:#fff;padding:20px;text-align:center;border-radius:12px;font-family:sans-serif;margin:10px 0;"><h3 style="margin:0 0 8px 0;">🔥 স্পেশাল অফার - মাত্র ৯৯ টাকা!</h3><p style="margin:0 0 12px 0;">সেরা মানের প্রোডাক্ট, ফ্রি ডেলিভারি</p><a href="{direct_link}" style="display:inline-block;background:#fff;color:#ff6b35;padding:10px 30px;border-radius:8px;text-decoration:none;font-weight:bold;">এখনই কিনুন →</a></div>',
        'ad_code_2' => '<div style="background:linear-gradient(135deg,#667eea,#764ba2);color:#fff;padding:15px;text-align:center;border-radius:12px;font-family:sans-serif;margin:10px 0;"><p style="margin:0;font-size:14px;">📱 ৫০% পর্যন্ত ছাড়! সীমিত সময়ের অফার</p></div>',
        'ad_code_3' => '<div style="background:#f8f9fa;border:2px solid #28a745;padding:15px;text-align:center;border-radius:12px;font-family:sans-serif;margin:10px 0;"><p style="margin:0;color:#28a745;font-weight:bold;">✅ ১০,০০০+ খুশি গ্রাহক | ⭐ ৪.৮/৫ রেটিং</p></div>',
        'direct_link' => 'https://example.com/demo-offer-landing',
        'is_active' => true,
    ]);
    echo "Created demo domain: demo-sub.easytsk.com\n";
} else {
    echo "Demo domain already exists\n";
}

// 3. Create a demo blog post
$post = \App\Models\BlogPost::where('title', 'like', '%ডেমো%')->first();
if (!$post) {
    $post = \App\Models\BlogPost::create([
        'title' => '🎯 ডেমো সাব-ডোমেইন টাস্ক - অ্যাড ও ডাইরেক্ট লিংক টেস্ট',
        'content' => '<div style="max-width:700px;margin:0 auto;font-family:sans-serif;padding:20px;">

<h1 style="color:#1a1a2e;text-align:center;font-size:24px;">🚀 আজই শুরু করুন অনলাইন ইনকাম!</h1>

<p style="text-align:center;color:#555;font-size:16px;">নিচের অফারটি দেখুন এবং টাস্ক সম্পন্ন করে পয়েন্ট অর্জন করুন।</p>

<!-- Banner Ad 1 -->
{ad_code_1}

<!-- Main Content -->
<div style="background:#fff;padding:20px;border-radius:12px;box-shadow:0 2px 10px rgba(0,0,0,0.1);margin:20px 0;">
    <h2 style="color:#333;margin-top:0;">📋 এই টাস্কে যা করতে হবে:</h2>
    <ol style="color:#555;line-height:2;">
        <li>নিচের <strong>ডাইরেক্ট লিংক</strong>-এ ক্লিক করে অফার পেজ ভিজিট করুন</li>
        <li>পেজটি সম্পূর্ণ লোড হওয়া পর্যন্ত অপেক্ষা করুন (কমপক্ষে ৩০ সেকেন্ড)</li>
        <li>পেজের স্ক্রিনশট তুলে সাবমিট করুন</li>
    </ol>
    
    <div style="text-align:center;margin:25px 0;">
        <a href="{direct_link}" target="_blank" style="display:inline-block;background:#4f46e5;color:#fff;padding:15px 40px;border-radius:10px;text-decoration:none;font-size:18px;font-weight:bold;">🎁 অফার দেখতে ক্লিক করুন</a>
    </div>
</div>

<!-- Banner Ad 2 -->
{ad_code_2}

<!-- Trust Section -->
<div style="background:#f0fdf4;border:1px solid #bbf7d0;padding:20px;border-radius:12px;margin:20px 0;">
    <h3 style="color:#166534;margin-top:0;">✅ কেন আমাদের প্ল্যাটফর্ম সেরা?</h3>
    <ul style="color:#166534;line-height:2;">
        <li>💸 বিকাশ/নগদ/রকেট - সব পেমেন্ট মেথড সাপোর্ট</li>
        <li>⚡ ২৪ ঘন্টার মধ্যে পেমেন্ট</li>
        <li>🔒 ১০০% সিকিউর ও ট্রাস্টেড</li>
        <li>👥 রেফার করে বাড়তি ইনকাম</li>
    </ul>
</div>

<!-- Banner Ad 3 -->
{ad_code_3}

<div style="text-align:center;color:#888;font-size:12px;margin-top:30px;">
    <p>User ID: {user_id} | Task ID: {task_id}</p>
    <p>© ২০২৬ EasyTSK - বিশ্বস্ত মাইক্রো টাস্ক প্ল্যাটফর্ম</p>
</div>

</div>',
    ]);
    echo "Created demo blog post: ID #{$post->id}\n";
} else {
    echo "Demo blog post already exists: ID #{$post->id}\n";
}

// 4. Create a demo task for subdomain
$task = \App\Models\Task::where('title', 'like', '%ডেমো সাব-ডোমেইন%')->first();
if (!$task) {
    $task = \App\Models\Task::create([
        'title' => '🌐 ডেমো সাব-ডোমেইন টাস্ক - অ্যাড দেখে ইনকাম',
        'description' => "এটি একটি ডেমো সাব-ডোমেইন টাস্ক।\n\nকাজের নিয়ম:\n১. নিচের লিংকে ক্লিক করে সাব-ডোমেইন পেজ ভিজিট করুন\n২. পেজের অ্যাডগুলো দেখুন\n৩. ডাইরেক্ট অফার লিংকে ক্লিক করুন\n৪. স্ক্রিনশট তুলে সাবমিট করুন\n\nসাব-ডোমেইন লিংক: https://demo-sub.easytsk.com\n\nসতর্কতা: VPN ব্যবহার করবেন না।",
        'points' => 50,
        'admin_profit' => 10,
        'quota_max' => 100,
        'quota_remaining' => 100,
        'cooldown_hours' => 24,
        'type' => 'custom',
        'requires_text_proof' => false,
        'requires_image_proof' => true,
        'external_link' => 'https://demo-sub.easytsk.com',
        'is_active' => true,
    ]);
    echo "Created demo task: ID #{$task->id}\n";
} else {
    echo "Demo task already exists: ID #{$task->id}\n";
}

echo "\n=== Done! ===\n";
echo "Demo Domain: https://demo-sub.easytsk.com\n";
echo "Demo Blog Post ID: {$post->id}\n";
echo "Demo Task ID: {$task->id}\n";
echo "Demo User: demo@easytsk.com / password123\n";
echo "\nTest URLs:\n";
echo "API (with domain param): http://127.0.0.1:8000/api/blog-posts/latest?domain=https://demo-sub.easytsk.com&uid=123&tid=" . $task->id . "\n";
echo "API (with Origin header): curl -H 'Origin: https://demo-sub.easytsk.com' http://127.0.0.1:8000/api/blog-posts/latest?uid=123&tid=" . $task->id . "\n";
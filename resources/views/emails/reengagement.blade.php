<!DOCTYPE html>
<html>

<head>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 600px;
            margin: 40px auto;
            background-color: #ffffff;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        .header {
            background: linear-gradient(135deg, #00c853 0%, #00e653 100%);
            padding: 40px;
            text-align: center;
            color: white;
        }

        .content {
            padding: 40px;
            color: #333333;
            line-height: 1.6;
        }

        .cta-button {
            display: inline-block;
            padding: 15px 30px;
            background-color: #00c853;
            color: white;
            text-decoration: none;
            border-radius: 12px;
            font-weight: bold;
            margin-top: 20px;
        }

        .footer {
            padding: 20px;
            text-align: center;
            color: #888888;
            font-size: 12px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1 style="margin:0; font-size: 24px;">EasyTSK Platform</h1>
        </div>
        <div class="content">
            <h2>স্বাগতম, {{ $user->name }}! 👋</h2>
            <p>আমাদের প্ল্যাটফর্মে যোগ দেওয়ার জন্য ধন্যবাদ। আমরা লক্ষ্য করেছি যে আপনি আপনার প্রথম টাস্কটি এখনো শুরু
                করেননি।</p>
            <p>আপনার জন্য কিছু সহজ কাজ অপেক্ষা করছে যা সম্পন্ন করলে আপনি তাৎক্ষণিক রিওয়ার্ড পাবেন। এখন শুরু করে আপনার
                প্রথম আয় নিশ্চিত করুন!</p>
            <center>
                <a href="{{ url('/') }}" class="cta-button">কাজ শুরু করুন 🚀</a>
            </center>
            <p style="margin-top: 30px;">যেকোনো প্রয়োজনে আমাদের সাপোর্ট সেকশনে নক দিতে পারেন।</p>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
        </div>
    </div>
</body>

</html>
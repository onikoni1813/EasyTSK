<x-admin-layout>
    <div style="font-family: sans-serif; padding: 50px; background: #f4f4f4; min-height: 100vh;">
        <div
            style="max-width: 600px; margin: 0 auto; background: white; padding: 40px; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
            <h1 style="margin-top:0;">Shared Hosting Toolkit 🛠️</h1>
            <p style="color: #666;">No SSH? No problem. Use these buttons to manage your site.</p>

            @if (session('status'))
                <div style="color: green; padding: 10px; border: 1px solid green; margin-bottom: 10px;">
                    {{ session('status') }}
                </div>
            @endif

            <ul style="list-style: none; padding: 0;">
                <li style="margin-bottom: 15px;">
                    <a style="display: block; padding: 15px; background: #4f46e5; color: white; text-decoration: none; border-radius: 10px; font-weight: bold; text-align: center;"
                        href="{{ route('admin.toolkit.migrate') }}">🚀 Run Migrations (DB Setup)</a>
                </li>
                <li style="margin-bottom: 15px;">
                    <a style="display: block; padding: 15px; background: #0ea5e9; color: white; text-decoration: none; border-radius: 10px; font-weight: bold; text-align: center;"
                        href="{{ route('admin.toolkit.seed') }}">🌱 Run Seeders (Admin/Setup Data)</a>
                </li>
                <li style="margin-bottom: 15px;">
                    <a style="display: block; padding: 15px; background: #10b981; color: white; text-decoration: none; border-radius: 10px; font-weight: bold; text-align: center;"
                        href="{{ route('admin.toolkit.storage-link') }}">📂 Create Storage Link (Fix Images)</a>
                </li>
                <li style="margin-bottom: 15px;">
                    <a style="display: block; padding: 15px; background: #f59e0b; color: white; text-decoration: none; border-radius: 10px; font-weight: bold; text-align: center;"
                        href="{{ route('admin.toolkit.clear-cache') }}">🧹 Clear All Cache</a>
                </li>
            </ul>
            <hr style="margin: 30px 0; border: 0; border-top: 1px solid #eee;">
            <a href="{{ route('admin.dashboard') }}"
                style="color: #4f46e5; font-weight: bold; text-decoration: none;">&larr; Back to Dashboard</a>
        </div>
    </div>
</x-admin-layout>
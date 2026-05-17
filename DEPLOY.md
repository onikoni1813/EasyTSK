# EasyTSK — cPanel Deployment Guide

> Last updated: 2026-05-17 | Target: Shared Hosting with cPanel

---

## ⚠️ Before You Start

| Step | Action | Why |
|------|--------|-----|
| 🔑 | Create GitHub **Personal Access Token** (PAT) | GitHub stopped accepting passwords in 2021 |
| 💾 | Backup your **MySQL database** | Safety first — phpMyAdmin → Export → Quick → Go |
| 📁 | Download `storage/app/` if you have KYC files | User-uploaded files |
| 📝 | Note your current `.env` DB credentials | You'll need them for the new `.env` |

### Create GitHub PAT
```
GitHub → Settings → Developer settings → Personal access tokens → Tokens (classic)
→ Generate new token (classic)
→ Check: "repo" (full control)
→ Copy the token: ghp_xxxxxxxxxxxxxxxxxxxx
```

---

## 📦 STEP 1: Backup Database (phpMyAdmin)

1. cPanel → **phpMyAdmin**
2. Left sidebar: click your database name
3. Top menu: **Export**
4. Select: **Quick** (or Custom → all tables)
5. Format: **SQL**
6. Click **Go** → file downloads

> ✅ Done. Your data is safe.

---

## 🗑️ STEP 2: Remove Old Files

### Option A: Via cPanel File Manager
1. cPanel → **File Manager** → go to `public_html`
2. **Select All** files (Ctrl+A)
3. If you want to keep `.env` — uncheck it before deleting
4. Click **Delete** → Confirm

### Option B: Via Terminal/SSH (faster)
```bash
cd ~/public_html

# Backup .env if needed
cp .env ~/env.backup

# Delete everything EXCEPT hidden files
rm -rf *

# Also remove hidden files (optional)
rm -rf .git .gitignore .htaccess .editorconfig
```

---

## 🚀 STEP 3: Clone from GitHub

### Via cPanel Terminal / SSH
```bash
cd ~
rm -rf public_html    # Delete the now-empty folder
git clone https://github.com/onikoni1813/EasyTSK.git public_html
cd public_html
```

> 🔑 When Git asks for password, paste your **PAT** (not your GitHub password)

### Via cPanel Git Version Control (if available)
1. cPanel → **Git™ Version Control**
2. Click **Create** → paste: `https://github.com/onikoni1813/EasyTSK.git`
3. Repository Path: `public_html`
4. Click **Create** → then **Manage** → **Pull or Deploy**

---

## ⚙️ STEP 4: Configure Environment

### Create .env from example
```bash
cd ~/public_html
cp .env.example .env
```

### Edit .env with nano or cPanel File Manager
```bash
nano .env
```

Set these values:

```env
APP_NAME=EasyTSK
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com

# Database (from your old .env backup)
DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=your_cpanel_db_name
DB_USERNAME=your_cpanel_db_user
DB_PASSWORD=your_cpanel_db_password

# Session Security (for HTTPS)
SESSION_SECURE_COOKIE=true
SESSION_ENCRYPT=false

# Queue (sync only — no queue worker on shared hosting)
QUEUE_CONNECTION=sync

# 🔒 CHANGE THIS in production!
TASK_SECRET_SALT=MicroJobV1Secret!

# Timezone
APP_TIMEZONE=Asia/Dhaka
```

---

## 📦 STEP 5: Install Dependencies

```bash
cd ~/public_html

# Install composer (skip if already installed)
# Check: which composer

composer install --no-dev --optimize-autoloader
```

> If `composer` command not found:
> ```
> cd ~
> curl -sS https://getcomposer.org/installer | php
> mv composer.phar /usr/local/bin/composer
> ```

---

## 🔧 STEP 6: Laravel Setup

```bash
cd ~/public_html

# Generate app key
php artisan key:generate

# Create storage symlink
php artisan storage:link

# Set permissions
chmod -R 755 storage bootstrap/cache
chmod -R 755 public
```

> If `php` command not found, try: `/usr/local/bin/php` or `/opt/cpanel/ea-php82/root/usr/bin/php`

---

## 🗄️ STEP 7: Database Migration & Seed

```bash
cd ~/public_html

# Run all migrations
php artisan migrate

# Seed essential data
php artisan db:seed --class=SettingSeeder
php artisan db:seed --class=AdminSeeder
php artisan db:seed --class=WithdrawalMethodSeeder
```

> ⚠️ Do NOT run `php artisan migrate:fresh` — it will DROP all tables!

---

## ⚡ STEP 8: Production Cache (Optional but Recommended)

```bash
cd ~/public_html

php artisan config:cache
php artisan route:cache
php artisan view:cache
```

> ⚠️ After this, `.env` changes won't take effect until you run:  
> `php artisan config:clear`

---

## 📝 STEP 9: Blog Subdomain Setup

### Create subdomain in cPanel
1. cPanel → **Subdomains**
2. Subdomain: `blog` → yourdomain.com
3. Document Root: `public_html/blog`

### Deploy blog files
```bash
cd ~/public_html
mkdir blog
cd blog
git clone https://github.com/onikoni1813/EasyTSK-Blog.git .
cp db.example.php db.php
nano db.php   # Set your DB credentials
```

### Blog db.php configuration
```php
$host = 'localhost';
$user = 'your_cpanel_db_user';      // SAME as .env DB_USERNAME
$pass = 'your_cpanel_db_password';  // SAME as .env DB_PASSWORD
$dbname = 'your_cpanel_db_name';    // SAME as .env DB_DATABASE
```

---

## 🛡️ STEP 10: SSL & Security

### Enable SSL (cPanel)
1. cPanel → **SSL/TLS Status**
2. Select your domain → **Run AutoSSL**
3. Force HTTPS: add to `public/.htaccess` (already included)

### Final security checks
```bash
cd ~/public_html

# Verify .env is NOT accessible from web
curl -I https://yourdomain.com/.env
# Should return: 403 Forbidden or 404 Not Found

# Verify storage link
ls -la public/storage
# Should show: storage -> ../storage/app/public
```

---

## ✅ VERIFICATION CHECKLIST

| # | Check | Command / Action |
|---|-------|-----------------|
| 1 | Homepage loads | Open `https://yourdomain.com` |
| 2 | No .env exposed | `https://yourdomain.com/.env` → 403/404 |
| 3 | Login works | Login with admin credentials |
| 4 | Admin panel | `https://yourdomain.com/admin` |
| 5 | Task list shows | Visit Tasks page |
| 6 | Blog subdomain | `https://blog.yourdomain.com` |
| 7 | CSS loads correctly | No broken styles |
| 8 | Debug off | No Laravel debugbar, no stack traces |

---

## 🔄 UPDATING (After Future Commits)

```bash
cd ~/public_html
git pull origin main
php artisan migrate          # Only if new migrations
php artisan config:clear     # If .env changed
php artisan optimize:clear   # If views/routes changed
```

---

## 🆘 TROUBLESHOOTING

### 404 on all pages except homepage
```bash
# Check .htaccess exists in public/
ls -la public/.htaccess

# If missing, create it:
cp .htaccess public/.htaccess
```

### 500 Internal Server Error
```bash
# Check Laravel logs
tail -50 storage/logs/laravel.log

# Common fixes:
chmod -R 755 storage bootstrap/cache
php artisan config:clear
php artisan optimize:clear
```

### "No application encryption key"
```bash
php artisan key:generate
# Or manually: nano .env → set APP_KEY=base64:......
```

### composer: command not found
```bash
cd ~
curl -sS https://getcomposer.org/installer | php
mv composer.phar /usr/local/bin/composer
# Or use PHP version-specific path:
alias php='/opt/cpanel/ea-php82/root/usr/bin/php'
```

### Storage symlink not working
```bash
rm -rf public/storage
php artisan storage:link
```

---

## 📞 Credentials After Deployment

| Account | Username | Password |
|---------|----------|----------|
| Admin | (from AdminSeeder) | (from AdminSeeder) |
| GitHub | onikoni1813 | ⚠️ Changed? |
| cPanel | (your cPanel) | (your cPanel) |

---

> 💡 **Tip:** cPanel Terminal path for PHP: `/opt/cpanel/ea-php82/root/usr/bin/php` (adjust PHP version number)
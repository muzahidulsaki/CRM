# Projukti Sheba CRM — প্রোডাকশন ডিপ্লয়মেন্ট ও সার্ভার কনফিগারেশন গাইড
> **সিস্টেম:** Projukti Sheba CRM & Business Management System  
> **ভার্সন:** 1.0 Production Release (Laravel 11/12, MySQL 8.0, PHP 8.2+)  
> **ডকুমেন্টেশন রেফারেন্স:** SRS Section 42 (Security), 53 (Backup & Recovery), 54 & 55 (Phase 9 — QA & Production)

---

## ১. সার্ভার প্রয়োজনীয়তা (Server Prerequisites)

| কম্পোনেন্ট | ন্যূনতম স্পেসিফিকেশন | প্রস্তাবিত (Recommended) |
|:---|:---|:---|
| **অপারেটিং সিস্টেম** | Ubuntu 22.04 LTS / Debian 12 | Ubuntu 24.04 LTS |
| **PHP ভার্সন** | PHP 8.2 অথবা 8.3 | PHP 8.3-FPM |
| **ডাটাবেজ** | MySQL 8.0+ অথবা MariaDB 10.6+ | MySQL 8.0 (InnoDB) |
| **ওয়েব সার্ভার** | Nginx 1.22+ অথবা Apache 2.4+ | Nginx 1.24+ |
| **র‍্যাম (RAM)** | 2 GB RAM | 4 GB+ RAM |
| **প্রসেসর (CPU)** | 2 vCPU Core | 4 vCPU Core |
| **স্টোরেজ** | 20 GB SSD/NVMe | 40 GB+ NVMe SSD |
| **প্যাকেজ ম্যানেজার** | Composer 2.x ও Node.js 20.x LTS | Composer 2.7+, Node.js 20.x, NPM |

### প্রয়োজনীয় PHP এক্সটেনশনসমূহ:
```bash
sudo apt update && sudo apt install -y php8.3-fpm php8.3-mysql php8.3-common \
    php8.3-curl php8.3-mbstring php8.3-xml php8.3-zip php8.3-bcmath \
    php8.3-soap php8.3-intl php8.3-gd unzip git nginx
```

---

## ২. লাইভ সার্ভার ডিপ্লয়মেন্ট স্টেপস (Step-by-Step Deployment)

### ধাপ ১: রিপোজিটরি ক্লোন ও পারমিশন সেটআপ
```bash
# প্রজেক্ট ডিরেক্টরিতে যান
cd /var/www
git clone <YOUR_GIT_REPO_URL> projukti-crm
cd /var/www/projukti-crm

# ফাইল ও ফোল্ডার পারমিশন কনফিগারেশন
sudo chown -R www-data:www-data /var/www/projukti-crm
sudo find /var/www/projukti-crm -type f -exec chmod 644 {} \;
sudo find /var/www/projukti-crm -type d -exec chmod 755 {} \;
sudo chmod -R 775 /var/www/projukti-crm/storage /var/www/projukti-crm/bootstrap/cache
```

### ধাপ ২: প্রোডাকশন ডিপেন্ডেন্সি ইনস্টলেশন ও বিল্ড
```bash
# Composer Production Packages (--no-dev)
composer install --no-dev --optimize-autoloader

# Frontend Assets Build (Tailwind CSS, JS)
npm ci
npm run build
```

### ধাপ ৩: Environment কনফিগারেশন
```bash
cp .env.production.example .env
nano .env
```
`.env` ফাইলে নিচের তথ্যগুলো নিশ্চিত করুন:
- `APP_ENV=production`
- `APP_DEBUG=false`
- `APP_URL=https://crm.projuktisheba.com`
- `DB_DATABASE=projukti_sheba_crm_prod`
- `DB_USERNAME=projukti_prod_user`
- `DB_PASSWORD=<YourSecurePassword>`

### ধাপ ৪: কী জেনারেশন, মাইগ্রেশন ও স্টোরেজ সিমলিংক
```bash
# অ্যাপ্লিকেশন এনক্রিপশন কী জেনারেট করুন
php artisan key:generate

# ডাটাবেজ মাইগ্রেশন ও রোল/পারমিশন সিডার চালান
php artisan migrate --force
php artisan db:seed --force

# পাবলিক স্টোরেজ সিমলিংক তৈরি
php artisan storage:link
```

---

## ৩. Nginx প্রোডাকশন ভার্চুয়াল হোস্ট কনফিগারেশন

ফাইল তৈরি করুন: `/etc/nginx/sites-available/crm.projuktisheba.com`
```nginx
server {
    listen 80;
    listen [::]:80;
    server_name crm.projuktisheba.com;
    return 301 https://$host$request_uri;
}

server {
    listen 443 ssl http2;
    listen [::]:443 ssl http2;
    server_name crm.projuktisheba.com;
    root /var/www/projukti-crm/public;

    # SSL সার্টিফিকেট (Let's Encrypt / Certbot)
    ssl_certificate /etc/letsencrypt/live/crm.projuktisheba.com/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/crm.projuktisheba.com/privkey.pem;
    ssl_protocols TLSv1.2 TLSv1.3;
    ssl_ciphers HIGH:!aNULL:!MD5;

    # সিকিউরিটি হেডার্স (SRS Section 42)
    add_header X-Frame-Options "SAMEORIGIN" always;
    add_header X-Content-Type-Options "nosniff" always;
    add_header X-XSS-Protection "1; mode=block" always;
    add_header Referrer-Policy "strict-origin-when-cross-origin" always;

    index index.php index.html;
    charset utf-8;

    client_max_body_size 50M;

    # স্ট্যাটিক অ্যাসেটস ক্যাশিং
    location ~* \.(jpg|jpeg|png|gif|ico|css|js|webp|svg|woff|woff2|ttf)$ {
        expires 30d;
        add_header Cache-Control "public, no-transform";
        access_log off;
    }

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.3-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
        fastcgi_hide_header X-Powered-By;
        fastcgi_read_timeout 300;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

সাইট চালু করুন ও Nginx রিলোড দিন:
```bash
sudo ln -s /etc/nginx/sites-available/crm.projuktisheba.com /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl reload nginx
```

### ফ্রি SSL সার্টিফিকেট ইনস্টল (Certbot):
```bash
sudo apt install -y certbot python3-certbot-nginx
sudo certbot --nginx -d crm.projuktisheba.com
```

---

## ৪. cPanel ও Shared Hosting ডিপ্লয়মেন্ট পদ্ধতি

যদি প্রতিষ্ঠানটি cPanel অথবা Shared Hosting ব্যবহার করে:
1. রিপোজিটরির সমস্ত ফাইল জিপ করে cPanel-এর রুট ডিরেক্টরিতে (যেমন `/home/username/crm`) আপলোড ও আনজিপ করুন।
2. প্রজেক্টের `public/` ফোল্ডারের সমস্ত কন্টেন্ট `public_html/` ফোল্ডারে কপি/মুভ করুন (অথবা cPanel ডোমেন ডিরেক্টরি হিসেবে `/home/username/crm/public` সিলেক্ট করুন)।
3. `public_html/index.php` ফাইল এডিট করে পাথের লোকেশন নিশ্চিত করুন:
   ```php
   require __DIR__.'/../crm/vendor/autoload.php';
   $app = require_once __DIR__.'/../crm/bootstrap/app.php';
   ```
4. cPanel MySQL Database Wizard দিয়ে নতুন ডাটাবেজ, ইউজার ও পাসওয়ার্ড তৈরি করে `.env` ফাইলে আপডেট করুন।

---

## ৫. ব্যাকগ্রাউন্ড অটোমেশন ও ক্রনজব শিডিউলার (Cron Setup)

প্রতিদিনের স্বয়ংক্রিয় রিমাইন্ডার (`crm:send-reminders`) এবং রাত ১২:০০ টার অটো ডাটাবেজ ব্যাকআপ (`crm:backup-db`) সচল রাখতে ক্রনজব কনফিগার করুন:

```bash
crontab -e
```
নিচের লাইনটি ক্রন্ট্যাব ফাইলে যুক্ত করুন:
```bash
* * * * * cd /var/www/projukti-crm && php artisan schedule:run >> /dev/null 2>&1
```

---

## ৬. প্রোডাকশন ক্যাশ ও পারফরম্যান্স অপটিমাইজেশন (Speed Optimization)

লাইভ সার্ভারে অত্যন্ত দ্রুত পেজ লোড ও পারফরম্যান্সের জন্য নিচের অপটিমাইজেশন কমান্ডগুলো বাধ্যতামূলকভাবে রান করুন:

```bash
# রুট, কনফিগ ও ভিউ ক্যাশ
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache

# অপ্রয়োজনীয় ক্যাশ ও লক ক্লিয়ারিং
php artisan optimize
```

> **টিপস:** কোনো কোড বা `.env` ফাইল পরিবর্তন করলে প্রথমে `php artisan optimize:clear` এবং এরপর `php artisan optimize` দিন।

---

## ৭. ডাটাবেজ অটো-ব্যাকআপ ও ডিজাস্টার রিকভারি (Disaster Recovery Runbook)

### অটোমেটিক ব্যাকআপ:
- সিস্টেমে বিল্ট-ইন `BackupService` ও `crm:backup-db` কমান্ড সক্রিয় রয়েছে।
- প্রতি রাতে ১২:০০ টায় স্বয়ংক্রিয়ভাবে ডাটাবেজের সম্পূর্ণ জিপ ব্যাকআপ `storage/app/backups/` ফোল্ডারে সংরক্ষিত হয়।
- সিস্টেমে ১৪ দিনের রিটেনশন পলিসি সক্রিয় থাকায় ১৪ দিনের বেশি পুরোনো ফাইল স্বয়ংক্রিয় ডিলিট হয়।
- অ্যাডমিন যেকোনো সময় `/backups` পোর্টাল থেকে সরাসরি ব্যাকআপ ডাউনলোড বা নতুন ব্যাকআপ জেনারেট করতে পারেন।

### ম্যানুয়াল ব্যাকআপ তৈরি:
```bash
# শুধুমাত্র ডাটাবেজ ব্যাকআপ
php artisan crm:backup-db

# ডাটাবেজ ও ক্লায়েন্টদের আপলোডকৃত সমস্ত ফাইল একসাথে ব্যাকআপ
php artisan crm:backup-db --files
```

### জরুরি ডাটা রিস্টোর প্রসিডিউর (Disaster Recovery):
```bash
# ধাপ ১: ব্যাকআপ ফাইলটি আনজিপ করুন
cd /var/www/projukti-crm/storage/app/backups
unzip ps_crm_backup_2026-10-05_102752.zip

# ধাপ ২: MySQL CLI দিয়ে সম্পূর্ণ ডাটাবেজ রিস্টোর করুন
mysql -u projukti_prod_user -p projukti_sheba_crm_prod < ps_crm_backup_2026-10-05_102752.sql
```

---

## ৮. সিকিউরিটি অডিট ও চেক-লিস্ট (Production Security Checklist)

- [x] **APP_DEBUG=false:** লাইভ সার্ভারে কোনো এরর বা ডাটাবেজ ট্রেস ব্যবহারকারীর কাছে প্রদর্শিত হবে না।
- [x] **RBAC Middleware Enforcement:** সাধারণ ডেভেলপার/ভিডিও এডিটররা কোম্পানি ফাইনান্সিয়াল ড্যাশবোর্ড, এমপ্লয়ি পে-আউট, সেটিংস ও ব্যাকআপ সিস্টেমে প্রবেশ করতে পারবে না (HTTP 403 Forbidden)।
- [x] **SQL Injection Defense:** সমস্ত কোয়েরিতে Eloquent ORM ও PDO Prepared Statements ব্যবহার করা হয়েছে।
- [x] **CSRF Protection:** সমস্ত ব্রাউজার ফর্ম সাবমিশনে CSRF টোকেন ভ্যালিডেশন বাধ্যতামূলক।
- [x] **API Authentication:** `/api/v1/*` রুটে ইউনিক বেয়ারার টোকেন (`ps_live_...`) ভিত্তিক ক্লাউড এক্সেস।
- [x] **WhatsApp Deduplication:** মেটা ক্লাউড এপিআই মেসেজ আইডি ক্যাশিং এর মাধ্যমে ডুপ্লিকেট মেসেজ ইনজেস্ট সম্পূর্ণ রোধ।
- [x] **Audit Trail:** সিস্টেমে সমস্ত স্পর্শকাতর অ্যাক্টিভিটি (`ActivityLog`) টাইমস্ট্যাম্প ও ইউজার আইডি সহ সংরক্ষিত।

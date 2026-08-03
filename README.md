# 📸 Aperture Vision - Photography Portfolio & Admin Panel

একটি আধুনিক, আকর্ষণীয় এবং রেসপন্সিভ **ফটোগ্রাফি পোর্টফোলিও ওয়েবসাইট** এবং **অ্যাডমিন প্যানেল**। এখানে ফটোগ্রাফাররা নিজেদের তোলা ছবি ক্যাটাগরি-ওয়াইজ (Nature, Portrait, Street, Landscape ইত্যাদি) ওয়েবসাইট থেকে সরাসরি আপলোড এবং সেগুলোর ক্যামেরা মেটাডাটা (EXIF data - ISO, Shutter speed, Lens, Location) প্রদর্শন করতে পারবেন।

> **Tech Stack:** PHP 8.x | PostgreSQL (Supabase) / MySQL (XAMPP) | HTML5 | CSS3 (Glassmorphic Dark Mode) | Vanilla JavaScript

---

## ⚡ Supabase কানেক্ট করার ৪টি সহজ ধাপ (Supabase Setup Guide)

আপনি যদি **Supabase (Cloud Database)** ব্যবহার করতে চান, তবে নিচের সহজ ধাপগুলো অনুসরণ করুন:

### 1. Supabase-এ টেবিল তৈরি করা (SQL Editor)
1. **[Supabase Dashboard](https://supabase.com/dashboard)**-এ যান এবং আপনার প্রজেক্টটি সিলেক্ট করুন।
2. বামপাশের মেনু থেকে **`SQL Editor`** (`>_` আইকন) তে ক্লিক করুন।
3. **`New query`** বাটনে ক্লিক করুন।
4. আপনার প্রজেক্টের **`sql/supabase_schema.sql`** ফাইলের সম্পূর্ণ কোড কপি করে পেস্ট করুন এবং ডানপাশে **`Run`** বাটনে ক্লিক করুন। 
   *(এর ফলে `categories`, `photos`, `admins`, `messages` টেবিলগুলো এবং প্রাথমিক ছবি ও স্যাম্পল ডাটা সেভ হয়ে যাবে)*।

### 2. ডাটাবেজ তথ্য (Connection Details) সংগ্রহ করা
1. Supabase-এর বামপাশের নিচে থাকা **`Project Settings`** (⚙️ আইকন) এ যান।
2. **`Database`** ট্যাবে ক্লিক করুন।
3. স্ক্রল করে **Connection Parameters** দেখতে পাবেন:
   - **Host:** e.g., `db.xxxxxxxxxxxx.supabase.co`
   - **Port:** `5432`
   - **Database:** `postgres`
   - **User:** `postgres`
   - **Password:** (প্রজেক্ট তৈরির সময় আপনি যে পাসওয়ার্ড দিয়েছিলেন)

### 3. প্রজেক্টের `includes/config.php` ফাইল আপডেট করা
আপনার প্রজেক্টের **`includes/config.php`** ফাইলটি ওপেন করুন এবং ৩-৭ নম্বর লাইনে আপনার মানগুলো বসিয়ে দিন:

```php
$db_mode = 'supabase'; // 'supabase' সিলেক্ট রাখুন

$supabase_host     = 'db.xxxxxxxxxxxx.supabase.co'; // আপনার Host নাম
$supabase_port     = '5432';
$supabase_db       = 'postgres';
$supabase_user     = 'postgres';
$supabase_password = 'YOUR_SUPABASE_PASSWORD';    // আপনার পাসওয়ার্ড
```

> **নোট:** আপনার XAMPP-এর PHP-তে PostgreSQL সাপোর্ট (`pdo_pgsql`) অলরেডি অটোমেটিক চালু করে দেওয়া হয়েছে!

---

## 💻 XAMPP MySQL বিকল্প গাইড (Local Machine Fallback)

যদি কখনও অফলাইনে কাজ করতে চান, তবে `includes/config.php` ফাইলে `$db_mode = 'mysql';` লিখে লোকাল XAMPP চালু করে কাজ করতে পারবেন।

### XAMPP লোকাল রান করতে:
1. XAMPP Control Panel-এ **Apache** এবং **MySQL** চালু করুন।
2. `http://localhost/phpmyadmin` এ গিয়ে `photography_db` ডাটাবেজে `sql/database.sql` ফাইল ইম্পোর্ট করুন।

---

## 🌐 ওয়েবসাইট ও অ্যাডমিন প্যানেল ব্যবহার

### 1. পাবলিক ওয়েবসাইট (Public Gallery)
👉 **`http://localhost/photograpy-project/`**

### 2. অ্যাডমিন প্যানেল (Admin Panel)
👉 **`http://localhost/photograpy-project/admin/login.php`**

- **Default Username:** `admin`
- **Default Password:** `admin123`

---

## 📂 ফাইল স্ট্রাকচার

```text
photograpy-project/
├── index.php                # গ্যালারি ও পোর্টফোলিও ওয়েবসাইট
├── README.md                # সম্পূর্ণ প্রজেক্ট গাইড
├── sql/
│   ├── supabase_schema.sql  # Supabase (PostgreSQL)-এর জন্য SQL ফাইল
│   └── database.sql         # XAMPP MySQL-এর জন্য SQL ফাইল
├── includes/
│   ├── config.php           # Supabase & MySQL সংযোগ কনফিগারেশন
│   ├── header.php           # ওয়েবসাইটের নেভিগেশন
│   └── footer.php           # ফুটার ও স্ক্রিপ্ট লিংক
├── admin/
│   ├── login.php            # অ্যাডমিন লগইন পেজ
│   ├── index.php            # অ্যাডমিন ড্যাশবোর্ড
│   ├── upload.php           # ফটো ও মেটাডাটা আপলোড পেজ
│   ├── categories.php       # ক্যাটাগরি ব্যবস্থাপনা
│   ├── photos.php           # ফটো এডিটর ও ফিচারড টগল
│   └── logout.php           # লগআউট
├── assets/
│   ├── css/style.css        # গ্লাসমরফিজম ডার্ক থিম ও এনিমেশন
│   └── js/main.js           # স্পিনার, ফিল্টার ও লাইটবক্স লজিক
└── uploads/                 # ফটো আপলোড ফোল্ডার
```

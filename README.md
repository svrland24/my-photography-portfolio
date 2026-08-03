# 📸 Aperture Vision - Photography Portfolio & Admin Panel

একটি আধুনিক, দৃষ্টিশক্তি কাড়ার মতো সুন্দর এবং রেসপন্সিভ **ফটোগ্রাফি পোর্টফোলিও ওয়েবসাইট** ও **সিকিউর অ্যাডমিন প্যানেল**। এটি সরাসরি **Supabase Cloud Database**-এর সাথে যুক্ত এবং **Vercel / GitHub**-এ ১০০% শূন্য-এরর (Zero Deployment Error) সহ ১-ক্লিকে লাইভ হোস্ট করার জন্য তৈরি।

> **Tech Stack:** HTML5 | Modern CSS3 (Glassmorphism & Dark Mode) | ES6+ JavaScript | Supabase Cloud Client | Vercel Native

---

## ⚡ প্রধান ফিচারসমূহ (Key Features)

1. **🎨 পাবলিক পোর্টফোলিও ওয়েবসাইট (`index.html`)**:
   - **Hero Showcase**: ফিচারড ছবি ও তার ক্যামেরা মেটাডাটা (ISO, Shutter speed, Lens, Aperture, Location)।
   - **Category Filters**: ক্লিকে ক্যাটাগরি অনুযায়ী ছবি ফিল্টারিং (Nature, Portrait, Street, Landscape, Architecture, Wildlife)।
   - **Live Search Bar**: ছবি, লোকেশন বা ক্যামেরা মডেল অনুযায়ী ইনস্ট্যান্ট সার্চ।
   - **EXIF Lightbox Modal**: ক্লিকে বড় সাইজে ছবি দেখার পাশাপাশি সম্পূর্ণ **EXIF Camera Specs**।
   - **Dark / Light Mode**: সুইচ করার সুবিধা।

2. **🔐 অ্যাডমিন প্যানেল (Admin Portal Modal)**:
   - **Passcode Protected**: ডিফল্ট PIN: **`admin123`**
   - **Photo Uploader**: পিসি থেকে সরাসরি ছবি ফাইল সিলেক্ট করে বা লিঙ্ক দিয়ে ক্যাটাগরি ও ক্যামেরা EXIF মেটাডাটা সহ ছবি আপলোড করা।
   - **Category Manager**: ইচ্ছামতো নতুন ক্যাটাগরি যোগ বা মুছে ফেলার সুযোগ।
   - **Photo Manager**: সকল ছবির তালিকা, Featured টগল এবং রিমুভ করার সুবিধা।

---

## 🚀 GitHub & Vercel-এ ১-ক্লিকে লাইভ করার নিয়ম

### ধাপ ১: GitHub-এ ফাইলগুলো পুশ করুন
CMD অপেন করে ১ লাইনের এই কমান্ডটি দিন:
```cmd
git add . && git commit -m "Initial commit of new Vercel-native portfolio" && git branch -M main && git push origin main
```

### ধাপ ২: Vercel-এ ডিপ্লয় করুন
1. **[Vercel.com](https://vercel.com)**-এ লগইন করুন।
2. **`Add New Project`** &rarr; আপনার GitHub repository **`my-photography-portfolio`** সিলেক্ট করে **`Deploy`** বাটনে ক্লিক করুন!
3. ৩ সেকেন্ডের মধ্যে Vercel আপনাকে ফ্রিতে লাইভ ডোমেইন লিঙ্ক দিয়ে দিবে (যেমন `https://my-photography-portfolio.vercel.app`)!

---

## 📂 ফাইল স্ট্রাকচার (Project Structure)

```text
photograpy-project/
├── index.html               # মূল পোর্টফোলিও ওয়েবসাইট ও অ্যাডমিন পোর্টাল
├── README.md                # সম্পূর্ণ প্রজেক্ট ও ডিপ্লয়মেন্ট গাইড
├── vercel.json              # Vercel-এর জন্য স্ট্যাটিক রুট কনফিগারেশন
├── package.json             # মেটাডাটা
├── sql/
│   └── supabase_schema.sql  # Supabase (PostgreSQL) ডাটাবেজ টেবিল
└── assets/
    ├── css/
    │   └── style.css        # ডার্ক গ্লাসমরফিজম স্টাইলিং ও এনিমেশন
    └── js/
        ├── config.js        # Supabase ক্রেডেনশিয়াল ও কনফিগারেশন
        ├── supabase.js      # Supabase ডাটা সার্ভিস ক্লায়েন্ট
        ├── main.js          # গ্যালারি ফিল্টারিং, সার্চ ও লাইটবক্স
        └── admin.js         # অ্যাডমিন লগইন, আপলোডার ও ক্যাটাগরি ম্যানেজার
```

---

## 🇬🇧 English Summary

**Aperture Vision** is an ultra-modern, visual-first Photography Portfolio & Admin Panel connected to Supabase Cloud Database. It features EXIF camera metadata display, dynamic category filtering, instant search, glassmorphic lightbox, and dark/light UI modes. Designed natively for zero-configuration 1-click deployments on Vercel.

# 🌌 AI Art Store (aiartstore.in)

> A premium, secure, and state-of-the-art e-commerce platform for showcasing and selling AI-generated digital art downloads. Built on CodeIgniter 4 and styled with a custom high-end dark theme.

---

## 🎨 Design & Aesthetics
The frontend uses a custom modern **cyberpunk-dark theme** built using custom CSS tokens and vanilla utilities on top of Bootstrap 5.3:
- **Color System**: Deep HSL space colors (Midnight black `#0a0a0f`, slate `#12121a`, royal purple accents `#8b5cf6`, and rich borders `#2a2a40`).
- **Visual Styles**: Glassmorphism layouts, glowing micro-animations, card hover transitions, and sticky blur headers.
- **Responsive Navigation**: Mobile bottom tab navigation drawer styled dynamically for handheld screens alongside desktop navigation.

---

## 🚀 Key Features

### 🛍️ Art Gallery & E-Commerce Catalog
- Dynamic product listings filterable by categories (Abstract, Sci-fi, Landscape, Nature, Anime, etc.).
- Secure watermarked image previews to protect original assets before purchase.
- Promo-code coupon discounts system with active validations on checkout.

### 💳 Razorpay Checkout & Webhooks
- Seamless transaction process using the Razorpay gateway.
- Automated payment validation and real-time webhook endpoints (`/razorpay/webhook`) to handle payment confirmation.

### 📦 Secure Digital Delivery
- Instant access to high-resolution downloads upon successful payment.
- Unique download links served via secure temporary tokens.
- Expiration and download count limit rules to prevent abuse.
- Complete Admin actions to manually revoke or reissue download access.

### 📣 Custom Landing Pages Engine
- Dynamic landing page builder (`/lp/{slug}`) designed for specific single-product marketing campaigns.
- Features custom YouTube hero background videos, count-down timers, and custom CTA redirection links.

### 📈 Feed Engines & SEO
- Google Analytics (`G-BY67JPBVPG`) integrated across all customer-facing layouts, login pages, and landing pages.
- Dynamically generated sitemap (`sitemap.xml`) for pages, posts, and products.
- Automated XML and CSV product feeds (`/feed/products.xml` & `/feed/products.csv`) optimized for Google Merchant Center, pre-populated with free digital shipping data for 47 targeted countries.

### 🔒 Admin Panel & Dashboard
- Full control center for Products, Categories, Orders, Users, Coupons, and Landing Pages.
- Real-time dashboard statistics detailing orders, total sales, and user counts.

---

## 🛠️ Technology Stack
- **Backend Framework**: PHP 8.2+ (CodeIgniter 4.x)
- **Database**: MySQL 5.7 / 8.0+
- **Authentication**: CodeIgniter Shield (custom login overrides) + Google OAuth integration.
- **Frontend**: Custom Vanilla CSS + Bootstrap 5.3 + jQuery & Toastify.js.
- **APIs & SDKs**: Google API Client (OAuth), Razorpay Webhooks.

---

## 📂 Project Structure

```
aiartstore.in/
├── app/
│   ├── Config/          # Application configurations (Auth, Filters, Routes, etc.)
│   ├── Controllers/     # Logic handlers (Admin, Shop, Checkout, GoogleAuth, Sitemap, etc.)
│   ├── Filters/         # Custom request filters (e.g. RedirectAfterLoginFilter)
│   ├── Models/          # Database interaction entities (Product, Order, Download, etc.)
│   └── Views/           # Layouts, Admin Dashboard, Pages, and Shop templates
├── public/
│   ├── index.php        # Server front controller
│   ├── uploads/         # Watermarked previews and static assets directory
│   └── .htaccess        # Apache request rewrites and security hardening configs
├── writable/            # Dynamic files, sessions, caching, and logs
└── spark                # CLI command runner for CodeIgniter 4
```

---

## 💻 Local Installation Guide

1. **Clone the Repository**:
   ```bash
   git clone https://github.com/stitpragya22/aiartstore.in.git
   cd aiartstore.in
   ```

2. **Configure Environment Variables**:
   Copy `env` to `.env` and fill out your database, Razorpay, Google Auth, and mail server credentials:
   ```bash
   cp env .env
   ```

3. **Install Composer Dependencies**:
   ```bash
   composer install
   ```

4. **Run Migrations & Seeders**:
   Set up your database tables and seed sample tags/blog content:
   ```bash
   php spark migrate
   php -f seed_blog.php
   ```

5. **Start Dev Server**:
   ```bash
   php spark serve
   ```
   Open `http://localhost:8080` in your browser.

---

## 🌐 Production Deployment

- **Web Root Configuration**: Set the server web document root to point to the `public/` directory rather than the root directory for security.
- **Google Merchant Center**: Submit the feed URL `https://yourdomain.com/feed/products.csv` as a scheduled fetch. Shipping rules for the 47 major target countries are preconfigured.
- **Analytics**: Real-time traffic data will flow into Google Analytics using tag `G-BY67JPBVPG`.

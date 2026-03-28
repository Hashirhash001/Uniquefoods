# 🛒 Unique Foods — E-Commerce Platform

A full-featured e-commerce web application built with **Laravel** for selling food products online. Unique Foods provides a seamless shopping experience for customers and a powerful admin dashboard for store management.

---

## 🚀 Tech Stack

| Layer | Technology |
|---|---|
| Backend | PHP / Laravel |
| Frontend | Blade Templates, jQuery, AJAX, Tailwind CSS |
| Database | MySQL |
| Payment | Stripe |
| Storage | AWS S3 / Local |
| Deployment | Hostinger / AWS |

---

## ✨ Features

### 🛍️ Customer-Facing

- **Product Catalog** — Browse food products with categories, filters, and search
- **Product Detail Pages** — Rich product descriptions, images, and pricing
- **Shopping Cart** — Add/remove items, update quantities with AJAX — no page reload
- **Secure Checkout** — Multi-step checkout with address, shipping, and payment
- **Stripe Payment Integration** — Secure online payments via Stripe
- **Order Tracking** — Real-time order status tracking
- **User Authentication** — Register, login, and manage personal accounts
- **Order History** — View past orders and their statuses
- **Responsive Design** — Fully mobile-friendly UI across all screen sizes
- **Group-Based Pricing** — Customers see personalised product catalogues and prices based on their assigned group

### 🔧 Admin Dashboard

- **Product Management** — Add, edit, delete products with image uploads
- **Category Management** — Organise products into categories
- **Order Management** — View, update, and manage all customer orders
- **Customer Management** — View registered users and their order history
- **Inventory Control** — Manage stock availability per product
- **Sales Analytics** — Dashboard with order stats and revenue overview
- **Customer Groups Management** — Create groups with exclusive product catalogues and assigned customers
- **Discount Management** — Create percentage or fixed-amount discounts with optional minimum order amounts
- **Time-Limited Offers** — Schedule price offers on individual products, entire categories, or all brand products

---

## 👥 Customer Groups Management

The platform supports a powerful **Customer Groups** system that allows the admin to segment customers and control what they see and pay.

### Group Features
- Create named customer groups and assign specific customers to each group
- Each group has its **own exclusive product catalogue** — customers only see products assigned to their group
- Assign **group-specific pricing** per product, independent of the standard product price
- Manage group membership — add or remove customers from groups at any time
- Customers not assigned to any group see the default public catalogue

### Use Cases
> - **Wholesale vs Retail** — different price tiers for different buyer types
> - **Region-specific catalogues** — show only products available in a customer’s area
> - **VIP / Membership pricing** — exclusive discounts for premium customers

---

## 🏷️ Discount & Offers Management

The admin panel includes a full-featured **price management system** with two tools: Discounts and Time-Limited Offers.

### Discounts

Create reusable discount rules applied at checkout:

| Field | Description |
|---|---|
| **Discount Type** | `Percentage (%)` or `Fixed Amount (£)` |
| **Discount Value** | The percentage or fixed amount to deduct |
| **Minimum Order Amount** | Optional threshold — discount only applies if cart total meets this value |
| **Status** | `Active` or `Inactive` — toggle discounts on/off without deleting |

**Example:** 15% off all orders above £50, or £10 flat off any order.

---

### Time-Limited Offers

Schedule promotional prices with a defined start and end date:

| Field | Description |
|---|---|
| **Scope** | `Single Product`, `Entire Category`, or `All Brand Products` |
| **Select Product** | Choose the specific product (for single product scope) |
| **Regular Price** | Displays the current price for reference |
| **Offer Price** | The discounted price active during the offer window |
| **Start Date** | Date when the offer becomes active |
| **End Date** | Date when the offer expires and price reverts |

**Example:** Run a weekend flash sale at £12.99 (regular £19.99) on a specific product from Friday to Sunday. Or apply a 20% reduced offer price across an entire category for a seasonal promotion.

---

## 📸 Screenshots

### Product Listing
![Product Listing](docs/screens/shop.png)

### Shopping Cart
![Shopping Cart](docs/screens/cart.png)

### Checkout Page
![Checkout](docs/screens/checkout.png)

### Admin Dashboard
![Admin Dashboard](docs/screens/dashboard.png)

### Discount & Offers Management
![Discount & Offers Management](docs/screens/offers1%20(1).png)

### Discount & Offers Management
![Discount & Offers Management](docs/screens/offers2.png)

### Categories Management
![Categories management](docs/screens/categories.png)

### Order Management
![Order Management](docs/screens/orders.png)

### Customers Management
![Customers Management](docs/screens/customers.png)

---

## 📦 Integrations

### 💳 Stripe
Handles secure payment processing for all customer orders. Supports card payments with webhook-based order confirmation.

---

## ⚙️ Installation & Setup

### Prerequisites
- PHP >= 8.1
- Composer
- MySQL
- Node.js & npm

### Steps

```bash
# Clone the repository
git clone https://github.com/Hashirhash001/Uniquefoods.git
cd Uniquefoods

# Install PHP dependencies
composer install

# Install frontend dependencies
npm install && npm run build

# Copy and configure environment
cp .env.example .env
php artisan key:generate

# Configure your .env (DB, Stripe, Mail credentials)

# Run migrations
php artisan migrate

# Link storage
php artisan storage:link

# Start development server
php artisan serve
```

---

## 🔑 Environment Variables

Key variables to configure in your `.env` file:

```env
APP_NAME="Unique Foods"
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost

# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=uniquefoods
DB_USERNAME=root
DB_PASSWORD=

# Mail
MAIL_MAILER=smtp
MAIL_HOST=your_mail_host
MAIL_PORT=587
MAIL_USERNAME=your_mail_username
MAIL_PASSWORD=your_mail_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@uniquefoods.com
MAIL_FROM_NAME="Unique Foods"

# AWS S3 (for image storage)
AWS_ACCESS_KEY_ID=your_aws_access_key
AWS_SECRET_ACCESS_KEY=your_aws_secret_key
AWS_DEFAULT_REGION=ap-south-1
AWS_BUCKET=your_s3_bucket_name

# Stripe Payment Gateway
STRIPE_KEY=your_stripe_publishable_key
STRIPE_SECRET=your_stripe_secret_key
STRIPE_WEBHOOK_SECRET=your_stripe_webhook_secret
```

---

## 📁 Project Structure

```
Uniquefoods/
├── app/
│   ├── Http/Controllers/   # Admin & Frontend Controllers
│   ├── Models/             # Eloquent Models
│   └── Services/           # Stripe Services
├── database/
│   └── migrations/         # Database schema
├── resources/
│   └── views/              # Blade templates (frontend + admin)
├── routes/
│   └── web.php             # Application routes
└── public/                 # Assets
```

---

## 📄 License

This project is proprietary software. All rights reserved.

---

> Built with ❤️ using Laravel — [Hashirhash001](https://github.com/Hashirhash001)

# Stark Brokers — Real Estate Platform 🏡

A focused, production-ready MVP built with Laravel (backend) and React (frontend) for property listings, bookings, and admin management.

[API Docs](https://documenter.getpostman.com/view/29185004/2sAYHwHPqT) • Local screenshots: `./screenshots/`

---

## Quick Links & Badges

[![PHP](https://img.shields.io/badge/PHP-^8.2-8892BF)](https://www.php.net/) [![Laravel](https://img.shields.io/badge/Laravel-11.x-EF2D2D)](https://laravel.com/) [![React](https://img.shields.io/badge/React-18.2.0-61DAFB)](https://reactjs.org/) [![Tailwind](https://img.shields.io/badge/Tailwind-3.x-38B2AC)](https://tailwindcss.com/) [![License: MIT](https://img.shields.io/badge/License-MIT-green.svg)](./LICENSE)

---

## What is this?
Lightweight real-estate MVP: public listing and search, owner listing management, bookings, admin panel, and REST APIs. Built to be easy to run locally and extend for production.

---

## Features (complete)

Below is a grouped, developer-friendly feature summary representing the functionality implemented or implied by the codebase and project packages.

- Public / Visitor
  - 🔎 Browse listings with server-side filtering (status, type, price, location)
  - 🗺️ Map view with property markers (Google Maps integration via `@react-google-maps/api`)
  - 🖼️ Property details page with image gallery and key info (rooms, size, amenities)
  - ❤️ Favorites / wishlist (save properties for later)
  - 📄 Static pages: FAQ, Contact Us, About

- Authentication & Accounts
  - 🔐 OTP-based registration and login (Twilio integration for SMS verification)
  - 📧 Email notifications and transactional mail (Laravel Mail)
  - 🛡️ Sanctum-based API authentication (cookies or token flows)
  - 👥 Role & permission management using Spatie (owner, renter, admin)

- Owner (Property Manager) features
  - 🏠 Create, read, update, delete property listings and units
  - 📸 Upload and manage multiple images per property
  - 🔁 Change unit status (available, booked, blocked) and track history
  - 📅 Manage bookings, accept/reject requests and view booking details
  - 🔔 Notifications for new bookings or messages

- Renter / Customer features
  - 🔍 Search and filter properties, view details and gallery
  - 📅 Request bookings / schedule tours
  - 💬 Contact owner or support via contact form
  - ✅ View booking requests and status

- Admin / Moderation
  - ⚙️ Manage site settings, FAQs, categories, and features
  - 👤 User management (view, change roles, ban/unban)
  - 📝 Moderate properties and bookings (approve, flag, remove)
  - 📊 Basic dashboards and runtime statistics

- Platform, Integrations & Developer features
  - 🌐 Localization / i18n (Arabic and English via `mcamara/laravel-localization`)
  - 📱 Responsive frontend built with React + Tailwind (Vite-powered)
  - 📦 API-first design with a versioned API (`/api/v1/*`) and Postman docs
  - 🔁 Queue workers and background jobs (queues configured in Laravel)
  - 🔒 Permission-aware endpoints and request validation (Form Requests)
  - 📁 Helpers and centralized services (`app/Services`, `app/Helpers`) for business logic

- Utilities & UX
  - 🔔 In-app and email notifications (Toastr + Mail)
  - ♻️ Image storage and public asset management (`public/storage` or configured filesystem)
  - 🔎 Search-friendly URLs and SEO-ready metadata on property pages

This list is concise but complete for practical development and extension. If you want, I can expand any category into implementation notes and point to exact files to edit.

---

## Quick Installation

Prerequisites: PHP >= 8.2, Composer, Node.js (>=18), npm, MySQL/MariaDB.

One-line (dev):

```powershell
composer install; copy .env.example .env; php artisan key:generate; php artisan migrate --seed; npm install; npm run dev; php artisan serve
```

Notes:
- Update `.env` DB credentials before running migrations.
- On non-Windows shells use `cp` instead of `copy`.

---

## Quick Start (development)

1. `composer install`
2. `npm ci`
3. `copy .env.example .env` (or `cp .env.example .env`)
4. Edit `.env` DB settings
5. `php artisan migrate --seed`
6. `npm run dev` and `php artisan serve`

Open `http://127.0.0.1:8000` (default) to view the app.

---

## API Reference (short)

Full Postman docs: https://documenter.getpostman.com/view/29185004/2sAYHwHPqT

| Method | Endpoint              | Auth | Purpose                                         |
|-------:|:----------------------|:----:|-------------------------------------------------|
|    GET | /api/v1/units         |  No  | List properties (filter query params supported) |
|    GET | /api/v1/units/{id}    |  No  | Property details                                |
|    GET | /api/v1/setting       |  No  | App settings                                    |
|   POST | /api/v1/auth/login    |  No  | Login (Sanctum)                                 |
|   POST | /api/v1/auth/register |  No  | Register (owner/tenant)                         |
|   POST | /api/v1/bookings      | Yes  | Create booking (authenticated)                  |

Example (Axios):

```js
// axios instance + token
axios.get(`${API_BASE}/units`).then(res => console.log(res.data))
```

---

## Project layout (short)

- `app/` — Models, Controllers, Services
- `routes/` — `api.php`, `web.php`
- `resources/js/` — React app (Vite)
- `resources/views/` — Inertia / Blade entries
- `database/migrations/`, `seeders/`
- `screenshots/` — UI images and ERD

Edit here:
- Controllers & requests: `app/Http/Controllers/` and `app/Http/Requests/`
- Frontend components/pages: `resources/js/components/` and `resources/js/pages/`

---

## Authentication

- Uses Laravel Sanctum (SPA / token flows).
- Role & permission via Spatie (`HasRoles` on User model).
- OTP flows use Twilio — check `app/Mail` and relevant controllers for verification logic.

Sanctum tip (SPA): ensure `config/cors.php` allows the frontend origin and use `credentials: 'include'` in requests.

---

## Screenshots

Desktop: Home and Dashboard (first, side-by-side):

| Home | Dashboard |
|---|---|
| ![Home](./screenshots/home.png) | ![Dashboard](./screenshots/dashboard.png) |

Other useful screenshots: `./screenshots/home-1.png`, `./screenshots/home-2.png`, `./screenshots/details.png`, `./screenshots/booking.png`, `./screenshots/ERD.png`.

---

## Testing & Deployment (high level)

- Tests: run Pest (`./vendor/bin/pest`).
- Build frontend: `npm run build`.
- Production checklist: set env, `composer install --no-dev`, `npm ci && npm run build`, `php artisan migrate --force`, `php artisan config:cache`, run queue workers with Supervisor.

---

## Support & License

API docs and collection: https://documenter.getpostman.com/view/29185004/2sAYHwHPqT

License: MIT (declared in `composer.json`).

For quick help, check `routes/api.php`, `app/Http/Controllers/`, and `resources/js/pages/` for entry points.

---

If you want this README adjusted further (shorter, or changed language), tell me which sections to trim.

---

Author: Mostafa Yehia

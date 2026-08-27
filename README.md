# RentDirect Marketplace

Production-focused, self-hosted house rental marketplace for:

- **Mobile App:** Flutter (Android + iOS)
- **Admin Panel:** React + TypeScript + Vite
- **Backend API:** Laravel + MySQL

This repository now contains **implemented scaffolds + core APIs**:

1. Laravel API project with Sanctum auth + RBAC base
2. Core property and admin CRUD APIs
3. React Admin Panel implementation (Vite + TS + Query + RHF + Zod + i18n)
4. Flutter app scaffold with Material 3 + Riverpod + GoRouter
5. System architecture, schema and self-hosting documentation

## Deployment Status

- **GitHub deployment:** Pushed to branch `meeralin-rentdirect-platform`.
- **Live preview website:** Published via GitHub Pages (UI preview).
- **Full API + Admin runtime:** implemented in code and runnable locally/self-hosted.

## Repository Layout

```text
backend/
  api/
    openapi.yaml
  database/
    rentdirect_schema.sql
  laravel-api/
    app/
    routes/api.php
    database/
admin/
  react-admin/
mobile/
  flutter-app/
docs/
  system-architecture.md
deploy/
  self-hosting-guide.md
```

## Product Goals

- Direct owner-to-tenant discovery (no rental commission)
- Free posting and search
- Revenue from sponsored placements, promotions and ads
- Multi-language support with English fallback
- Strong server-side authorization, validation and auditing

## Run Instructions

### 1) Laravel API (`backend/laravel-api`)

1. Install PHP 8.3+, Composer and MySQL.
2. Copy env and configure DB:
   - `cp .env.example .env`
   - set `DB_CONNECTION=mysql` credentials in `.env`
3. Install packages:
   - `composer install`
4. Generate app key and migrate:
   - `php artisan key:generate`
   - `php artisan migrate --seed`
5. Run API:
   - `php artisan serve`
6. Base URL:
   - `http://127.0.0.1:8000/api/v1`

Default seeded admin:

- Email: `admin@rentdirect.test`
- Password: `Admin@12345`

### 2) React Admin (`admin/react-admin`)

1. Create env:
   - copy `.env.example` to `.env`
2. Install packages:
   - `npm install`
3. Start:
   - `npm run dev`
4. Admin URL:
   - `http://127.0.0.1:5173`

### 3) Flutter App (`mobile/flutter-app`)

1. Install Flutter SDK.
2. In `mobile/flutter-app`:
   - `flutter create .`
   - `flutter pub get`
   - `flutter run`

### 4) Deployment

- cPanel/VPS deployment guide: `deploy/self-hosting-guide.md`
- Architecture reference: `docs/system-architecture.md`

## Clickable Links

- **Repository:** https://github.com/MeeraliN/JustTrial
- **Live Preview Website:** https://meeralin.github.io/JustTrial/
- **API (planned production):** https://api.example.com
- **Admin (planned production):** https://admin.example.com
- **Public Website (planned production):** https://example.com
- **Releases (for future APK/EXE uploads):** https://github.com/MeeraliN/JustTrial/releases
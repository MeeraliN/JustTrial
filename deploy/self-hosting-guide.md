# Self-Hosting Guide (cPanel + VPS)

## Domain Mapping

- `api.example.com` -> Laravel API
- `admin.example.com` -> React Admin Panel
- `example.com` -> Public pages/property share pages

## 1) Shared Hosting / cPanel Baseline

1. Create subdomains in cPanel:
   - `api.example.com`
   - `admin.example.com`
2. Create MySQL database and user with strong password.
3. Upload Laravel API source to a directory outside `public_html` where possible.
4. Point API document root to Laravel `public/`.
5. Configure `.env`:
   - `APP_ENV=production`
   - `APP_DEBUG=false`
   - DB credentials
   - mail credentials
   - queue/cache/session drivers
6. Set writable permissions for:
   - `storage/`
   - `bootstrap/cache/`
7. Configure cron for Laravel scheduler:
   - `* * * * * php /path/to/artisan schedule:run`
8. Configure queue worker via host-supported process manager or recurring cron fallback.
9. Upload React admin build (`dist/`) to `admin.example.com` document root.
10. Configure SPA routing fallback for admin app to `index.html`.
11. Install SSL certificates for all domains and force HTTPS.

## 2) VPS Baseline (Recommended for Scale)

1. Install stack:
   - Nginx
   - PHP 8.2+ and required extensions
   - MySQL 8+
   - Redis (optional but recommended)
   - Supervisor for queue workers
2. Create virtual hosts:
   - API host for `api.example.com`
   - Admin static host for `admin.example.com`
   - Public host for `example.com`
3. Deploy Laravel API:
   - configure environment variables
   - run migrations and seeders
   - cache config/routes/views
4. Deploy React admin:
   - build artifact (`npm run build`)
   - serve static files through Nginx
5. Configure background workers:
   - queue worker under Supervisor
   - scheduler cron (`schedule:run`)
6. Harden server:
   - firewall
   - fail2ban
   - regular updates
   - DB backup schedule
   - HTTPS auto-renew

## 3) Storage and Media

- Store uploaded property images on server-managed storage.
- Generate compressed versions and thumbnails in backend pipeline.
- Enforce upload limits by MIME, dimension and size.

## 4) Security Checklist

- Disable debug mode in production
- Keep secrets only in environment variables
- Restrict CORS to trusted app/admin domains
- Apply rate limits to auth, search, AI and chat endpoints
- Rotate API and third-party keys
- Maintain immutable audit log retention

## 5) Backups and Recovery

- Daily MySQL backups with retention policy
- Media backup schedule
- Quarterly restore drill in staging environment

# RentDirect Marketplace

Production-focused, self-hosted house rental marketplace for:

- **Mobile App:** Flutter (Android + iOS)
- **Admin Panel:** React + TypeScript + Vite
- **Backend API:** Laravel + MySQL

This repository currently contains **Phase 1** deliverables:

1. System architecture and security baseline
2. MySQL schema design
3. Initial REST API contract (OpenAPI)
4. Self-hosting deployment guide (cPanel/shared hosting and VPS)

## Deployment Status

- **GitHub deployment:** Pushed to branch `meeralin-rentdirect-platform`.
- **Live preview website:** Published via GitHub Pages (static concept preview, not full app runtime).

## Repository Layout

```text
backend/
  api/
    openapi.yaml
  database/
    rentdirect_schema.sql
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

## Planned Build Sequence

1. Planning and database design
2. Laravel API
3. Authentication and permissions
4. React Admin Panel
5. Flutter application
6. Listings, search and maps
7. Chat and notifications
8. Ads, promotions and AI
9. Testing, security and deployment

## How to Run What Exists in This Repo

This repository is currently **Phase 1 design artifacts**. You can run/use it as follows:

1. Clone the repository:
   - `git clone https://github.com/MeeraliN/JustTrial.git`
2. Import the database schema (MySQL 8+):
   - `mysql -u <db_user> -p < backend/database/rentdirect_schema.sql`
3. Open API contract:
   - Use `backend/api/openapi.yaml` in Swagger Editor or Postman import.
4. Follow deployment blueprint:
   - `deploy/self-hosting-guide.md`
5. Review architecture:
   - `docs/system-architecture.md`

## Clickable Links

- **Repository:** https://github.com/MeeraliN/JustTrial
- **Live Preview Website:** https://meeralin.github.io/JustTrial/
- **Planned API URL:** https://api.example.com
- **Planned Admin URL:** https://admin.example.com
- **Planned Public Website:** https://example.com
- **Releases (for future APK/EXE uploads):** https://github.com/MeeraliN/JustTrial/releases
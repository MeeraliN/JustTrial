# RentDirect System Architecture

## 1) Deployment Topology

- `api.example.com` -> Laravel REST API + Sanctum + queue worker + scheduler
- `admin.example.com` -> React Admin Panel static build served by web server
- `example.com` -> Public property pages + share pages
- MySQL on self-hosted environment
- Media files stored on self-hosted filesystem/object-compatible storage

## 2) High-Level Modules

1. **Identity and Access**
   - Email/password login
   - Email verification
   - Google Sign-In and Apple Sign-In
   - Optional phone OTP
   - Staff RBAC using Spatie Laravel Permission
2. **Property Lifecycle**
   - Create/edit listings
   - Moderation and approval workflow
   - Active/rented/expired state handling
3. **Discovery**
   - Filter/search/map queries
   - Sponsored and verified ranking
   - Saved searches and alerts
4. **Communication**
   - In-app chat (text + image)
   - Viewing requests
   - Report/block controls
5. **Monetization**
   - Featured placements
   - Business promotions
   - Manual payment verification (UPI)
6. **Localization**
   - Language management in DB
   - Dynamic content translations
   - Locale-aware formatting defaults (INR, regional date/time)
7. **AI Service Layer**
   - Gemini calls only from backend
   - Per-user and monthly quotas
   - Feature toggles and fallback responses
8. **Governance and Security**
   - Immutable audit logs
   - Rate limiting + restricted CORS
   - Soft deletes and restore flows

## 3) Mobile + Admin Client Boundaries

- Flutter and React clients only call Laravel API.
- No direct MySQL access from Flutter/React.
- Auth tokens, API keys and AI credentials remain server-side.

## 4) Security Baseline

- HTTPS everywhere
- Strict request validation on all write endpoints
- Server-side permission checks for every admin action
- Secure upload validation (MIME, size, dimensions)
- Hash passwords using modern algorithm (Laravel default)
- Session/token revocation support
- Immutable audit records for staff actions

## 5) Extensibility Strategy

- Dynamic property fields, amenities, categories and languages stored in DB
- Admin can manage metadata without mobile app release
- Translation table model supports adding Marathi/Gujarati/Bengali/Tamil/Telugu later
- AI features isolated behind backend service + usage ledgers

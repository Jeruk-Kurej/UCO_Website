# UCO Website - AI Agent Instructions

## Project Overview
This is a Laravel 12 + Vite application for managing UC (University) alumni businesses, testimonials, and student data. The platform includes **public business browsing** with **guest access** and authenticated admin/student features with AI-powered content moderation.

## Tech Stack
- **Backend**: Laravel 12 (PHP 8.2+), Pest (testing), Laravel Breeze (auth)
- **Frontend**: Vite, Alpine.js, Tailwind CSS 3
- **Database**: MySQL/PostgreSQL (migrations-driven schema)
- **AI**: Google Gemini API via `google-gemini-php/laravel`
- **Import**: Maatwebsite Excel for bulk user/business imports

## Architecture Patterns

### Role-Based Access Control
**Four user types**: `guest` (unauthenticated), `student`, `alumni`, `admin` (stored in `users.role`)

- **Public routes (guest access)**: 
  - `/businesses` - Browse all businesses
  - `/businesses/{id}` - View business details, products, services, photos, contacts
  - `/business-types` - View business categories
  - `/contact-types` - View contact types
  - `/uc-testimonies` - View and submit testimonies (guests + authenticated non-admins)
  
- **Authenticated routes**: Dashboard, profile, CRUD operations (middleware: `auth`, `verified`)
  - Students/Alumni can create/edit their own businesses
  - Testimonies auto-fill user's name when logged in
  
- **Admin-only routes**: User management, imports, business type management (middleware: `auth`, `verified`, `admin`)
  - Admins cannot submit testimonies (business logic restriction)

- Custom middleware: `IsAdmin` ([app/Http/Middleware/IsAdmin.php](app/Http/Middleware/IsAdmin.php)) checks `$user->isAdmin()`
- Policies: [BusinessPolicy.php](app/Policies/BusinessPolicy.php) - **public viewing for all**, owner/admin editing

### Business Model (`business_mode` field)
Businesses support three modes: `product`, `service`, or `both`
- Check with: `$business->isProductMode()`, `$business->isServiceMode()`, `$business->isBothMode()`
- Forms/controllers validate mode before showing product/service fields

### JSON Data Storage Pattern
User model ([User.php](app/Models/User.php)) uses 5 JSON columns for flexible data:
- `personal_data` - gender, addresses, social media
- `academic_data` - education history, advisor
- `father_data` / `mother_data` - parent information
- `graduation_data` - employment, business info
- Access JSON fields: `extended_data->nis` in queries

### Excel Import Workflow
Critical: **Import users FIRST, then businesses**
1. **Users Import**: [UsersImport.php](app/Imports/UsersImport.php)
   - Required: `name`, `email` (unique)
   - Auto-generates `username`, default password: `password123`
   - Skips duplicates by email
   - Maps columns flexibly: `prodi`/`jurusan`/`major` → `Major`

2. **Businesses Import**: [BusinessesImport.php](app/Imports/BusinessesImport.php)
   - Required: business name + owneror `getAuthUserOrNull()` helper for type-safe auth:
```php
// For routes requiring authentication
private function getAuthUser(): User {
    /** @var User $user */
    $user = Auth::user();
    if (!$user) abort(401, 'Unauthenticated.');
    return $user;
}

// For routes allowing guest access
private function getAuthUserOrNull(): ?User {
    /** @var User|null $user */
    $user = Auth::user();
    return $user;
}
```

### Public Access Pattern
- Business views: Accessible to all (guests + authenticated)
- Testimonies: Guests can view and submit, logged users have name auto-filled, admins blocked from submitting
- Navigation: Shows login/register buttons for guests, profile dropdown for authenticated users
## Key Conventions

### Controller Patterns
All controllers use `getAuthUser()` helper for type-safe auth:
```php
private function getAuthUser(): User {
    /** @var User $user */
    $user = Auth::user();
    if (!$user) abort(401, 'Unauthenticated.');
    return $user;
}
```

- **Guest submissions are moderated the same way as authenticated user submissions**
### Search Implementation
Search implemented in 3 list pages ([SEARCH_FEATURE.md](SEARCH_FEATURE.md)):
- User list: searches `name`, `email`, `username`, `extended_data->nis`
- Business list: searches business name, description, owner name, business type (via relationships)
- Uses `LIKE "%{$search}%"` queries with `orWhere` for multiple fields
- Preserves pagination + query params (`search`, `my`)

### AI Content Moderation
[AiModerationService.php](app/Services/AiModerationService.php) analyzes UC testimonies:
- Uses Google Gemini (model from `GEMINI_MODEL` env, default: `gemini-2.5-flash`)
- Returns: `sentiment_score`, `is_approved`, `rejection_reason`
- Debug mode: Set `GEMINI_DEBUG=true` or `APP_DEBUG=true` to log raw responses
- Handles both plain JSON and markdown-wrapped responses

### Custom Color Palette
[tailwind.config.js](tailwind.config.js) defines brand colors:
- Primary: `uco-orange` (50-900 scale, main: `#ff8c2e`)
- Secondary: `uco-yellow` (50-900 scale, main: `#ffd633`)
- Use these instead of default Tailwind oranges/yellows

## Development Workflow

### Setup
```bash
composer run setup  # Installs deps, copies .env, generates key, migrates, builds assets
```

### Run Dev Environment
```bash
composer run dev  # Runs: php artisan serve + queue:listen + npm run dev (concurrently)
```
Or manually:
- Server: `php artisan serve`
- Queue: `php artisan queue:listen --tries=1`
- Assets: `npm run dev`

### Testing
```bash
composer run test  # Clears config cache, runs Pest tests
```

### Database Management
- Migrations: `php artisan migrate`
- Reset DB (admin only, temp route): `/admin/reset-database-confirm`

## Common Gotchas

### Excel Import Issues
- **"Duplicate key error"**: Import removes `id` column - DO NOT import Excel with ID values
- **"Student data detected"**: BusinessesImport rejects rows with 3+ student columns (nis, prodi, angkatan, etc.)
- **"No owner found"**: Business needs matching user by email/name before import

### Authorization
- Use `$user->isAdmin()` method, not direct role checks
- Forms for owners: check `$user->id === $business->user
- **Guest access**: Check `auth()->check()` before accessing user properties

### Public vs Authenticated Views
- Business pages: Show all content to guests, show edit/delete buttons only to owners/admins
- Testimony form: Auto-fill name field for logged-in users (readonly), allow manual entry for guests
- Navigation: Conditionally show login/register or profile dropdown based on auth status_id || $user->isAdmin()`
- Policies handle public vs authenticated vs admin logic

### Relationships
- Business → User (owner), BusinessType, Products, Services, Photos, Contacts
- Load with: `Business::with(['user', 'businessType', 'products', 'photos'])`

### Queue Processing
TePolicies: [BusinessPolicy.php](app/Policies/BusinessPolicy.php) (handles public access)
- stimony AI analysis uses queues - ensure queue worker runs (`php artisan queue:listen`)

## File References
- Routes: [routes/web.php](routes/web.php) (clearly sectioned: public, auth, admin)
- Main models: [Business.php](app/Models/Business.php), [User.php](app/Models/User.php)
- Import logic: [UsersImport.php](app/Imports/UsersImport.php), [BusinessesImport.php](app/Imports/BusinessesImport.php)
- Detailed guides: [IMPORT_GUIDE.md](IMPORT_GUIDE.md), [SEARCH_FEATURE.md](SEARCH_FEATURE.md), [EXCEL_IMPORT_GUIDE.md](EXCEL_IMPORT_GUIDE.md)

## Cloudinary & Deployment Notes

- Storage: The app uses Laravel Filesystems. We configure `FILESYSTEM_DISK=cloudinary` for persistent image storage.
- Required environment variables (set these in Railway / deployment environment):
  - `CLOUDINARY_CLOUD_NAME`
  - `CLOUDINARY_API_KEY`
  - `CLOUDINARY_API_SECRET`
  - `FILESYSTEM_DISK=cloudinary`

- Railway deployment guidance:
  1. Add the Cloudinary env vars to your Railway project settings (do NOT commit secrets to the repo).
  2. The `railway.json` includes a `postDeploy` step to clear config/cache and run migrations. Ensure the Railway build/run supports these commands.
  3. The app no longer relies on `storage/app/public` for runtime assets — Cloudinary serves uploaded images.

- Migration of existing local files (recommended):
  - Create an Artisan command or a one-off script to iterate `storage/app/public` and upload files to Cloudinary, then update DB rows to the Cloudinary public id (path) used by your models (`profile_photo_url`, `photo_url`, `logo_url`, etc.).
  - After migration, verify pages (businesses index/show, profile) render images.

- Runtime checks: On startup the deploy script runs `php artisan config:clear` and `php artisan cache:clear`. If you need an explicit Cloudinary connectivity check, add a lightweight Artisan command that calls `Storage::disk('cloudinary')->exists('some-known-public-id')` and returns success/failure; wire that into a healthcheck endpoint.

If you want, I can add a migration command/script to upload existing local files to Cloudinary and patch DB entries automatically — say the word and I'll scaffold it.

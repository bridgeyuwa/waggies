# Waggies

Luxury pet care website for Waggies (Abuja), built with Laravel 13, Filament 5, Livewire 4, and Tailwind CSS v4.

## Requirements

- PHP 8.4+
- [Laravel Herd](https://herd.laravel.com/) (recommended) or compatible web server
- Composer
- Node.js 20+

## Local setup

1. Clone the repository and install dependencies:

```bash
composer install
npm install
```

2. Copy the environment file and configure admin credentials:

```bash
cp .env.example .env
php artisan key:generate
```

Set at minimum:

- `APP_NAME=Waggies`
- `ADMIN_EMAIL`, `ADMIN_PASSWORD`, `ADMIN_NAME` — used by the seeder to create the Filament admin user
- `ADMIN_EMAILS` — comma-separated emails allowed to access `/admin` outside `local`
- `MAIL_FROM_ADDRESS` — sender for enquiry notifications

3. Run migrations and seed demo content:

```bash
php artisan migrate
php artisan db:seed
```

4. Build frontend assets:

```bash
npm run build
```

For active development, use `npm run dev` or `composer run dev`.

## URLs

With Herd, the site is served at `https://waggies.test` (project directory name).

- Public site: `/`
- Filament admin: `/admin` — log in with the seeded admin user (`ADMIN_EMAIL` / `ADMIN_PASSWORD`)

## Useful commands

```bash
php artisan test              # Pest test suite
vendor/bin/pint --dirty       # Format changed PHP files
php artisan sitemap:generate    # Regenerate public/sitemap.xml
```

## CI

GitHub Actions runs Pint, PHPUnit/Pest tests, and `npm run build` on push and pull requests.

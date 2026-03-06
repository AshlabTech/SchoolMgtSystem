# School Management System

A Laravel 12 + Inertia.js (Vue 3) school management application for managing students, classes, results, fees, report cards, timetable data, and related academic records.

## Tech Stack

- PHP 8.2+
- Laravel 12
- Inertia.js v2
- Vue 3 + TypeScript
- Tailwind CSS v4
- Vite
- MySQL or SQLite (Laravel-supported databases)

## Project Setup

### 1. Clone and enter the project

```bash
git clone <your-repo-url>
cd SchoolMgtSystem
```

### 2. Install dependencies

```bash
composer install
npm install
```

### 3. Configure environment

```bash
cp .env.example .env
php artisan key:generate
```

Update your `.env` database settings if needed.

### 4. Run migrations and seed database

```bash
php artisan migrate
php artisan db:seed
```

### 5. Build or run frontend assets

For local development:

```bash
npm run dev
```

For production build:

```bash
npm run build
```

### 6. Start the application

Use the full development stack (Laravel server, queue listener, logs, and Vite):

```bash
composer dev
```

Or run only the backend server:

```bash
php artisan serve
```

## One-Command Setup

You can also bootstrap the project with:

```bash
composer setup
```

This script installs dependencies, creates `.env`, generates app key, runs migrations, and builds frontend assets.

## Testing and Quality Checks

Run tests:

```bash
composer test
```

Run code style fixer:

```bash
vendor/bin/pint --parallel
```

Run frontend linting:

```bash
npm run lint
```

## Useful Commands

- `composer dev` - Start server, queue worker, logs, and Vite together.
- `php artisan migrate:fresh --seed` - Rebuild and seed the database.
- `php artisan queue:listen` - Run queue listener manually.
- `php artisan pail` - View application logs.

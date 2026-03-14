# JEFAL Prive School Management (Laravel)

Backend and Blade-based web platform for **JEFAL Privé**, a private language institute in Morocco.

## 1. Project Overview

This project provides:
- Role-based access control (`admin`, `secretary`, `professor`, `student`, `visitor`)
- School operations modules (classes, rooms, students, homework, announcements)
- Payment management with CMI integration structure (safe placeholders)
- Secure PDF upload/download with class-level authorization
- Email notifications (account creation, payment confirmation, payment reminders)
- Monthly scheduler for unpaid payment reminders
- Blade dashboards by role
- Shared-hosting-ready setup for Apache + MySQL (Hostinger)

## 2. Architecture

Full architecture documentation is available here:
- [ARCHITECTURE.md](/c:/Users/TaHa/Desktop/schoolWebsite/backend/ARCHITECTURE.md)

High-level layers:
- `Routes` -> HTTP entry points and role restrictions
- `Middleware` -> authorization gate by role
- `Form Requests` -> validation and authorization at request level
- `Controllers` -> orchestration only
- `Services` -> business/external integration logic (`CMIService`)
- `Models` -> Eloquent relationships and persistence
- `Mail + Commands` -> notifications and scheduled jobs
- `Blade Views` -> layouts/components/dashboard screens

## 3. Folder Structure

```text
backend/
|-- app/
|   |-- Console/Commands/           # Scheduled tasks (payment reminders)
|   |-- Http/
|   |   |-- Controllers/            # Dashboard/auth/business endpoints
|   |   |-- Middleware/             # RoleMiddleware
|   |   `-- Requests/               # Centralized validation
|   |-- Mail/                       # Email classes
|   |-- Models/                     # Eloquent models + relationships
|   |-- Policies/                   # Fine-grained authorization
|   |-- Providers/                  # Service provider + policy registration
|   `-- Services/                   # External/business services (CMI)
|-- bootstrap/
|-- config/
|-- database/
|   |-- migrations/
|   `-- seeders/
|-- public/
|-- resources/views/
|   |-- layouts/
|   |-- components/
|   |-- dashboard/
|   |-- auth/
|   |-- public/
|   `-- emails/
|-- routes/
|   |-- web.php
|   `-- console.php
|-- .env.example
`-- DEPLOYMENT_HOSTINGER.md
```

## 4. Requirements

- PHP `8.2+`
- Composer `2+`
- MySQL `8+`
- Apache with `mod_rewrite`

## 5. Local Setup

From the `backend` folder:

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan storage:link
php artisan migrate --seed
```

Configure `.env` values before running:
- `DB_*` for MySQL
- `MAIL_*` for SMTP
- `CMI_*` placeholders for payment provider integration

## 6. Run the Project

Development server:

```bash
php artisan serve
```

Default URL:
- `http://127.0.0.1:8000`

## 7. Demo Accounts (from seeders)

Passwords for demo users: `Password123!`

- `admin@jefalprive.ma` (admin)
- `secretary@jefalprive.ma` (secretary)
- `professor@jefalprive.ma` (professor)
- `student@jefalprive.ma` (student)
- `visitor@jefalprive.ma` (visitor)

## 8. Scheduler and Reminder Emails

Run once manually:

```bash
php artisan payments:send-reminders
```

In production, run scheduler every minute:

```bash
* * * * * php /path/to/project/artisan schedule:run >> /dev/null 2>&1
```

## 9. Security Notes

- Password hashing is enforced (`hashed` cast on `User`)
- CSRF is handled by Laravel for forms
- Form validation is centralized in Form Requests
- Route/middleware role protection is enabled
- PDF access is restricted to enrolled students
- Keep `APP_DEBUG=false` in production

## 10. Deployment (Hostinger Shared Hosting)

Use:
- [DEPLOYMENT_HOSTINGER.md](/c:/Users/TaHa/Desktop/schoolWebsite/backend/DEPLOYMENT_HOSTINGER.md)

## 11. Next Recommended Improvements

1. Split dashboard/business route files (`routes/admin.php`, `routes/secretary.php`, etc.) and load them in `bootstrap/app.php`.
2. Add PHPUnit feature tests for auth, RBAC, payments, and PDF access control.
3. Replace placeholder CMI verification with signed callback validation and API status checks.

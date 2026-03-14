# Architecture - JEFAL Prive Laravel

## 1. Goal

Define a clean, maintainable backend architecture for a role-based school management platform deployed on shared hosting (Apache + MySQL).

## 2. Architectural Style

Layered Laravel architecture with clear responsibilities:

- `Route Layer`: endpoint declaration + route grouping by role
- `Middleware Layer`: horizontal access control checks (`RoleMiddleware`)
- `Request Layer`: validation + per-action authorization (`FormRequest` classes)
- `Controller Layer`: orchestrates application use-cases
- `Service Layer`: isolated business/external gateway logic (`CMIService`)
- `Domain/Data Layer`: Eloquent models and relations
- `Presentation Layer`: Blade layouts/components/views
- `Async Layer`: mail queue + scheduled commands

## 3. Core Modules

### Authentication + RBAC
- Login/logout and dashboard redirect by role in `AuthController`
- Route protection via `auth` + `role:*` middleware
- `User::hasRole()` helper centralizes role checks

### Academic Management
- Classes, rooms, enrolled students
- Homeworks and class-linked PDFs
- Role-dependent access for operations

### Payments
- Monthly payment records per student
- `PaymentController` for create/confirm/callback handling
- `CMIService` as external provider integration boundary

### Communication
- Announcements with optional `target_role`
- Mailables for account/payment lifecycle notifications

### Scheduled Jobs
- `payments:send-reminders` scans unpaid/late payments
- Scheduler configured monthly in console kernel

## 4. Data Model Relationships

- `User` (professor) -> has many `SchoolClass`
- `User` (student) <-> many-to-many `SchoolClass` through `student_class`
- `SchoolClass` -> belongs to `Room`
- `User` (student) -> has many `Payment`
- `SchoolClass` -> has many `Homework`
- `SchoolClass` -> has many `Pdf`
- `User` -> has many `Announcement`, `Homework`, `Pdf`

## 5. Security Design

- Password hashing through model cast (`password => hashed`)
- Input validation in dedicated request objects
- CSRF on all state-changing forms
- Route-level role restrictions
- Policy-ready structure (`PdfPolicy`)
- PDF download restricted to students enrolled in the related class

## 6. Request Lifecycle (Example)

### Secure PDF Download (Student)
1. Route `/student/pdfs/{pdf}` requires `auth` + `role:student`
2. Controller checks student enrollment for `pdf.class_id`
3. If authorized and file exists, streams from `storage/app/public`
4. Otherwise returns `403` or `404`

### CMI Callback
1. CMI posts callback payload to `/payments/callback/cmi`
2. Controller delegates parsing to `CMIService::handleCallback`
3. Verification performed by `CMIService::verifyTransaction`
4. Payment status updated and confirmation email queued

## 7. Deployment-Oriented Decisions

- Apache rewrite rules included (`.htaccess`)
- Environment-based config for DB, mail, and CMI
- Shared-hosting cron compatible scheduler command
- Public file serving via `storage:link`

## 8. Recommended Professionalization Roadmap

1. Split monolithic `web.php` into role-focused route files.
2. Add DTO/resource classes for API-like consistency in controllers.
3. Add feature tests for RBAC boundaries and payment flows.
4. Introduce audit logs for payment and file events.
5. Implement real CMI signature verification and replay protection.

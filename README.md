# JEFAL Privé - Monorepo Structure

This workspace is organized into two clear top-level applications:

```text
schoolWebsite/
|-- frontend/   # React UI (public site + dashboard interface)
|-- backend/    # Laravel app (auth, RBAC, business logic, Blade)
`-- README.md   # Root workspace guide
```

## Folder Roles

- `frontend/`
  - Existing React application UI
  - Can be used as design/reference while migrating screens to Blade

- `backend/`
  - Main production backend for Hostinger deployment
  - Includes migrations, models, controllers, middleware, services, Blade views

## Recommended Workflow

1. Build and validate business logic in `backend/`.
2. Recreate/align React pages into Blade views in `backend/resources/views`.
3. Keep `frontend/` as reference until Blade migration is complete.
4. Deploy only Laravel `backend/` to production.

## Run Commands
Backend:
```bash
cd backend
composer install
cp .env.example .env
php artisan key:generate
php artisan storage:link
php artisan migrate --seed
php artisan serve
```

Frontend (optional/reference):
```bash
cd frontend
npm install
npm run dev
```

## Important Docs

- Backend guide: [backend/README.md](/c:/Users/TaHa/Desktop/schoolWebsite/backend/README.md)
- Backend architecture: [backend/ARCHITECTURE.md](/c:/Users/TaHa/Desktop/schoolWebsite/backend/ARCHITECTURE.md)
- Hostinger deployment: [backend/DEPLOYMENT_HOSTINGER.md](/c:/Users/TaHa/Desktop/schoolWebsite/backend/DEPLOYMENT_HOSTINGER.md)

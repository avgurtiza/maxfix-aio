# MaxFix Admin Panel

Filament-based admin panel for MaxFix vehicle maintenance application.

## Prerequisites

- Docker
- Docker Compose
- PHP 8.2+ (for local development outside Docker)
- Composer

## Quick Start

### Using Docker (Recommended)

1. Start PostgreSQL container:
```bash
docker-compose up -d postgres
```

2. Install dependencies:
```bash
composer install
```

3. Run migrations:
```bash
php artisan migrate
```

4. Seed admin user:
```bash
php artisan db:seed --class=AdminUserSeeder
```

5. Build assets:
```bash
npm install
npm run build
```

6. Start development server:
```bash
php artisan serve
```

7. Access admin panel at http://localhost:8000/admin

### Default Credentials

**Admin Panel:**
- Email: admin@maxfix.com
- Password: password

**App Users (for testing data management):**
- Car Owner: owner@example.com / password
- Fleet Manager: fleet@example.com / password
- Service Personnel: service@example.com / password

## Docker Setup

The admin panel uses Docker for PostgreSQL database with following containers:

- `maxfix-admin-postgres`: PostgreSQL 15 database
- `maxfix-admin-nginx`: Nginx web server (optional)
- `maxfix-admin-app`: PHP-FPM application (optional)

### Database Connection

- Host: `postgres` (inside Docker) or `localhost:5433` (locally)
- Database: `maxfix_admin`
- Username: `maxfix_user`
- Password: `maxfix_password`

## Available Resources

The admin panel includes Filament resources for:

### User Management
1. **Admin Users** - Manage admin panel access (separate from app users)
2. **Users** - Manage application users (car owners, fleet managers, service personnel)

### Vehicle Management
3. **Vehicles** - Manage vehicle information and ownership
   - Includes relationship managers for assigned users and service records

### Service Management
4. **Service Records** - Track service history and maintenance
5. **Service Shops** - Manage service shop directory
6. **Maintenance Reminders** - Configure and manage maintenance reminders

## User Separation

The admin panel uses two separate user systems:

- **Admin Users** (`admin_users` table): For Filament admin panel access only
- **App Users** (`users` table): For the main MaxFix application (backend/)

These are completely separate authentication systems with different guards:
- `admin` guard for Filament panel
- `web` guard for app users

## Navigation Structure

Resources are organized into navigation groups:

- **User Management** (icon: users)
  - Admin Users
  - Users

- **Vehicle Management** (icon: truck)
  - Vehicles (with relationship managers for users, service records, reminders)

- **Service Management** (icon: wrench-screwdriver)
  - Service Records
  - Service Shops
  - Maintenance Reminders

## Relationship Managers

The following relationship managers provide nested views:

### UserResource
- **VehiclesRelationManager** - View and manage user's assigned vehicles
- **FavoriteShopsRelationManager** - View user's favorite service shops

### VehicleResource
- **UsersRelationManager** - Manage vehicle ownership (owner, driver, mechanic)
- **ServiceRecordsRelationManager** - View vehicle's service history
- **RemindersRelationManager** - View vehicle's maintenance reminders

### ServiceRecordResource
- **VehicleRelationManager** - View associated vehicle
- **ShopRelationManager** - View associated service shop
- **CreatorRelationManager** - View record creator

### ServiceShopResource
- **ServiceRecordsRelationManager** - View shop's service records
- **FavoritedByRelationManager** - View users who favorited this shop

### MaintenanceReminderResource
- **VehicleRelationManager** - View reminder's vehicle
- **CreatorRelationManager** - View reminder creator

## Development

### Creating New Resources

```bash
php artisan make:filament-resource ResourceName --generate
```

### Running Tests

```bash
php artisan test
```

### Code Style

```bash
./vendor/bin/pint
```

## Environment Variables

Key environment variables in `.env`:

```env
APP_NAME="MaxFix Admin"
APP_URL=http://localhost:8081

DB_CONNECTION=pgsql
DB_HOST=localhost
DB_PORT=5433
DB_DATABASE=maxfix_admin
DB_USERNAME=maxfix_user
DB_PASSWORD=maxfix_password
```

## Docker Commands

```bash
# Start all containers
docker-compose up -d

# Start specific containers
docker-compose up -d postgres

# View logs
docker-compose logs -f

# Stop containers
docker-compose down

# Stop and remove volumes
docker-compose down -v
```

## Project Structure

```
admin/
├── app/
│   ├── Filament/
│   │   ├── AdminPanelProvider.php
│   │   └── Resources/
│   │       ├── UserResource.php
│   │       ├── VehicleResource.php
│   │       ├── ServiceRecordResource.php
│   │       ├── ServiceShopResource.php
│   │       └── MaintenanceReminderResource.php
│   └── Models/
├── database/
│   ├── migrations/
│   └── seeders/
├── docker/
│   └── nginx/
│       └── default.conf
├── docker-compose.yml
└── Dockerfile
```

## Accessing PostgreSQL

```bash
docker exec -it maxfix-admin-postgres psql -U maxfix_user -d maxfix_admin
```

## Troubleshooting

### Database Connection Issues

If you can't connect to PostgreSQL:

1. Ensure container is running:
```bash
docker-compose ps
```

2. Check connection string in `.env` - use `localhost:5433` for local access

3. Restart container:
```bash
docker-compose restart postgres
```

### Filament Not Loading

1. Clear config cache:
```bash
php artisan config:clear
```

2. Clear view cache:
```bash
php artisan view:clear
```

3. Rebuild assets:
```bash
npm run build
```

## Security Notes

- Change default admin password in production
- Update `APP_KEY` for production
- Use strong database passwords
- Enable HTTPS in production
- Configure proper firewall rules

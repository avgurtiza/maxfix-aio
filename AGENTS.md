# AGENTS.md - Coding Guidelines for max-fix

## Project Overview
Laravel 12 application with PHP 8.2+, Filament v3 admin panel, and Vite/TailwindCSS frontend.

### Architecture
- **app/** - Main application (Livewire SPA)
- **admin/** - Filament admin panel (separate Laravel installation)

## Build/Lint/Test Commands

### App (run from `app/` directory)
```bash
# Install dependencies
composer install

# Run all tests
composer test
# OR
php artisan test

# Run single test file
php artisan test tests/Feature/ExampleTest.php

# Run single test method
php artisan test --filter=test_the_application_returns_a_successful_response

# Run tests with coverage
php artisan test --coverage

# Lint/fix code style (Laravel Pint)
./vendor/bin/pint
./vendor/bin/pint --test        # Check without fixing
```

### Frontend (run from `app/` directory)
```bash
# Install dependencies
bun install

# Development build with hot reload
bun run dev

# Production build
bun run build
```

### Full Development
```bash
# Start all services (server, queue, logs, vite)
composer dev
```

### Admin Panel (run from `admin/` directory)
```bash
# Start PostgreSQL container
docker-compose up -d postgres

# Install dependencies
composer install

# Run migrations
php artisan migrate

# Seed database
php artisan db:seed

# Build frontend assets
npm install && npm run build

# Start development server
php artisan serve

# Access admin panel at http://localhost:8000/admin
```

### Docker Commands for Admin Panel
```bash
# Start all containers
docker-compose up -d

# View logs
docker-compose logs -f

# Stop containers
docker-compose down

# Access PostgreSQL directly
docker exec -it maxfix-admin-postgres psql -U maxfix_user -d maxfix_admin
```

## Code Style Guidelines

### PHP

#### Formatting
- **Indent**: 4 spaces (no tabs)
- **Line endings**: LF
- **Charset**: UTF-8
- **Follows**: PSR-12 via Laravel Pint

#### Naming Conventions
- **Classes**: PascalCase (e.g., `UserController`)
- **Methods/Functions**: camelCase (e.g., `getUserById`)
- **Variables**: camelCase (e.g., `$userName`)
- **Constants**: UPPER_SNAKE_CASE
- **Database tables**: snake_case, plural (e.g., `user_profiles`)
- **Models**: PascalCase, singular (e.g., `UserProfile`)

#### Imports
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;  // Standard library first
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;
    // ...
}
```

#### Type Declarations
- Always use return type declarations
- Use PHP 8.2+ features (typed properties, union types, readonly)
- Add PHPDoc for complex types

```php
public function getUser(int $id): ?User
{
    return User::find($id);
}
```

#### Error Handling
- Use Laravel's exception handling
- Validate input with Form Requests
- Use type-safe returns

### JavaScript

#### Formatting
- **Indent**: 4 spaces (matches PHP)
- **Quotes**: Single quotes for strings
- **Semicolons**: Required

#### Naming Conventions
- **Variables/Functions**: camelCase
- **Classes**: PascalCase
- **Constants**: UPPER_SNAKE_CASE

#### Imports
```javascript
import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';
```

## Testing Conventions

### Test Structure
- Place Unit tests in `tests/Unit/`
- Place Feature tests in `tests/Feature/`
- All tests extend `Tests\TestCase`

### Test Naming
- Use descriptive snake_case method names
- Prefix with `test_` or use `@test` annotation

```php
public function test_user_can_create_post(): void
{
    $response = $this->get('/');
    $response->assertStatus(200);
}
```

### Running Tests
- Always use `php artisan test` instead of `vendor/bin/phpunit`
- Tests use SQLite in-memory database

## Project Structure

```
app/
├── app/              # Application code
│   ├── Http/         # Controllers, Middleware, Requests
│   ├── Models/       # Eloquent models
│   ├── Livewire/     # Livewire components
│   ├── Notifications/ # Notification classes
│   ├── Policies/     # Authorization policies
│   ├── Providers/    # Service providers
│   └── Services/     # Business logic services
├── config/           # Configuration files
├── database/         # Migrations, factories, seeders
├── resources/        # Views, CSS, JS
├── routes/           # Route definitions
├── tests/            # Test files
└── .env.example      # Environment template

admin/
├── app/
│   ├── Filament/
│   │   ├── AdminPanelProvider.php
│   │   └── Resources/
│   │       ├── AdminUserResource.php
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

## Environment

### App
- Copy `.env.example` to `.env`
- Generate app key: `php artisan key:generate`
- Database defaults to SQLite
- Run migrations: `php artisan migrate`

### Admin Panel
- PostgreSQL via Docker (port 5433)
- Database: `maxfix_admin`
- Username: `maxfix_user`
- Password: `maxfix_password`
- Run migrations: `php artisan migrate`

## Admin Panel Credentials

### Default Admin User (Filament Access)
- Email: admin@maxfix.com
- Password: password

### Default App Users
- Car Owner: owner@example.com / password
- Fleet Manager: fleet@example.com / password
- Service Personnel: service@example.com / password

## Additional Notes

- Use Laravel's built-in features (Eloquent, Validation, etc.)
- Follow Laravel conventions over custom implementations
- Use type hints and return types everywhere possible
- No custom Pint configuration - use Laravel defaults

### Filament Admin Panel Guidelines

#### Resource Structure
- Place Filament resources in `admin/app/Filament/Resources/`
- Create relation managers in `admin/app/Filament/Resources/{Resource}/RelationManagers/`
- Use `--generate` flag to auto-generate pages: `php artisan make:filament-resource ResourceName --generate`

#### Navigation
- Set navigation icon: `protected static ?string $navigationIcon = 'heroicon-o-icon-name';`
- Set navigation group: `protected static ?string $navigationGroup = 'Group Name';`
- Set sort order: `protected static ?int $navigationSort = 1;`

#### Forms
- Use relationship Selects with `->relationship()` and `->searchable()->preload()`
- Group related fields with `Forms\Components\Section`
- Use appropriate input types (TagsInput for arrays, Toggle for booleans, etc.)

#### Tables
- Use `->searchable()` for searchable columns
- Use `->sortable()` for sortable columns
- Use BadgeColumn for enum/status fields with color coding
- Hide less important columns with `->toggleable(isToggledHiddenByDefault: true)`

#### Relationship Managers
- Create for BelongsTo/HasMany relationships
- Use for managing pivot table relationships (attach/detach actions)
- Include view actions for quick access to related records

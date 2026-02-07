# AGENTS.md - Coding Guidelines for max-fix

## Project Overview
Laravel 12 application with PHP 8.2+ backend and Vite/TailwindCSS frontend.

## Build/Lint/Test Commands

### PHP (run from `backend/` directory)
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

### Frontend (run from `backend/` directory)
```bash
# Install dependencies
npm install

# Development build with hot reload
npm run dev

# Production build
npm run build
```

### Full Development
```bash
# Start all services (server, queue, logs, vite)
composer dev
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
backend/
├── app/              # Application code
│   ├── Http/         # Controllers, Middleware, Requests
│   ├── Models/       # Eloquent models
│   └── Providers/    # Service providers
├── config/           # Configuration files
├── database/         # Migrations, factories, seeders
├── resources/        # Views, CSS, JS
├── routes/           # Route definitions
├── tests/            # Test files
└── .env.example      # Environment template
```

## Environment

- Copy `.env.example` to `.env`
- Generate app key: `php artisan key:generate`
- Database defaults to SQLite
- Run migrations: `php artisan migrate`

## Additional Notes

- Use Laravel's built-in features (Eloquent, Validation, etc.)
- Follow Laravel conventions over custom implementations
- Use type hints and return types everywhere possible
- No custom Pint configuration - use Laravel defaults

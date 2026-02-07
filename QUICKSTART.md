# MaxFix Quick Start Guide

Get MaxFix up and running in 5 minutes.

## Prerequisites

- **Docker & Docker Compose** (required)
- **PHP 8.2+** (optional, for local development)
- **Node.js 18+** (optional, for local development)

## Quick Start

### 1. Start Docker Services

```bash
cd /Users/tatay/projects/max-fix
docker-compose up -d
```

This starts:
- **Nginx** on `http://localhost:8080` (web server)
- **PHP-FPM** (backend)
- **Mailpit** on `http://localhost:8025` (email testing)

### 2. Seed Database

```bash
docker exec maxfix_php php artisan db:seed --class=ServiceShopSeeder
```

This adds 15 sample service shops across Philippine cities.

### 3. Access the Application

Open your browser: **http://localhost:8080**

## First Steps

### Register an Account

1. Click **Sign Up** in the navigation
2. Fill in your details:
   - Full Name
   - Email address
   - Password (min 8 characters)
   - Select role: **Car Owner** (personal) or **Fleet Manager** (up to 10 vehicles)
3. Click **Create Account**

### Add Your First Vehicle

1. After registration, you'll be redirected to the vehicles page
2. Click **+ Add Vehicle**
3. Option A: Enter a **17-character VIN** and click **Decode VIN** (auto-fills details)
   - Example: `1HGCM82633A004352`
4. Option B: Enter vehicle details manually:
   - Make (e.g., Toyota)
   - Model (e.g., Camry)
   - Year (e.g., 2020)
   - License Plate (e.g., ABC 1234)
   - Current Mileage
5. Click **Create Vehicle**

### Log a Service Record

1. From the vehicle card, click **Services**
2. Click **Log Service**
3. Fill in details:
   - Service Date
   - Mileage at service
   - Service Type (Oil Change, Tire Rotation, Brake Service, etc.)
   - Description (optional)
   - Cost (optional)
   - Select Service Shop (optional)
   - Upload Receipt (optional) - PDF, JPG, or PNG (max 10MB)
4. Click **Log Service**

### Create a Maintenance Reminder

1. From the vehicle card, click **Services**, then go to the Reminders tab
2. Click **Create Reminder**
3. Fill in details:
   - Service Name (e.g., "Oil Change")
   - Reminder Type: Mileage-based, Date-based, or Both
   - Trigger: Every X km or Every X days
   - Notification methods: Email
4. Click **Create Reminder**

### Search for Service Shops

1. Click **Shops** in the navigation (or visit `/shops`)
2. Filter by:
   - City or service type
   - Distance from your location (requires coordinates)
3. Click on a shop to see details
4. Click **⭐ Favorite** to save for later

## Available Web Routes

| Route | Description |
|-------|-------------|
| `/` | Landing page |
| `/login` | Login form |
| `/register` | Registration form |
| `/vehicles` | My vehicles dashboard |
| `/vehicles/create` | Add new vehicle |
| `/vehicles/{uuid}/edit` | Edit vehicle |
| `/vehicles/{uuid}/services` | Service history timeline |
| `/vehicles/{uuid}/services/create` | Log new service |
| `/vehicles/{uuid}/reminders` | Maintenance reminders |
| `/vehicles/{uuid}/reminders/create` | Create reminder |
| `/shops` | Service shop directory |

## Available API Endpoints

### Authentication
```bash
# Register
POST /api/register
{
  "name": "John Doe",
  "email": "john@example.com",
  "password": "password123",
  "password_confirmation": "password123",
  "role": "car_owner"
}

# Login
POST /api/login
{
  "email": "john@example.com",
  "password": "password123"
}

# Response
{
  "user": { ... },
  "token": "2|abc123xyz..."  # Save this token
}
```

### Vehicles
```bash
# List vehicles (requires token)
GET /api/vehicles
Authorization: Bearer YOUR_TOKEN

# Create vehicle
POST /api/vehicles
{
  "make": "Toyota",
  "model": "Camry",
  "year": 2020,
  "vin": "1HGCM82633A004352",
  "current_plate": "ABC 1234",
  "current_mileage": 50000
}

# Decode VIN
POST /api/vehicles/decode-vin
{
  "vin": "1HGCM82633A004352"
}
```

### Service Records
```bash
# List services for vehicle
GET /api/vehicles/{uuid}/services

# Create service record (with file)
POST /api/vehicles/{uuid}/services
Content-Type: multipart/form-data
{
  "service_date": "2024-01-15",
  "mileage": 52000,
  "service_type": "oil_change",
  "description": "Regular oil change",
  "cost": 50.00,
  "shop_id": 1,
  "receipt": <file>
}
```

### Maintenance Reminders
```bash
# List reminders for vehicle
GET /api/vehicles/{uuid}/reminders

# Create reminder
POST /api/vehicles/{uuid}/reminders
{
  "service_name": "Oil Change",
  "reminder_type": "mileage",
  "trigger_mileage": 5000,
  "trigger_days": 180
}

# Mark reminder as complete
POST /api/reminders/{id}/complete
{
  "current_mileage": 57000
}
```

### Service Shops
```bash
# Search shops
GET /api/shops?city=Makati&service_type=oil_change

# Shop details
GET /api/shops/{id}

# Add to favorites
POST /api/shops/{id}/favorite

# Remove from favorites
DELETE /api/shops/{id}/favorite

# List favorites
GET /api/shops/favorites
```

## Common Commands

### Docker
```bash
# Start all services
docker-compose up -d

# View logs
docker-compose logs -f nginx  # or php, mailpit

# Stop all services
docker-compose down

# Stop and remove volumes (resets database)
docker-compose down -v
```

### PHP Artisan (from inside container)
```bash
# Run from host
docker exec maxfix_php php artisan <command>

# Or enter container
docker exec -it maxfix_php sh

# Run migrations
docker exec maxfix_php php artisan migrate

# Seed database
docker exec maxfix_php php artisan db:seed

# Clear caches
docker exec maxfix_php php artisan cache:clear
docker exec maxfix_php php artisan config:clear
docker exec maxfix_php php artisan route:clear
```

### Testing
```bash
# Run all tests
docker exec maxfix_php php artisan test

# Run single test file
docker exec maxfix_php php artisan test tests/Feature/VehicleApiTest.php

# Run specific test method
docker exec maxfix_php php artisan test --filter=test_user_can_create_vehicle

# Run with coverage
docker exec maxfix_php php artisan test --coverage
```

### Code Formatting
```bash
# Run Laravel Pint (auto-fixes code)
docker exec maxfix_php ./vendor/bin/pint

# Check without fixing
docker exec maxfix_php ./vendor/bin/pint --test
```

## Scheduled Tasks

MaxFix includes a daily reminder scheduler. To enable in production:

### Option A: Cron Job (Recommended)

```bash
# Add to crontab
* * * * * cd /path/to/project && docker exec maxfix_php php artisan schedule:run >> /dev/null 2>&1
```

### Option B: Run Manually

```bash
# Send all due reminder notifications
docker exec maxfix_php php artisan app:send-maintenance-reminders
```

### Option C: Supervisor (Production)

```ini
[program:maxfix-scheduler]
process_name=%(program_name)s_%(process_num)02d
command=php artisan schedule:run
directory=/var/www/html
autostart=true
autorestart=true
user=www-data
numprocs=1
redirect_stderr=true
stdout_logfile=/var/www/html/storage/logs/scheduler.log
```

## Troubleshooting

### Docker won't start?

```bash
# Check port conflicts
lsof -i :8080  # Nginx
lsof -i :8025  # Mailpit

# Rebuild containers
docker-compose down
docker-compose up -d --build
```

### Database locked or errors?

```bash
# Reset database (WARNING: deletes all data)
docker-compose down -v
docker exec maxfix_php php artisan migrate:fresh --seed
```

### 502 Bad Gateway?

```bash
# Check if PHP container is running
docker ps | grep maxfix_php

# Restart PHP container
docker-compose restart php
```

### Mailpit not showing emails?

```bash
# Check Mailpit is running
docker ps | grep maxfix_mailpit

# Visit Mailpit UI
open http://localhost:8025

# Test email sending
docker exec maxfix_php php artisan tinker
>>> \Illuminate\Support\Facades\Mail::raw('Test', fn($m) => $m->to('test@example.com')->send());
```

### File uploads failing?

```bash
# Check storage permissions
docker exec maxfix_php ls -la storage/app/public/

# Create storage symlink
docker exec maxfix_php php artisan storage:link
```

### API returns 401 Unauthorized?

Make sure you're including the Bearer token:

```bash
# Wrong
Authorization: abc123xyz

# Correct
Authorization: Bearer abc123xyz
```

## Project Structure

```
max-fix/
├── docker/
│   ├── nginx/
│   │   └── nginx.conf
│   ├── php/
│   │   ├── Dockerfile
│   │   └── php.ini
│   └── php/
├── backend/
│   ├── app/
│   │   ├── Console/Commands/    # Scheduled tasks
│   │   ├── Http/Controllers/Api/  # API controllers
│   │   ├── Http/Resources/         # API transformers
│   │   ├── Livewire/              # Frontend components
│   │   ├── Models/                # Eloquent models
│   │   ├── Policies/              # Authorization
│   │   └── Providers/             # Service providers
│   ├── config/                    # Configuration files
│   ├── database/
│   │   ├── migrations/           # Database migrations
│   │   └── seeders/              # Seed data
│   ├── public/                    # Public web root
│   ├── resources/
│   │   └── views/               # Blade templates
│   ├── routes/                    # Route definitions
│   └── .env                      # Environment variables
├── docker-compose.yml            # Docker orchestration
├── README.md                   # Project overview
├── SPECS.md                   # Technical specifications
├── TASKS.md                   # Implementation tasks
├── IMPLEMENTATION_PLAN.md       # Architecture decisions
├── AGENTS.md                  # Coding guidelines
└── QUICKSTART.md              # This file
```

## Development Tips

### View Laravel logs

```bash
docker exec maxfix_php tail -f storage/logs/laravel.log
```

### Run database migrations after schema changes

```bash
docker exec maxfix_php php artisan migrate
```

### Reset everything (fresh start)

```bash
docker-compose down -v
docker exec maxfix_php php artisan migrate:fresh --seed
```

### Test API with cURL

```bash
# Get token
TOKEN=$(curl -X POST http://localhost:8080/api/login \
  -H "Content-Type: application/json" \
  -d '{"email":"john@example.com","password":"password"}' \
  | jq -r '.token')

# Use token
curl -X GET http://localhost:8080/api/vehicles \
  -H "Authorization: Bearer $TOKEN"
```

## Next Steps

1. ✅ Explore the web interface
2. ✅ Test API endpoints
3. ✅ Try the VIN decoder (use a real VIN)
4. ✅ Upload a receipt image/PDF
5. ✅ Create reminders and check notifications
6. ✅ Browse service shops
7. ✅ Review the [SPECS.md](./SPECS.md) for detailed technical docs
8. ✅ Check [TASKS.md](./TASKS.md) for remaining implementation items

## Support

- **Documentation**: [AGENTS.md](./AGENTS.md) - Coding guidelines
- **Technical Specs**: [SPECS.md](./SPECS.md) - API details
- **Tasks**: [TASKS.md](./TASKS.md) - Implementation status
- **Issues**: Report bugs on GitHub

---

**Happy Testing! 🚗**

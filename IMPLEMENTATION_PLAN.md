# MaxFix Implementation Plan

## Project Overview

MaxFix is a vehicle service management application designed for car owners and small fleet managers. The system will track vehicle service history, provide maintenance reminders, and connect users with service providers.

## Frontend Technology Decision

### Your Situation Analysis

**Current Skills:**
- Primary stack: Laravel + Livewire
- Minimal JavaScript skills
- Future goal: Mobile app after web frontend

**Key Considerations:**

| Factor | Laravel + Livewire | Next.js (Current Plan) | Recommendation |
|--------|-------------------|----------------------|----------------|
| **Learning Curve** | ✅ Minimal (you know it) | ❌ Steep (React, TypeScript, App Router) | Livewire wins |
| **Mobile Reusability** | ❌ Web-only, no code sharing | ⚠️ Limited (API only) | Neither ideal |
| **API-First Architecture** | ⚠️ Requires discipline | ✅ Natural separation | Next.js wins |
| **Development Speed** | ✅ Fast for you | ❌ Slow learning phase | Livewire wins |
| **Modern UX** | ⚠️ Good, but page-centric | ✅ SPA-like experience | Next.js wins |
| **Team Scalability** | ⚠️ PHP-only developers | ✅ Separate frontend team | Next.js wins |

### Recommended Approach: **Hybrid Strategy**

> [!IMPORTANT]
> **Start with Laravel + Livewire, Build API Alongside**
> 
> This approach gives you the best of both worlds:
> 1. **Immediate productivity** - Build features quickly with your existing skills
> 2. **Future-proof architecture** - Design API endpoints as you build Livewire components
> 3. **Mobile readiness** - API will be ready when you start mobile development
> 4. **Optional migration** - Can switch to Next.js later if needed, or keep Livewire

#### Implementation Strategy

**Phase 1: Laravel + Livewire Web App (Months 1-3)**
- Build full-featured web application with Livewire
- Simultaneously create RESTful API endpoints for each feature
- Use Laravel Sanctum for authentication (supports both web sessions and API tokens)
- Structure: `routes/web.php` (Livewire) + `routes/api.php` (mobile-ready API)

**Phase 2: Mobile App (Months 4-6)**
- Use Flutter or React Native
- Consume the API you built in Phase 1
- No frontend code reuse, but all business logic is ready

**Phase 3: Optional Frontend Migration (Future)**
- If you hire frontend developers or learn React, migrate to Next.js
- API is already built and tested
- Zero backend changes needed

### Alternative: Pure API-First with Next.js

If you're committed to learning modern JavaScript and have 2-3 months for the learning curve:

**Pros:**
- Better separation of concerns from day one
- More marketable skills
- Easier to hire specialized developers later

**Cons:**
- 3-4x slower initial development
- Steep learning curve (React hooks, TypeScript, Server Components, App Router)
- More complex deployment and debugging

> [!TIP]
> **My Recommendation:** Start with Laravel + Livewire + API. Here's why:
> - You'll ship features 3x faster initially
> - The API you build will serve the mobile app perfectly
> - Livewire 3 is modern and capable (Alpine.js integration, SPA-like navigation)
> - You can always migrate the frontend later without touching the backend

---

## Proposed Architecture

### Backend: Laravel API + Livewire Web UI

```
┌─────────────────────────────────────────────────────────┐
│                     Nginx (Port 8080)                    │
│  ┌────────────────┐              ┌──────────────────┐   │
│  │   / (web)      │              │   /api/*         │   │
│  │   Livewire UI  │              │   JSON API       │   │
│  └────────────────┘              └──────────────────┘   │
└─────────────────────────────────────────────────────────┘
                          │
                          ▼
              ┌───────────────────────┐
              │   Laravel (PHP 8.3)   │
              │   ├── Livewire        │
              │   ├── API Resources   │
              │   ├── Sanctum Auth    │
              │   └── Business Logic  │
              └───────────────────────┘
                          │
                          ▼
                  ┌───────────────┐
                  │    SQLite     │
                  │  (dev) / PG   │
                  └───────────────┘
```

### Mobile App (Future Phase)

```
┌─────────────────────────┐
│   Flutter/React Native  │
│   Mobile Application    │
└─────────────────────────┘
            │
            │ HTTPS
            ▼
    ┌───────────────┐
    │  Laravel API  │
    │  /api/*       │
    └───────────────┘
```

---

## Proposed Changes

### Phase 1: Project Foundation (Week 1-2)

#### [NEW] Docker Configuration

**Files to create:**
- `docker-compose.yml` - Multi-service orchestration
- `docker/nginx/nginx.conf` - Reverse proxy configuration
- `docker/php/Dockerfile` - PHP 8.3 + extensions
- `docker/php/php.ini` - PHP configuration
- `.dockerignore` - Exclude unnecessary files

**Configuration:**
- Nginx on port 8080 (reverse proxy)
- PHP-FPM service
- SQLite volume mount
- Mailpit on port 8025
- Hot reload for development

#### [NEW] Laravel Application Setup

**Core files:**
- `backend/.env.example` - Environment template
- `backend/config/sanctum.php` - API authentication
- `backend/config/cors.php` - CORS for mobile API
- `backend/database/migrations/` - Database schema

**Initial migrations:**
1. `create_users_table` - Enhanced with roles
2. `create_vehicles_table` - VIN, make, model, year, plate
3. `create_vehicle_user_table` - Ownership/management pivot
4. `create_service_records_table` - Service history
5. `create_maintenance_reminders_table` - Scheduled reminders
6. `create_service_shops_table` - Shop directory

---

### Phase 2: Authentication & User Management (Week 3)

#### Backend Components

##### [NEW] `app/Models/User.php`
- Roles: `car_owner`, `fleet_manager`, `service_personnel`
- Sanctum token authentication
- Relationships: vehicles, serviceRecords

##### [NEW] `app/Http/Controllers/Api/AuthController.php`
- `POST /api/register` - User registration
- `POST /api/login` - Token generation
- `POST /api/logout` - Token revocation
- `GET /api/user` - Current user profile

##### [NEW] Livewire Components
- `app/Livewire/Auth/Register.php` - Registration form
- `app/Livewire/Auth/Login.php` - Login form
- `resources/views/livewire/auth/register.blade.php`
- `resources/views/livewire/auth/login.blade.php`

**Features:**
- Email/password authentication
- Role selection during registration
- Session-based auth for web (Livewire)
- Token-based auth for API (mobile future)

---

### Phase 3: Vehicle Management (Week 4-5)

#### Backend Models & Controllers

##### [NEW] `app/Models/Vehicle.php`
```php
// Key fields:
- uuid (primary identifier)
- vin (unique, nullable)
- make, model, year
- current_plate
- current_mileage
- created_at, updated_at
```

##### [NEW] `app/Http/Controllers/Api/VehicleController.php`
- `GET /api/vehicles` - List user's vehicles
- `POST /api/vehicles` - Register new vehicle
- `GET /api/vehicles/{uuid}` - Vehicle details
- `PUT /api/vehicles/{uuid}` - Update vehicle
- `DELETE /api/vehicles/{uuid}` - Remove vehicle
- `POST /api/vehicles/decode-vin` - VIN decoding service

##### [NEW] Livewire Components
- `app/Livewire/Vehicles/VehicleList.php` - Vehicle dashboard
- `app/Livewire/Vehicles/VehicleForm.php` - Add/edit vehicle
- `app/Livewire/Vehicles/VinDecoder.php` - VIN lookup component

**Features:**
- VIN-based registration with auto-fill
- Manual entry fallback
- Vehicle ownership transfer (preserves history)
- Fleet manager: manage up to 10 vehicles
- Vehicle card UI with make/model/year/plate

---

### Phase 4: Service History (Week 6-7)

#### Backend Components

##### [NEW] `app/Models/ServiceRecord.php`
```php
// Key fields:
- vehicle_id
- service_date
- mileage
- service_type (oil_change, tire_rotation, etc.)
- description
- cost
- shop_id (nullable)
- receipt_path (file upload)
```

##### [NEW] `app/Http/Controllers/Api/ServiceRecordController.php`
- `GET /api/vehicles/{uuid}/services` - Service history
- `POST /api/vehicles/{uuid}/services` - Log new service
- `GET /api/services/{id}` - Service details
- `PUT /api/services/{id}` - Update service
- `DELETE /api/services/{id}` - Delete service

##### [NEW] Livewire Components
- `app/Livewire/Services/ServiceHistory.php` - Timeline view
- `app/Livewire/Services/ServiceForm.php` - Log service form
- `app/Livewire/Services/ReceiptUpload.php` - File upload component

**Features:**
- Chronological service timeline
- Filter by service type, date range
- Receipt image upload and preview
- Cost tracking and totals
- Export to PDF (future)

---

### Phase 5: Maintenance Reminders (Week 8)

#### Backend Components

##### [NEW] `app/Models/MaintenanceReminder.php`
```php
// Key fields:
- vehicle_id
- reminder_type (mileage_based, time_based)
- service_name (e.g., "Oil Change")
- trigger_mileage (nullable)
- trigger_date (nullable)
- notification_methods (email, push)
- is_completed
```

##### [NEW] `app/Console/Commands/SendMaintenanceReminders.php`
- Scheduled command (daily)
- Checks due reminders
- Sends email/push notifications

##### [NEW] `app/Http/Controllers/Api/ReminderController.php`
- `GET /api/vehicles/{uuid}/reminders` - List reminders
- `POST /api/vehicles/{uuid}/reminders` - Create reminder
- `PUT /api/reminders/{id}` - Update reminder
- `POST /api/reminders/{id}/complete` - Mark complete
- `DELETE /api/reminders/{id}` - Delete reminder

##### [NEW] Livewire Components
- `app/Livewire/Reminders/ReminderList.php` - Active reminders
- `app/Livewire/Reminders/ReminderForm.php` - Create/edit reminder
- `app/Livewire/Reminders/ReminderNotifications.php` - Notification bell

**Features:**
- Mileage-based triggers (e.g., every 5,000 km)
- Time-based triggers (e.g., every 6 months)
- Email notifications via Mailpit (dev) / Mailgun (prod)
- Push notifications (future mobile)
- Snooze/dismiss functionality

---

### Phase 6: Service Shop Lookup (Week 9)

#### Backend Components

##### [NEW] `app/Models/ServiceShop.php`
```php
// Key fields:
- name
- address, city, postal_code
- latitude, longitude
- phone, email, website
- services_offered (JSON array)
- rating (future)
```

##### [NEW] `app/Http/Controllers/Api/ShopController.php`
- `GET /api/shops` - Search shops (with geo filters)
- `GET /api/shops/{id}` - Shop details
- `POST /api/shops/{id}/favorite` - Add to favorites
- `DELETE /api/shops/{id}/favorite` - Remove favorite

##### [NEW] Livewire Components
- `app/Livewire/Shops/ShopSearch.php` - Search interface
- `app/Livewire/Shops/ShopMap.php` - Map view (Leaflet.js)
- `app/Livewire/Shops/ShopDetails.php` - Shop detail modal

**Features:**
- Search by location (city, postal code)
- Distance calculation from user location
- Filter by service type
- Favorites list
- Click-to-call, click-to-navigate
- Static seed data initially
- Future: Nominatim geocoding integration

---

### Phase 7: Polish & Deployment (Week 10)

#### UI/UX Enhancements

##### [MODIFY] Livewire Components
- Add loading states (wire:loading)
- Form validation with real-time feedback
- Toast notifications for success/error
- Responsive design (mobile-first)
- Dark mode support (optional)

##### [NEW] `resources/css/app.css`
- Tailwind CSS configuration
- Custom color palette
- Component styles
- Responsive utilities

#### Deployment Configuration

##### [NEW] Production Docker Setup
- `docker-compose.prod.yml` - Production overrides
- Environment variable management
- HTTPS/SSL configuration
- Database migration strategy

##### [NEW] CI/CD Pipeline (Optional)
- GitHub Actions workflow
- Automated testing
- Docker image building
- Deployment automation

---

## Database Schema

### Core Tables

```mermaid
erDiagram
    users ||--o{ vehicle_user : "owns/manages"
    vehicles ||--o{ vehicle_user : "owned by"
    vehicles ||--o{ service_records : "has"
    vehicles ||--o{ maintenance_reminders : "has"
    service_shops ||--o{ service_records : "performed at"
    users ||--o{ user_favorites : "favorites"
    service_shops ||--o{ user_favorites : "favorited by"

    users {
        bigint id PK
        string name
        string email UK
        string password
        enum role
        timestamps
    }

    vehicles {
        bigint id PK
        uuid uuid UK
        string vin UK
        string make
        string model
        int year
        string current_plate
        int current_mileage
        timestamps
    }

    vehicle_user {
        bigint id PK
        bigint user_id FK
        bigint vehicle_id FK
        enum role
        timestamps
    }

    service_records {
        bigint id PK
        bigint vehicle_id FK
        bigint shop_id FK
        date service_date
        int mileage
        string service_type
        text description
        decimal cost
        string receipt_path
        timestamps
    }

    maintenance_reminders {
        bigint id PK
        bigint vehicle_id FK
        string reminder_type
        string service_name
        int trigger_mileage
        date trigger_date
        json notification_methods
        boolean is_completed
        timestamps
    }

    service_shops {
        bigint id PK
        string name
        text address
        string city
        string postal_code
        decimal latitude
        decimal longitude
        string phone
        string email
        string website
        json services_offered
        timestamps
    }

    user_favorites {
        bigint id PK
        bigint user_id FK
        bigint shop_id FK
        timestamps
    }
```

---

## API Endpoints Summary

### Authentication
- `POST /api/register` - User registration
- `POST /api/login` - Login (returns token)
- `POST /api/logout` - Logout
- `GET /api/user` - Current user

### Vehicles
- `GET /api/vehicles` - List vehicles
- `POST /api/vehicles` - Create vehicle
- `GET /api/vehicles/{uuid}` - Get vehicle
- `PUT /api/vehicles/{uuid}` - Update vehicle
- `DELETE /api/vehicles/{uuid}` - Delete vehicle
- `POST /api/vehicles/decode-vin` - VIN decoder

### Service Records
- `GET /api/vehicles/{uuid}/services` - List services
- `POST /api/vehicles/{uuid}/services` - Create service
- `GET /api/services/{id}` - Get service
- `PUT /api/services/{id}` - Update service
- `DELETE /api/services/{id}` - Delete service

### Maintenance Reminders
- `GET /api/vehicles/{uuid}/reminders` - List reminders
- `POST /api/vehicles/{uuid}/reminders` - Create reminder
- `PUT /api/reminders/{id}` - Update reminder
- `POST /api/reminders/{id}/complete` - Mark complete
- `DELETE /api/reminders/{id}` - Delete reminder

### Service Shops
- `GET /api/shops` - Search shops
- `GET /api/shops/{id}` - Get shop
- `POST /api/shops/{id}/favorite` - Add favorite
- `DELETE /api/shops/{id}/favorite` - Remove favorite

---

## Verification Plan

### Automated Tests

**Backend Tests:**
```bash
# Unit tests
php artisan test --filter=VehicleTest
php artisan test --filter=ServiceRecordTest
php artisan test --filter=ReminderTest

# Feature tests (API endpoints)
php artisan test --filter=VehicleApiTest
php artisan test --filter=AuthApiTest

# Coverage report
php artisan test --coverage
```

**API Testing:**
```bash
# Using Postman/Insomnia collections
# Test all CRUD operations for each resource
# Verify authentication flows
# Test error handling and validation
```

### Manual Verification

**Web Application (Livewire):**
1. User registration and login flow
2. Vehicle registration with VIN decoding
3. Service history logging with receipt upload
4. Maintenance reminder creation and notifications
5. Service shop search and favorites
6. Responsive design on mobile browsers

**API Testing (for future mobile):**
1. Token authentication flow
2. All CRUD operations via API
3. File uploads via API
4. Error responses and validation messages
5. CORS configuration for mobile apps

**Docker Environment:**
```bash
# Verify all services are running
docker-compose ps

# Check logs
docker-compose logs -f nginx
docker-compose logs -f php

# Test Mailpit
# Visit http://localhost:8025
# Trigger reminder email and verify receipt
```

**Database Verification:**
```bash
# Run migrations
php artisan migrate:fresh --seed

# Verify schema
php artisan db:show
php artisan migrate:status

# Test data integrity
php artisan tinker
>>> Vehicle::with('serviceRecords')->first()
```

---

## Timeline Estimate

| Phase | Duration | Deliverables |
|-------|----------|--------------|
| **Phase 1: Foundation** | 2 weeks | Docker setup, Laravel installation, database schema |
| **Phase 2: Authentication** | 1 week | User registration, login, role management |
| **Phase 3: Vehicles** | 2 weeks | Vehicle CRUD, VIN decoding, ownership management |
| **Phase 4: Service History** | 2 weeks | Service logging, receipt uploads, timeline view |
| **Phase 5: Reminders** | 1 week | Reminder system, email notifications, scheduling |
| **Phase 6: Shop Lookup** | 1 week | Shop search, geolocation, favorites |
| **Phase 7: Polish** | 1 week | UI refinement, testing, deployment prep |
| **Total** | **10 weeks** | Full v1.0 web application with API ready for mobile |

---

## Next Steps

> [!IMPORTANT]
> **Decision Required: Frontend Technology**
> 
> Please confirm your preferred approach:
> 
> **Option A (Recommended):** Laravel + Livewire + API
> - Faster development with your existing skills
> - API built alongside for future mobile app
> - Can migrate to Next.js later if needed
> 
> **Option B:** Next.js (as per README)
> - Steeper learning curve
> - Better long-term separation
> - Requires 2-3 months learning investment

Once you confirm the frontend approach, I'll:
1. Set up the Docker environment
2. Initialize the Laravel application
3. Create the database migrations
4. Build the authentication system
5. Implement features phase by phase

---

## Additional Considerations

### VIN Decoding Service

**Options:**
1. **NHTSA API** (Free, US vehicles only)
   - `https://vpic.nhtsa.dot.gov/api/vehicles/DecodeVin/{vin}?format=json`
2. **VIN Decoder API** (Paid, global coverage)
3. **Manual fallback** - Always available

**Recommendation:** Start with NHTSA API + manual fallback.

### File Storage Strategy

**Current:** Local disk storage
- Receipts stored in `storage/app/receipts`
- Served via Laravel storage link

**Future Migration Path:**
- MinIO (self-hosted S3-compatible)
- AWS S3 with presigned URLs
- Laravel Filesystem abstraction makes this seamless

### Notification Strategy

**Phase 1 (Web):**
- Email notifications via Mailpit (dev) / Mailgun (prod)
- In-app notification bell (Livewire component)

**Phase 2 (Mobile):**
- Push notifications via Firebase Cloud Messaging
- Same Laravel notification class, different channel

### Scalability Considerations

**Current (v1.0):**
- SQLite for simplicity
- Database queue driver
- Local file storage

**Future Growth Path:**
- PostgreSQL for production
- Redis for queues and caching
- S3 for file storage
- Horizontal scaling with load balancer
- Read replicas for database

All architectural decisions support this migration path without code rewrites.

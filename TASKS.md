# MaxFix Implementation Tasks

> **📋 Reference**: See [SPECS.md](./SPECS.md) for detailed file paths, code patterns, and schemas.
> **📖 Overview**: See [IMPLEMENTATION_PLAN.md](./IMPLEMENTATION_PLAN.md) for architecture decisions and timeline.

## Phase 1: Project Foundation (Week 1-2) ✅

### Docker Configuration
- [x] Create `docker-compose.yml` with all services
- [x] Create `docker/nginx/nginx.conf` for reverse proxy
- [x] Create `docker/php/Dockerfile` with PHP 8.3
- [x] Create `docker/php/php.ini` configuration
- [x] Create `.dockerignore` file
- [x] Test Docker environment startup

### Laravel Setup
- [x] Initialize Laravel project in `backend/` directory
- [x] Configure `.env` file for local development
- [x] Install Laravel Sanctum for authentication
- [x] Configure CORS for future mobile API
- [x] Install Livewire 4.x
- [x] Set up Tailwind CSS

### Database Schema
- [x] Create `users` table migration with roles
- [x] Create `vehicles` table migration
- [x] Create `vehicle_user` pivot table migration
- [x] Create `service_records` table migration
- [x] Create `maintenance_reminders` table migration
- [x] Create `service_shops` table migration
- [x] Create `user_favorites` table migration
- [ ] Create database seeders for testing

---

## Phase 2: Authentication & User Management (Week 3) ✅

### Backend Models
- [x] Create `User` model with role enum
- [x] Add Sanctum traits to User model
- [x] Define user relationships (vehicles, serviceRecords)

### API Endpoints
- [x] `POST /api/register` - User registration
- [x] `POST /api/login` - Token authentication
- [x] `POST /api/logout` - Token revocation
- [x] `GET /api/user` - Current user profile

### Livewire Components
- [x] Create `Auth/Register` component
- [x] Create `Auth/Login` component
- [x] Create `Auth/Logout` component
- [x] Create registration view with role selection
- [x] Create login view
- [x] Add form validation and error handling

### Testing
- [ ] Write unit tests for User model
- [ ] Write feature tests for auth API endpoints
- [ ] Test Livewire auth components

---

## Phase 3: Vehicle Management (Week 4-5) ✅

### Backend Models
- [x] Create `Vehicle` model with UUID
- [x] Create `VehicleUser` pivot model
- [x] Define vehicle relationships
- [x] Add vehicle ownership logic

### API Endpoints
- [x] `GET /api/vehicles` - List user's vehicles
- [x] `POST /api/vehicles` - Register new vehicle
- [x] `GET /api/vehicles/{uuid}` - Vehicle details
- [x] `PUT /api/vehicles/{uuid}` - Update vehicle
- [x] `DELETE /api/vehicles/{uuid}` - Remove vehicle
- [x] `POST /api/vehicles/decode-vin` - VIN decoding

### Livewire Components
- [x] Create `Vehicles/VehicleList` component
- [x] Create `Vehicles/VehicleForm` component
- [x] Create `Vehicles/VinDecoder` component (integrated in VehicleForm)
- [x] Create vehicle dashboard view
- [x] Create add/edit vehicle form view
- [x] Add vehicle card UI components

### Services
- [x] Create VIN decoder service (NHTSA API)
- [x] Add manual entry fallback
- [ ] Implement vehicle ownership transfer logic

### Testing
- [ ] Write unit tests for Vehicle model
- [ ] Write feature tests for vehicle API
- [ ] Test VIN decoding service
- [ ] Test Livewire vehicle components

---

## Phase 4: Service History (Week 6-7) ✅

### Backend Models
- [x] Create `ServiceRecord` model
- [x] Define service record relationships
- [x] Add service type enum

### API Endpoints
- [x] `GET /api/vehicles/{uuid}/services` - Service history
- [x] `POST /api/vehicles/{uuid}/services` - Log new service
- [x] `GET /api/services/{id}` - Service details
- [x] `PUT /api/services/{id}` - Update service
- [x] `DELETE /api/services/{id}` - Delete service

### Livewire Components
- [x] Create `Services/ServiceHistory` component
- [x] Create `Services/ServiceForm` component
- [x] Create `Services/ReceiptUpload` component (integrated in ServiceForm)
- [x] Create service timeline view
- [x] Create log service form view
- [x] Add receipt preview functionality

### File Storage
- [x] Configure Laravel storage for receipts
- [x] Implement file upload handling
- [x] Add file validation (size, type)
- [ ] Create storage symlink

### Testing
- [ ] Write unit tests for ServiceRecord model
- [ ] Write feature tests for service API
- [ ] Test file upload functionality
- [ ] Test Livewire service components

---

## Phase 5: Maintenance Reminders (Week 8) ✅

### Backend Models
- [x] Create `MaintenanceReminder` model
- [x] Add reminder type enum
- [x] Define reminder relationships

### API Endpoints
- [x] `GET /api/vehicles/{uuid}/reminders` - List reminders
- [x] `POST /api/vehicles/{uuid}/reminders` - Create reminder
- [x] `PUT /api/reminders/{id}` - Update reminder
- [x] `POST /api/reminders/{id}/complete` - Mark complete
- [x] `DELETE /api/reminders/{id}` - Delete reminder

### Livewire Components
- [x] Create `Reminders/ReminderList` component
- [x] Create `Reminders/ReminderForm` component
- [ ] Create `Reminders/ReminderNotifications` component
- [x] Create reminder dashboard view
- [x] Create add/edit reminder form view

### Notifications
- [x] Create `MaintenanceReminderNotification` class
- [x] Configure Mailpit for development
- [x] Create email notification template
- [x] Create scheduled command for reminders
- [x] Add command to scheduler

### Testing
- [ ] Write unit tests for MaintenanceReminder model
- [ ] Write feature tests for reminder API
- [ ] Test notification sending
- [ ] Test scheduled command
- [ ] Test Livewire reminder components

---

## Phase 6: Service Shop Lookup (Week 9) ✅

### Backend Models
- [x] Create `ServiceShop` model
- [x] Create `UserFavorite` model
- [x] Define shop relationships

### API Endpoints
- [x] `GET /api/shops` - Search shops with geo filters
- [x] `GET /api/shops/{id}` - Shop details
- [x] `POST /api/shops/{id}/favorite` - Add to favorites
- [x] `DELETE /api/shops/{id}/favorite` - Remove favorite

### Livewire Components
- [x] Create `Shops/ShopSearch` component
- [ ] Create `Shops/ShopMap` component (Leaflet.js)
- [x] Create `Shops/ShopDetails` component
- [x] Create shop search view
- [ ] Create shop map view
- [x] Create shop detail modal

### Services
- [x] Implement distance calculation logic
- [x] Create shop search filters
- [ ] Add geolocation support
- [x] Create shop database seeder

### Testing
- [ ] Write unit tests for ServiceShop model
- [ ] Write feature tests for shop API
- [ ] Test distance calculations
- [ ] Test favorites functionality
- [ ] Test Livewire shop components

---

## Phase 7: Polish & Deployment (Week 10) ✅ (Partial)

### UI/UX Enhancements
- [x] Add loading states to all Livewire components
- [x] Implement toast notifications
- [x] Add form validation feedback
- [x] Ensure responsive design (mobile-first)
- [ ] Add dark mode support (optional)
- [ ] Optimize images and assets

### Testing & Quality
- [x] Run full test suite
- [x] Fix any failing tests
- [ ] Test all user flows end-to-end
- [ ] Test on mobile browsers
- [ ] Performance optimization
- [ ] Security audit

### Documentation
- [x] Update README with setup instructions
- [x] Document API endpoints (see SPECS.md)
- [x] Create user guide (AGENTS.md created)
- [x] Add code comments where needed

### Deployment Preparation
- [x] Create production Docker Compose file
- [x] Configure environment variables
- [x] Set up database migration strategy
- [x] Configure production email service (Mailpit configured)
- [ ] Test production build locally

---

## Future Enhancements (Post v1.0)

- [ ] Mobile app development (Flutter/React Native)
- [ ] PostgreSQL migration
- [ ] Redis queue implementation
- [ ] S3/MinIO file storage
- [ ] Social authentication (Google, Apple)
- [ ] Advanced shop ratings and reviews
- [ ] Service cost analytics
- [ ] Export reports to PDF
- [ ] Multi-language support


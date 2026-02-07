# MaxFix Implementation Tasks

> **📋 Reference**: See [SPECS.md](./SPECS.md) for detailed file paths, code patterns, and schemas.
> **📖 Overview**: See [IMPLEMENTATION_PLAN.md](./IMPLEMENTATION_PLAN.md) for architecture decisions and timeline.

## Phase 1: Project Foundation (Week 1-2)

### Docker Configuration
- [/] Create `docker-compose.yml` with all services
- [ ] Create `docker/nginx/nginx.conf` for reverse proxy
- [ ] Create `docker/php/Dockerfile` with PHP 8.3
- [ ] Create `docker/php/php.ini` configuration
- [ ] Create `.dockerignore` file
- [ ] Test Docker environment startup

### Laravel Setup
- [ ] Initialize Laravel project in `backend/` directory
- [ ] Configure `.env` file for local development
- [ ] Install Laravel Sanctum for authentication
- [ ] Configure CORS for future mobile API
- [ ] Install Livewire 3
- [ ] Set up Tailwind CSS

### Database Schema
- [ ] Create `users` table migration with roles
- [ ] Create `vehicles` table migration
- [ ] Create `vehicle_user` pivot table migration
- [ ] Create `service_records` table migration
- [ ] Create `maintenance_reminders` table migration
- [ ] Create `service_shops` table migration
- [ ] Create `user_favorites` table migration
- [ ] Create database seeders for testing

---

## Phase 2: Authentication & User Management (Week 3)

### Backend Models
- [ ] Create `User` model with role enum
- [ ] Add Sanctum traits to User model
- [ ] Define user relationships (vehicles, serviceRecords)

### API Endpoints
- [ ] `POST /api/register` - User registration
- [ ] `POST /api/login` - Token authentication
- [ ] `POST /api/logout` - Token revocation
- [ ] `GET /api/user` - Current user profile

### Livewire Components
- [ ] Create `Auth/Register` component
- [ ] Create `Auth/Login` component
- [ ] Create `Auth/Logout` component
- [ ] Create registration view with role selection
- [ ] Create login view
- [ ] Add form validation and error handling

### Testing
- [ ] Write unit tests for User model
- [ ] Write feature tests for auth API endpoints
- [ ] Test Livewire auth components

---

## Phase 3: Vehicle Management (Week 4-5)

### Backend Models
- [ ] Create `Vehicle` model with UUID
- [ ] Create `VehicleUser` pivot model
- [ ] Define vehicle relationships
- [ ] Add vehicle ownership logic

### API Endpoints
- [ ] `GET /api/vehicles` - List user's vehicles
- [ ] `POST /api/vehicles` - Register new vehicle
- [ ] `GET /api/vehicles/{uuid}` - Vehicle details
- [ ] `PUT /api/vehicles/{uuid}` - Update vehicle
- [ ] `DELETE /api/vehicles/{uuid}` - Remove vehicle
- [ ] `POST /api/vehicles/decode-vin` - VIN decoding

### Livewire Components
- [ ] Create `Vehicles/VehicleList` component
- [ ] Create `Vehicles/VehicleForm` component
- [ ] Create `Vehicles/VinDecoder` component
- [ ] Create vehicle dashboard view
- [ ] Create add/edit vehicle form view
- [ ] Add vehicle card UI components

### Services
- [ ] Create VIN decoder service (NHTSA API)
- [ ] Add manual entry fallback
- [ ] Implement vehicle ownership transfer logic

### Testing
- [ ] Write unit tests for Vehicle model
- [ ] Write feature tests for vehicle API
- [ ] Test VIN decoding service
- [ ] Test Livewire vehicle components

---

## Phase 4: Service History (Week 6-7)

### Backend Models
- [ ] Create `ServiceRecord` model
- [ ] Define service record relationships
- [ ] Add service type enum

### API Endpoints
- [ ] `GET /api/vehicles/{uuid}/services` - Service history
- [ ] `POST /api/vehicles/{uuid}/services` - Log new service
- [ ] `GET /api/services/{id}` - Service details
- [ ] `PUT /api/services/{id}` - Update service
- [ ] `DELETE /api/services/{id}` - Delete service

### Livewire Components
- [ ] Create `Services/ServiceHistory` component
- [ ] Create `Services/ServiceForm` component
- [ ] Create `Services/ReceiptUpload` component
- [ ] Create service timeline view
- [ ] Create log service form view
- [ ] Add receipt preview functionality

### File Storage
- [ ] Configure Laravel storage for receipts
- [ ] Implement file upload handling
- [ ] Add file validation (size, type)
- [ ] Create storage symlink

### Testing
- [ ] Write unit tests for ServiceRecord model
- [ ] Write feature tests for service API
- [ ] Test file upload functionality
- [ ] Test Livewire service components

---

## Phase 5: Maintenance Reminders (Week 8)

### Backend Models
- [ ] Create `MaintenanceReminder` model
- [ ] Add reminder type enum
- [ ] Define reminder relationships

### API Endpoints
- [ ] `GET /api/vehicles/{uuid}/reminders` - List reminders
- [ ] `POST /api/vehicles/{uuid}/reminders` - Create reminder
- [ ] `PUT /api/reminders/{id}` - Update reminder
- [ ] `POST /api/reminders/{id}/complete` - Mark complete
- [ ] `DELETE /api/reminders/{id}` - Delete reminder

### Livewire Components
- [ ] Create `Reminders/ReminderList` component
- [ ] Create `Reminders/ReminderForm` component
- [ ] Create `Reminders/ReminderNotifications` component
- [ ] Create reminder dashboard view
- [ ] Create add/edit reminder form view

### Notifications
- [ ] Create `MaintenanceReminderNotification` class
- [ ] Configure Mailpit for development
- [ ] Create email notification template
- [ ] Create scheduled command for reminders
- [ ] Add command to scheduler

### Testing
- [ ] Write unit tests for MaintenanceReminder model
- [ ] Write feature tests for reminder API
- [ ] Test notification sending
- [ ] Test scheduled command
- [ ] Test Livewire reminder components

---

## Phase 6: Service Shop Lookup (Week 9)

### Backend Models
- [ ] Create `ServiceShop` model
- [ ] Create `UserFavorite` model
- [ ] Define shop relationships

### API Endpoints
- [ ] `GET /api/shops` - Search shops with geo filters
- [ ] `GET /api/shops/{id}` - Shop details
- [ ] `POST /api/shops/{id}/favorite` - Add to favorites
- [ ] `DELETE /api/shops/{id}/favorite` - Remove favorite

### Livewire Components
- [ ] Create `Shops/ShopSearch` component
- [ ] Create `Shops/ShopMap` component (Leaflet.js)
- [ ] Create `Shops/ShopDetails` component
- [ ] Create shop search view
- [ ] Create shop map view
- [ ] Create shop detail modal

### Services
- [ ] Implement distance calculation logic
- [ ] Create shop search filters
- [ ] Add geolocation support
- [ ] Create shop database seeder

### Testing
- [ ] Write unit tests for ServiceShop model
- [ ] Write feature tests for shop API
- [ ] Test distance calculations
- [ ] Test favorites functionality
- [ ] Test Livewire shop components

---

## Phase 7: Polish & Deployment (Week 10)

### UI/UX Enhancements
- [ ] Add loading states to all Livewire components
- [ ] Implement toast notifications
- [ ] Add form validation feedback
- [ ] Ensure responsive design (mobile-first)
- [ ] Add dark mode support (optional)
- [ ] Optimize images and assets

### Testing & Quality
- [ ] Run full test suite
- [ ] Fix any failing tests
- [ ] Test all user flows end-to-end
- [ ] Test on mobile browsers
- [ ] Performance optimization
- [ ] Security audit

### Documentation
- [ ] Update README with setup instructions
- [ ] Document API endpoints
- [ ] Create user guide
- [ ] Add code comments where needed

### Deployment Preparation
- [ ] Create production Docker Compose file
- [ ] Configure environment variables
- [ ] Set up database migration strategy
- [ ] Configure production email service
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


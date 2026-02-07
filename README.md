# MaxFix

Vehicle service management application (early scaffold).

## Vision

To create a user-friendly application that empowers car owners and small fleet managers to easily track and manage their vehicle's service history, receive timely maintenance reminders, and connect with service providers.

## Project Overview

MaxFix provides vehicle registration, service history logging, maintenance reminders, and shop lookup tools with a containerized dev environment and a Laravel + Next.js stack.

## Core Concepts

- Vehicle-centric: primary entity is the vehicle, preserving service history across ownership.
- User roles: Car Owners, Fleet Managers (≤10 vehicles), Service Personnel.

## Target Audience

- General car owners (single car or family).
- Managers of small fleets (10 vehicles or less).

## Key Features (v1.0)

1. Vehicle Registration:
   - VIN-based entry with optional VIN decoding to pre-fill make/model/year.
   - Manual entry fallback.
2. User & Role Management:
   - Registration, login, roles, attach owners/managers to vehicles.
3. Service History Logging:
   - Log services with date, mileage, parts, cost, receipts.
4. Maintenance Reminders:
   - Reminders by mileage/time; push/email options.
5. Service Shop Lookup:
   - Search nearby shops with details and favorites.

## Technology Stack

- Containerization: Docker & Docker Compose
- Web Server: Nginx (reverse proxy)
- Backend: Laravel (PHP 8.3, PHP-FPM)
- Frontend: Livewire 3 + Tailwind CSS (with parallel API for mobile)
- Database: SQLite for local dev (Postgres/MySQL planned)
- Queue: database driver (Redis planned)
- Email: Mailpit (SMTP + web UI)
- Storage: Local disk now; MinIO/S3 planned

## Stack Summary

- Backend: Laravel (PHP 8.3) via php-fpm
- Frontend: Livewire 3 (Blade + Alpine.js, Tailwind CSS)
- API: RESTful JSON API (Sanctum auth, ready for mobile apps)
- Reverse Proxy: Nginx (port 8080) -> all routes to Laravel
- Database: SQLite (local dev) with planned migration to PostgreSQL
- Queue: Database driver (Redis planned later)
- Email: Mailpit (SMTP + web UI on port 8025)
- Storage: Local disk (abstraction ready for future MinIO/S3)

## Baseline Decisions

| Concern | Current | Future Path |
|---------|---------|-------------|
| DB | SQLite file | PostgreSQL migration (add pdo_pgsql) |
| Auth | Laravel Sanctum | Social login (Google, Apple), possible Passport if external OAuth needed |
| Queue | database | Redis service + horizon/style monitoring |
| Vehicle ID | UUID + plate | Plate history table for changes |
| Geo/Shops | Static seed + later Nominatim | Optional Google Places adapter |
| Storage | Local disk | MinIO/S3 with presigned URLs |
| Email | Mailpit | Mailgun/SES via MailerInterface |
| PHP | 8.3 | Periodic minor updates |
| Livewire | 3.x | Stay current with Laravel releases |


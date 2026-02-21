# MaxFix Browser Testing Plan

This document outlines a comprehensive plan for testing the frontend features of the MaxFix application using a browser interface. This plan can be followed manually or executed by an automated AI browser subagent.

## 1. Authentication & Onboarding
**Objective:** Verify that users can register, log in, and are directed to the correct starting pages.
- **Register new user:** 
  - Navigate to `/register`.
  - Fill out the registration form (name, email, password, password confirmation).
  - Submit the form and verify successful redirection to the `/vehicles` dashboard.
- **Login existing user:**
  - Navigate to `/login`.
  - Enter valid credentials.
  - Submit and verify redirection to `/vehicles`.
- **Home page redirection:**
  - When authenticated, navigate to `/`. Verify automatic redirect to `/vehicles`.
  - When logged out, navigate to `/`. Verify the home page content is visible.
- **Protected routes:**
  - While logged out, attempt to navigate to `/vehicles` or other protected routes. Verify redirect to `/login`.

## 2. Vehicle Management
**Objective:** Verify CRUD operations for user vehicles.
- **View vehicles list (`/vehicles`):**
  - Navigate to `/vehicles`.
  - Verify the page loads and displays existing vehicles or an empty state message if none exist.
- **Add a new vehicle (`/vehicles/create`):**
  - Click "Add Vehicle" or navigate to `/vehicles/create`.
  - Form validation: Submit an empty form and verify specific Livewire validation errors appear.
  - Fill out the vehicle make, model, year, and any other required fields.
  - Submit valid data and verify the new vehicle appears in the list on `/vehicles` with a success toast/message.
- **Edit an existing vehicle (`/vehicles/{id}/edit`):**
  - From `/vehicles`, click edit on a specific vehicle.
  - Change a detail (e.g., the year, mileage, or color) and save.
  - Verify the updated information is accurately reflected on the `/vehicles` page.

## 3. Service History
**Objective:** Verify that users can log and view maintenance services for specific vehicles.
- **View service history (`/vehicles/{id}/services`):**
  - From a vehicle card, navigate to its services history.
  - Verify the list of past services is displayed in chronological order.
- **Add a service record (`/vehicles/{id}/services/create`):**
  - Click to add a new service record.
  - Enter service details (date, type of service, cost, shop, notes).
  - Submit and verify the new record appears in the service history list and the vehicle's total maintenance cost updates if applicable.
- **Edit a service record (`/vehicles/{id}/services/{service_id}/edit`):**
  - Open an existing service record for editing.
  - Modify costs or notes, then submit.
  - Verify the updated service details.

## 4. Maintenance Reminders
**Objective:** Verify that users can set and manage upcoming maintenance reminders.
- **View reminders (`/vehicles/{id}/reminders`):**
  - Navigate to the reminders page for a vehicle.
  - Verify the list of active, upcoming, and past/overdue reminders based on date or mileage.
- **Create a reminder (`/vehicles/{id}/reminders/create`):**
  - Click to create a new reminder.
  - Enter the reminder task (e.g., "Oil Change"), due date, or due mileage threshold.
  - Save and verify the reminder appears in the active reminders list.
- **Edit/Complete a reminder (`/reminders/{id}/edit`):**
  - Edit an existing reminder.
  - Mark the reminder as completed.
  - Save and verify it moves to a completed state or disappears from the immediate active alerts list.

## 5. Service Shop Search & Map
**Objective:** Verify that users can search for maintenance shops and view them geographically.
- **Search shops (List view - `/shops`):**
  - Navigate to `/shops`.
  - Use search inputs (location, shop name, or service type).
  - Verify the list dynamically updates with relevant results without full page reloads (Livewire functionality).
- **Map view (`/shops/map`):**
  - Navigate to `/shops/map`.
  - Verify the map canvas loads correctly (check browser console for Javascript API errors).
  - Check if map markers render for available shops in the viewable coordinates.
  - Click on a map marker to confirm an info window or details card pops up containing shop name, rating, and address.

## Execution Requirements
To execute this plan using a browser testing tool or subagent:
1. Ensure the local Laravel development server is running (`php artisan serve` or `herd`).
2. Ensure the Vite development server is running (`npm run dev`) for TailwindCSS to compile correctly.
3. Use a pre-seeded database with generic test users, vehicles, and shops to make navigation easier, or start with creating a fresh user.

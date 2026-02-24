## 1. Backend Changes

- [x] 1.1 Add `$hasVehicles` computed property to `Home.php` Livewire component
- [x] 1.2 Pass `$hasVehicles` to view in render method

## 2. Frontend Changes

- [x] 2.1 Add conditional rendering in `home.blade.php` for empty fleet state
- [x] 2.2 Create empty state UI with "Add your first vehicle" heading and description
- [x] 2.3 Add "Add Vehicle" CTA button linking to `route('vehicles.create')`
- [x] 2.4 Ensure existing health score section displays when vehicles exist

## 3. Testing

- [x] 3.1 Verify empty state displays when user has zero vehicles
- [x] 3.2 Verify health score displays when user has one or more vehicles
- [x] 3.3 Verify "Add Vehicle" button navigates to vehicle creation page
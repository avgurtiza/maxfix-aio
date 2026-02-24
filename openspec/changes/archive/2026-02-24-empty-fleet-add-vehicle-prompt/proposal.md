## Why

When a user has zero vehicles in their fleet, the dashboard displays "Your fleet is 100% healthy" with "0 of 0 vehicles are fully up to date on maintenance." This is misleading and unhelpful—it suggests success when there's nothing to maintain. The user should be prompted to add their first vehicle via the vehicles page instead of seeing an empty fleet health score.

## What Changes

- Add conditional rendering to the dashboard health score section
- When `totalVehicles === 0`, display an empty state prompting user to add a vehicle
- Show "Add your first vehicle" call-to-action with link to vehicles page
- Maintain existing health score display for users with 1+ vehicles

## Capabilities

### New Capabilities

- `empty-fleet-prompt`: Empty state UI that guides users to add their first vehicle when fleet is empty

### Modified Capabilities

None. This is a new UI condition, not a change to existing spec requirements.

## Impact

- **Affected files**: 
  - `app/src/app/Livewire/Home.php` (add `hasVehicles` computed property)
  - `app/src/resources/views/livewire/home.blade.php` (conditional rendering)
- **No API changes**
- **No database changes**
- **No breaking changes**
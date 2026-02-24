## Context

The dashboard health score section currently shows "Your fleet is 100% healthy" regardless of whether the user has vehicles. When `totalVehicles === 0`, the health score defaults to 100%, which is misleading.

**Current Implementation** (`Home.php`):
```php
$healthScore = $totalVehicles > 0 ? round(($healthyVehicles / $totalVehicles) * 100) : 100;
```

**Current View** (`home.blade.php`):
- Always displays health score circle and "X of Y vehicles" message
- Link to vehicles page exists but is secondary ("View Details" button)

## Goals / Non-Goals

**Goals:**
- Display helpful empty state when user has zero vehicles
- Guide new users to add their first vehicle
- Maintain existing health score display for users with vehicles

**Non-Goals:**
- Changing the health score calculation logic
- Modifying the vehicles page
- Adding onboarding flow beyond this single prompt

## Decisions

### 1. Conditional Rendering in View (vs Component Logic)
**Decision**: Add condition in Blade view, not PHP component.
**Rationale**: Simpler change, keeps logic in presentation layer where the display decision lives.

### 2. Empty State Design
**Decision**: Replace entire health score section with empty state card.
**Rationale**: Cleaner than showing "0 of 0" with a health percentage. Matches common empty state patterns.

**Empty State Content**:
- Icon: Vehicle illustration or heroicon
- Heading: "Add your first vehicle"
- Description: "Start tracking your fleet's maintenance and health."
- CTA button: "Add Vehicle" → `route('vehicles.create')`

### 3. Computed Property for Has Vehicles
**Decision**: Add `$hasVehicles` boolean to component data.
**Rationale**: Cleaner template condition than checking `$totalVehicles > 0` in Blade.

## Risks / Trade-offs

- **Risk**: Users with 0 vehicles see different layout → **Mitigation**: Empty state matches existing card styling, consistent experience
- **Trade-off**: Adds condition to view → **Acceptable**: Simple conditional, well-scoped
## ADDED Requirements

### Requirement: Empty fleet state displays add vehicle prompt
When a user has zero vehicles in their fleet, the dashboard SHALL display an empty state prompting them to add their first vehicle instead of showing a health score.

#### Scenario: User with zero vehicles sees empty state
- **WHEN** user has zero vehicles in their fleet
- **THEN** dashboard displays empty state with "Add your first vehicle" heading
- **AND** dashboard displays description about tracking maintenance
- **AND** dashboard displays "Add Vehicle" button linking to vehicle creation page

#### Scenario: User with one or more vehicles sees health score
- **WHEN** user has one or more vehicles in their fleet
- **THEN** dashboard displays the existing health score section
- **AND** health score shows percentage based on vehicles without active reminders
- **AND** text shows "X of Y vehicles are fully up to date on maintenance"

### Requirement: Add vehicle button navigates to vehicle creation
The empty state "Add Vehicle" button SHALL navigate to the vehicle creation page.

#### Scenario: Clicking Add Vehicle button
- **WHEN** user clicks "Add Vehicle" button in empty state
- **THEN** system navigates to the vehicle creation page (`vehicles.create` route)
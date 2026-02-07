<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\MaintenanceReminderResource;
use App\Models\MaintenanceReminder;
use App\Models\Vehicle;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ReminderController extends Controller
{
    public function index(Request $request, Vehicle $vehicle): AnonymousResourceCollection
    {
        $this->authorize('view', $vehicle);

        $reminders = $vehicle->reminders()->with('creator')->get();

        return MaintenanceReminderResource::collection($reminders);
    }

    public function store(Request $request, Vehicle $vehicle): JsonResponse
    {
        $this->authorize('view', $vehicle);

        $validated = $request->validate([
            'service_name' => ['required', 'string', 'max:100'],
            'reminder_type' => ['required', 'in:time,mileage,both'],
            'trigger_mileage' => ['nullable', 'integer', 'min:1'],
            'trigger_days' => ['nullable', 'integer', 'min:1'],
            'next_due_date' => ['nullable', 'date'],
            'next_due_mileage' => ['nullable', 'integer', 'min:0'],
            'notification_methods' => ['required', 'array'],
            'notification_methods.*' => ['in:email,push'],
        ]);

        if ($validated['reminder_type'] === 'time' || $validated['reminder_type'] === 'both') {
            if (empty($validated['trigger_days']) && empty($validated['next_due_date'])) {
                return response()->json([
                    'message' => 'Either trigger_days or next_due_date is required for time-based reminders.',
                ], 422);
            }
        }

        if ($validated['reminder_type'] === 'mileage' || $validated['reminder_type'] === 'both') {
            if (empty($validated['trigger_mileage']) && empty($validated['next_due_mileage'])) {
                return response()->json([
                    'message' => 'Either trigger_mileage or next_due_mileage is required for mileage-based reminders.',
                ], 422);
            }
        }

        $reminder = $vehicle->reminders()->create([
            ...$validated,
            'created_by' => $request->user()->id,
            'is_active' => true,
        ]);

        return response()->json(new MaintenanceReminderResource($reminder), 201);
    }

    public function update(Request $request, MaintenanceReminder $reminder): JsonResponse
    {
        $this->authorize('update', $reminder);

        $validated = $request->validate([
            'service_name' => ['sometimes', 'string', 'max:100'],
            'reminder_type' => ['sometimes', 'in:time,mileage,both'],
            'trigger_mileage' => ['nullable', 'integer', 'min:1'],
            'trigger_days' => ['nullable', 'integer', 'min:1'],
            'next_due_date' => ['nullable', 'date'],
            'next_due_mileage' => ['nullable', 'integer', 'min:0'],
            'notification_methods' => ['sometimes', 'array'],
            'notification_methods.*' => ['in:email,push'],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        $reminder->update($validated);

        return response()->json(new MaintenanceReminderResource($reminder->fresh()));
    }

    public function complete(Request $request, MaintenanceReminder $reminder): JsonResponse
    {
        $this->authorize('update', $reminder);

        $validated = $request->validate([
            'current_mileage' => ['required', 'integer', 'min:0'],
        ]);

        $reminder->markCompleted($validated['current_mileage']);

        return response()->json([
            'message' => 'Reminder marked as complete.',
            'reminder' => new MaintenanceReminderResource($reminder->fresh()),
        ]);
    }

    public function destroy(Request $request, MaintenanceReminder $reminder): JsonResponse
    {
        $this->authorize('delete', $reminder);

        $reminder->delete();

        return response()->json(['message' => 'Reminder deleted successfully.']);
    }
}

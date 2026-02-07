<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\VehicleResource;
use App\Models\Vehicle;
use App\Services\VinDecoderService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class VehicleController extends Controller
{
    public function __construct(
        private VinDecoderService $vinDecoder
    ) {}

    public function index(Request $request): AnonymousResourceCollection
    {
        $vehicles = $request->user()
            ->vehicles()
            ->with(['serviceRecords' => fn ($q) => $q->latest()->limit(3)])
            ->get();

        return VehicleResource::collection($vehicles);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'vin' => ['nullable', 'string', 'size:17', 'unique:vehicles,vin'],
            'make' => ['required', 'string', 'max:50'],
            'model' => ['required', 'string', 'max:50'],
            'year' => ['required', 'integer', 'min:1900', 'max:'.(date('Y') + 1)],
            'current_plate' => ['nullable', 'string', 'max:20'],
            'current_mileage' => ['nullable', 'integer', 'min:0'],
            'color' => ['nullable', 'string', 'max:30'],
            'fuel_type' => ['nullable', 'in:gasoline,diesel,electric,hybrid'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        if ($request->user()->isFleetManager()) {
            $vehicleCount = $request->user()->vehicles()->count();
            if ($vehicleCount >= 10) {
                return response()->json([
                    'message' => 'Fleet managers can manage up to 10 vehicles.',
                ], 422);
            }
        }

        $vehicle = Vehicle::create($validated);

        $request->user()->vehicles()->attach($vehicle->id, [
            'relationship' => 'owner',
            'is_primary' => true,
        ]);

        return response()->json(new VehicleResource($vehicle), 201);
    }

    public function show(Request $request, Vehicle $vehicle): VehicleResource
    {
        $this->authorize('view', $vehicle);

        $vehicle->load(['serviceRecords.shop', 'activeReminders']);

        return new VehicleResource($vehicle);
    }

    public function update(Request $request, Vehicle $vehicle): VehicleResource
    {
        $this->authorize('update', $vehicle);

        $validated = $request->validate([
            'vin' => ['nullable', 'string', 'size:17', 'unique:vehicles,vin,'.$vehicle->id],
            'make' => ['sometimes', 'string', 'max:50'],
            'model' => ['sometimes', 'string', 'max:50'],
            'year' => ['sometimes', 'integer', 'min:1900', 'max:'.(date('Y') + 1)],
            'current_plate' => ['nullable', 'string', 'max:20'],
            'current_mileage' => ['nullable', 'integer', 'min:0'],
            'color' => ['nullable', 'string', 'max:30'],
            'fuel_type' => ['nullable', 'in:gasoline,diesel,electric,hybrid'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $vehicle->update($validated);

        return new VehicleResource($vehicle);
    }

    public function destroy(Request $request, Vehicle $vehicle): JsonResponse
    {
        $this->authorize('delete', $vehicle);

        $vehicle->delete();

        return response()->json(['message' => 'Vehicle deleted successfully']);
    }

    public function decodeVin(Request $request): JsonResponse
    {
        $request->validate([
            'vin' => ['required', 'string', 'size:17'],
        ]);

        $result = $this->vinDecoder->decode($request->vin);

        if ($result['success']) {
            return response()->json($result['data']);
        }

        return response()->json([
            'message' => 'Unable to decode VIN. Please enter vehicle details manually.',
        ], 422);
    }
}

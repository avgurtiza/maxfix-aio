<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ServiceRecordResource;
use App\Models\ServiceRecord;
use App\Models\Vehicle;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Storage;

class ServiceRecordController extends Controller
{
    public function index(Request $request, Vehicle $vehicle): AnonymousResourceCollection
    {
        $this->authorize('view', $vehicle);

        $records = $vehicle->serviceRecords()
            ->with(['shop'])
            ->orderByDesc('service_date')
            ->get();

        return ServiceRecordResource::collection($records);
    }

    public function store(Request $request, Vehicle $vehicle): JsonResponse
    {
        $this->authorize('update', $vehicle);

        $validated = $request->validate([
            'shop_id' => ['nullable', 'exists:service_shops,id'],
            'service_date' => ['required', 'date'],
            'mileage' => ['nullable', 'integer', 'min:0'],
            'service_type' => ['required', 'in:oil_change,tire_rotation,brake_service,transmission,engine,electrical,air_conditioning,suspension,inspection,other'],
            'description' => ['nullable', 'string', 'max:2000'],
            'cost' => ['nullable', 'numeric', 'min:0'],
            'receipt' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:10240'],
        ]);

        $receiptPath = null;
        if ($request->hasFile('receipt')) {
            $receiptPath = $request->file('receipt')->store('receipts/'.$vehicle->uuid, 'public');
        }

        $record = ServiceRecord::create([
            'vehicle_id' => $vehicle->id,
            'shop_id' => $validated['shop_id'] ?? null,
            'created_by' => $request->user()->id,
            'service_date' => $validated['service_date'],
            'mileage' => $validated['mileage'] ?? null,
            'service_type' => $validated['service_type'],
            'description' => $validated['description'] ?? null,
            'cost' => $validated['cost'] ?? null,
            'receipt_path' => $receiptPath,
        ]);

        if ($validated['mileage'] ?? null) {
            $vehicle->updateMileage($validated['mileage']);
        }

        $record->load(['shop']);

        return response()->json(new ServiceRecordResource($record), 201);
    }

    public function show(Request $request, ServiceRecord $service): ServiceRecordResource
    {
        $this->authorize('view', $service);

        $service->load(['shop', 'vehicle', 'creator']);

        return new ServiceRecordResource($service);
    }

    public function update(Request $request, ServiceRecord $service): ServiceRecordResource
    {
        $this->authorize('update', $service);

        $validated = $request->validate([
            'shop_id' => ['nullable', 'exists:service_shops,id'],
            'service_date' => ['sometimes', 'date'],
            'mileage' => ['nullable', 'integer', 'min:0'],
            'service_type' => ['sometimes', 'in:oil_change,tire_rotation,brake_service,transmission,engine,electrical,air_conditioning,suspension,inspection,other'],
            'description' => ['nullable', 'string', 'max:2000'],
            'cost' => ['nullable', 'numeric', 'min:0'],
            'receipt' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:10240'],
        ]);

        if ($request->hasFile('receipt')) {
            if ($service->receipt_path) {
                Storage::disk('public')->delete($service->receipt_path);
            }
            $validated['receipt_path'] = $request->file('receipt')->store('receipts/'.$service->vehicle->uuid, 'public');
        }

        $service->update($validated);

        if ($validated['mileage'] ?? null) {
            $service->vehicle->updateMileage($validated['mileage']);
        }

        $service->load(['shop']);

        return new ServiceRecordResource($service);
    }

    public function destroy(Request $request, ServiceRecord $service): JsonResponse
    {
        $this->authorize('delete', $service);

        if ($service->receipt_path) {
            Storage::disk('public')->delete($service->receipt_path);
        }

        $service->delete();

        return response()->json(['message' => 'Service record deleted successfully']);
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('service_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicle_id')->constrained()->cascadeOnDelete();
            $table->foreignId('shop_id')->nullable()->constrained('service_shops')->nullOnDelete();
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->date('service_date');
            $table->unsignedInteger('mileage');
            $table->enum('service_type', [
                'oil_change',
                'tire_rotation',
                'brake_service',
                'transmission',
                'engine',
                'electrical',
                'air_conditioning',
                'suspension',
                'inspection',
                'other',
            ]);
            $table->text('description')->nullable();
            $table->decimal('cost', 10, 2)->nullable();
            $table->string('receipt_path')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('service_records');
    }
};

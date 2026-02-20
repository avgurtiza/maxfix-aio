<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('vin', 17)->nullable()->unique();
            $table->string('make', 50);
            $table->string('model', 50);
            $table->year('year');
            $table->string('current_plate', 20)->nullable();
            $table->unsignedInteger('current_mileage')->default(0);
            $table->string('color', 30)->nullable();
            $table->string('fuel_type', 20)->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};

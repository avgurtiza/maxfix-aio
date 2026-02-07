<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('maintenance_reminders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicle_id')->constrained()->cascadeOnDelete();
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->string('service_name', 100);
            $table->enum('reminder_type', ['mileage', 'date', 'both'])->default('both');
            $table->unsignedInteger('trigger_mileage')->nullable();
            $table->unsignedInteger('trigger_days')->nullable();
            $table->date('next_due_date')->nullable();
            $table->unsignedInteger('next_due_mileage')->nullable();
            $table->json('notification_methods')->default('["email"]');
            $table->boolean('is_active')->default(true);
            $table->timestamp('last_notified_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('maintenance_reminders');
    }
};

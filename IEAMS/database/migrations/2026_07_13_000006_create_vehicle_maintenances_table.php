<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vehicle_maintenances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicle_id')->constrained()->onDelete('cascade');
            $table->string('maintenance_type'); // repair, servicing, inspection
            $table->date('maintenance_date');
            $table->date('completion_date')->nullable();
            $table->string('workshop_name')->nullable();
            $table->text('description');
            $table->decimal('cost', 15, 2)->default(0);
            $table->string('status')->default('ongoing'); // ongoing, completed
            $table->string('previous_vehicle_status')->nullable(); // to restore status after maintenance
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vehicle_maintenances');
    }
};

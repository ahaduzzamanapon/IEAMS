<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->string('vehicle_number')->unique();
            $table->string('registration_number')->unique();
            $table->date('registration_date');
            $table->date('registration_expiry_date');
            $table->string('fitness_certificate_number');
            $table->date('fitness_issue_date');
            $table->date('fitness_expiry_date');
            $table->string('vehicle_type');
            $table->string('vehicle_category');
            $table->string('vehicle_name');
            $table->string('brand');
            $table->string('model')->nullable();
            $table->integer('manufacturing_year')->nullable();
            $table->string('color');
            $table->string('fuel_type');
            $table->decimal('fuel_quantity', 10, 2);
            $table->string('chassis_number')->unique();
            $table->string('engine_number')->unique();
            $table->integer('seating_capacity')->nullable();
            $table->string('status')->default('available'); // available, assigned, in_use, under_maintenance, out_of_service, accident, disposed
            $table->timestamps();
        });

        Schema::create('drivers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('father_name');
            $table->string('mobile');
            $table->string('nid');
            $table->string('driving_license_number')->unique();
            $table->date('license_issue_date');
            $table->date('license_expiry_date')->nullable();
            $table->string('license_category')->nullable();
            $table->string('blood_group')->nullable();
            $table->text('permanent_address');
            $table->text('present_address');
            $table->string('emergency_contact');
            $table->string('status')->default('active'); // active, inactive
            $table->timestamps();
        });

        Schema::create('vehicle_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicle_id')->constrained()->onDelete('cascade');
            $table->string('assigned_office');
            $table->foreignId('assigned_officer_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('assigned_driver_id')->constrained('drivers')->onDelete('cascade');
            $table->date('assignment_date');
            $table->text('purpose');
            $table->date('expected_return_date')->nullable();
            $table->date('actual_return_date')->nullable();
            $table->string('status')->default('active'); // active, returned
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicle_assignments');
        Schema::dropIfExists('drivers');
        Schema::dropIfExists('vehicles');
    }
};

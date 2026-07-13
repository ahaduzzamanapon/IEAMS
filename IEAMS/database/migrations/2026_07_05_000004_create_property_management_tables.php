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
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('project_id_code')->unique();
            $table->string('project_code')->unique();
            $table->string('name');
            $table->string('division');
            $table->string('district');
            $table->string('upazila');
            $table->text('mouza'); // Comma-separated list of Mouzas or JSON
            $table->decimal('total_land', 15, 2);
            $table->decimal('total_road_land', 15, 2);
            $table->decimal('estimated_project_cost', 15, 2)->nullable();
            $table->integer('total_planned_plot');
            $table->integer('total_planned_apartment');
            $table->date('project_start_date');
            $table->date('expected_completion_date')->nullable();
            $table->string('status')->default('planning'); // planning, ongoing, completed, closed
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('lands', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->onDelete('cascade');
            $table->date('purchase_date');
            $table->decimal('purchase_value', 15, 2);
            $table->text('seller_information');
            $table->string('deed_number');
            $table->date('registration_date');
            $table->string('khatian_number'); // multiple/comma-separated
            $table->string('dag_number'); // multiple/comma-separated
            $table->decimal('land_amount', 15, 2);
            $table->string('land_classification');
            $table->string('land_map_path')->nullable();
            $table->timestamps();
        });

        Schema::create('plots', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->onDelete('cascade');
            $table->string('plot_number');
            $table->string('plot_name')->nullable();
            $table->decimal('plot_area', 15, 2);
            $table->string('status')->default('vacant'); // vacant, sold, leased
            $table->timestamps();
        });

        Schema::create('buildings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('plot_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('number');
            $table->decimal('footprint_area', 15, 2);
            $table->integer('total_floor');
            $table->boolean('has_lift')->default(false);
            $table->boolean('has_parking')->default(false);
            $table->string('construction_status')->default('planned'); // planned, under_construction, completed
            $table->timestamps();
        });

        Schema::create('floors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('building_id')->constrained()->onDelete('cascade');
            $table->string('floor_number');
            $table->string('floor_name')->nullable();
            $table->integer('total_apartment')->default(0);
            $table->timestamps();
        });

        Schema::create('apartments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('floor_id')->constrained()->onDelete('cascade');
            $table->string('apartment_number');
            $table->string('name');
            $table->decimal('size', 15, 2);
            $table->integer('bedrooms')->nullable();
            $table->integer('bathrooms')->nullable();
            $table->integer('balcony')->nullable();
            $table->string('parking')->nullable();
            $table->boolean('utility_connection')->default(true);
            $table->string('orientation');
            $table->string('status')->default('vacant'); // vacant, reserved, booked, allocated, rented, sold, under_maintenance, cancelled
            $table->timestamps();
        });

        Schema::create('property_sales', function (Blueprint $table) {
            $table->id();
            $table->string('property_type'); // 'plot', 'apartment'
            $table->foreignId('plot_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('apartment_id')->nullable()->constrained()->onDelete('cascade');
            $table->date('sale_date');
            $table->string('buyer_name');
            $table->string('father_name');
            $table->string('mother_name');
            $table->string('nid');
            $table->string('passport')->nullable();
            $table->string('mobile');
            $table->string('email')->nullable();
            $table->text('address');
            $table->decimal('sale_value', 15, 2);
            $table->string('registration_number')->nullable();
            $table->date('registration_date')->nullable();
            $table->string('deed_number')->nullable();
            $table->string('payment_status')->default('pending'); // pending, partially_paid, paid
            $table->date('handover_date')->nullable();
            $table->timestamps();
        });

        Schema::create('rents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('apartment_id')->constrained()->onDelete('cascade');
            $table->string('tenant_name');
            $table->string('nid');
            $table->string('mobile');
            $table->text('address');
            $table->string('occupation');
            $table->date('rent_start_date');
            $table->date('rent_end_date');
            $table->decimal('monthly_rent', 15, 2);
            $table->decimal('advance_amount', 15, 2);
            $table->decimal('security_deposit', 15, 2);
            $table->string('agreement_number')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rents');
        Schema::dropIfExists('property_sales');
        Schema::dropIfExists('apartments');
        Schema::dropIfExists('floors');
        Schema::dropIfExists('buildings');
        Schema::dropIfExists('plots');
        Schema::dropIfExists('lands');
        Schema::dropIfExists('projects');
    }
};

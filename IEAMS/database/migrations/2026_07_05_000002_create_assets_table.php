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
        Schema::create('assets', function (Blueprint $table) {
            $table->id();
            $table->string('unique_asset_id')->nullable()->unique();
            $table->string('asset_type'); // 'fixed', 'current', 'consumer'
            $table->foreignId('category_id')->constrained();
            $table->foreignId('sub_category_id')->constrained();
            $table->string('brand')->nullable();
            $table->string('model')->nullable();
            $table->string('serial_number')->nullable();
            $table->integer('quantity')->nullable();

            // Procurement Info
            $table->date('purchase_date')->nullable();
            $table->decimal('purchase_cost', 15, 2)->nullable();
            $table->decimal('capitalized_cost', 15, 2)->nullable();
            $table->decimal('total_cost', 15, 2)->nullable();
            $table->string('purchase_order_number')->nullable();
            $table->string('invoice_number')->nullable();
            $table->foreignId('vendor_id')->nullable()->constrained();

            // Warranty Info
            $table->boolean('warranty_applicable')->default(false);
            $table->date('warranty_start_date')->nullable();
            $table->date('warranty_end_date')->nullable();

            // Depreciation Info
            $table->string('depreciation_method')->nullable(); // 'straight-line', 'written-down-value'
            $table->integer('useful_life')->nullable(); // years
            $table->decimal('salvage_value_percentage', 5, 2)->nullable();
            $table->decimal('salvage_value_amount', 15, 2)->nullable();
            $table->decimal('current_book_value', 15, 2)->nullable();

            // Maintenance Status
            $table->string('maintenance_status')->default('available'); // 'available', 'assigned', 'under_maintenance', 'out_of_service', 'accident', 'scrap', 'disposed'
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assets');
    }
};

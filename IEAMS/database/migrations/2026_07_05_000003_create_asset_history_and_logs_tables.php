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
        Schema::create('asset_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('asset_id')->constrained()->onDelete('cascade');
            $table->foreignId('custodian_id')->constrained('users')->onDelete('cascade');
            $table->string('assigned_office');
            $table->string('assigned_branch')->nullable();
            $table->date('assigned_date');
            $table->date('expected_return_date')->nullable();
            $table->date('actual_return_date')->nullable();
            $table->string('status')->default('active'); // 'active', 'returned'
            $table->timestamps();
        });

        Schema::create('asset_transfers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('asset_id')->constrained()->onDelete('cascade');
            $table->foreignId('from_custodian_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->foreignId('to_custodian_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->string('from_office')->nullable();
            $table->string('to_office')->nullable();
            $table->date('transfer_date');
            $table->text('remarks')->nullable();
            $table->timestamps();
        });

        Schema::create('asset_maintenances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('asset_id')->constrained()->onDelete('cascade');
            $table->string('maintenance_type'); // 'repair', 'servicing'
            $table->date('maintenance_date');
            $table->decimal('cost', 15, 2);
            $table->string('status')->default('pending'); // 'pending', 'ongoing', 'completed'
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('depreciation_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('asset_id')->constrained()->onDelete('cascade');
            $table->date('depreciation_date');
            $table->decimal('depreciation_amount', 15, 2);
            $table->decimal('book_value_before', 15, 2);
            $table->decimal('book_value_after', 15, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('depreciation_logs');
        Schema::dropIfExists('asset_maintenances');
        Schema::dropIfExists('asset_transfers');
        Schema::dropIfExists('asset_assignments');
    }
};

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
        Schema::create('offices', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->timestamps();
        });

        Schema::create('branches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('office_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('code');
            $table->timestamps();

            $table->unique(['office_id', 'code']);
        });

        // Add office_id and branch_id to asset_assignments
        Schema::table('asset_assignments', function (Blueprint $table) {
            $table->foreignId('office_id')->nullable()->constrained('offices')->onDelete('set null');
            $table->foreignId('branch_id')->nullable()->constrained('branches')->onDelete('set null');
        });

        // Add to_office_id and to_branch_id to asset_transfers
        Schema::table('asset_transfers', function (Blueprint $table) {
            $table->foreignId('to_office_id')->nullable()->constrained('offices')->onDelete('set null');
            $table->foreignId('to_branch_id')->nullable()->constrained('branches')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('asset_transfers', function (Blueprint $table) {
            $table->dropForeign(['to_branch_id']);
            $table->dropForeign(['to_office_id']);
            $table->dropColumn(['to_branch_id', 'to_office_id']);
        });

        Schema::table('asset_assignments', function (Blueprint $table) {
            $table->dropForeign(['branch_id']);
            $table->dropForeign(['office_id']);
            $table->dropColumn(['branch_id', 'office_id']);
        });

        Schema::dropIfExists('branches');
        Schema::dropIfExists('offices');
    }
};

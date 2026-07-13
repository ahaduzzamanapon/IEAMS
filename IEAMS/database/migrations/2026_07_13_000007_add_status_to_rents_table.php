<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('rents', function (Blueprint $table) {
            // BR-58: Rent Agreement cancellation must keep history
            $table->string('status')->default('active')->after('agreement_number'); // active, expired, terminated
            $table->date('terminated_at')->nullable()->after('status');
            $table->text('termination_reason')->nullable()->after('terminated_at');
        });
    }

    public function down(): void
    {
        Schema::table('rents', function (Blueprint $table) {
            $table->dropColumn(['status', 'terminated_at', 'termination_reason']);
        });
    }
};

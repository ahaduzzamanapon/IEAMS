<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('vehicles', function (Blueprint $table) {
            $table->string('tax_token_number')->nullable()->after('fitness_expiry_date');
            $table->date('tax_token_issue_date')->nullable()->after('tax_token_number');
            $table->date('tax_token_expiry_date')->nullable()->after('tax_token_issue_date');
            $table->string('insurance_number')->nullable()->after('tax_token_expiry_date');
            $table->string('insurance_company')->nullable()->after('insurance_number');
            $table->date('insurance_issue_date')->nullable()->after('insurance_company');
            $table->date('insurance_expiry_date')->nullable()->after('insurance_issue_date');
        });
    }

    public function down(): void
    {
        Schema::table('vehicles', function (Blueprint $table) {
            $table->dropColumn([
                'tax_token_number', 'tax_token_issue_date', 'tax_token_expiry_date',
                'insurance_number', 'insurance_company', 'insurance_issue_date', 'insurance_expiry_date'
            ]);
        });
    }
};

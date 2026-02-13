<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->json('report_other_expenses')->nullable()->after('logo_positions');
            $table->json('report_other_income')->nullable()->after('report_other_expenses');
        });
    }

    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn(['report_other_expenses', 'report_other_income']);
        });
    }
};

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
        Schema::create('cracked_eggs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('egg_size_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('quantity_cracked');
            $table->string('reason')->nullable();
            $table->foreignId('recorded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->date('date_recorded');
            $table->timestamps();

            $table->index(['date_recorded', 'egg_size_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cracked_eggs');
    }
};

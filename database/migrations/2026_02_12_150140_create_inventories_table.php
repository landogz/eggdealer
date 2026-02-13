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
        Schema::create('inventories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('egg_size_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('current_stock_pieces')->default(0);
            $table->unsignedInteger('current_stock_trays')->default(0);
            $table->unsignedInteger('minimum_stock_alert')->default(0);
            $table->timestamp('last_updated')->nullable();
            $table->timestamps();

            $table->unique('egg_size_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventories');
    }
};

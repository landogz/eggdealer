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
        Schema::create('egg_prices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('egg_size_id')->constrained()->cascadeOnDelete();
            $table->decimal('price_per_piece', 10, 2)->nullable();
            $table->decimal('price_per_tray', 10, 2)->nullable();
            $table->decimal('price_bulk', 10, 2)->nullable();
            $table->decimal('wholesale_price', 10, 2)->nullable();
            $table->decimal('reseller_price', 10, 2)->nullable();
            $table->date('effective_date');
            $table->string('status', 20)->default('active'); // active, scheduled, expired
            $table->timestamps();
            $table->index(['egg_size_id', 'effective_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('egg_prices');
    }
};

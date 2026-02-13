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
        Schema::create('stock_outs', function (Blueprint $table) {
            $table->id();
            $table->string('customer_name')->nullable();
            $table->string('order_type', 20); // piece, tray, bulk
            $table->foreignId('egg_size_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('quantity');
            $table->decimal('price_used', 10, 2);
            $table->decimal('discount', 10, 2)->default(0);
            $table->decimal('total_amount', 12, 2);
            $table->string('payment_status', 20)->default('unpaid'); // unpaid, paid, partial
            $table->string('payment_method', 30)->nullable();
            $table->dateTime('transaction_date');
            $table->decimal('profit', 12, 2)->nullable();
            $table->timestamps();

            $table->index(['transaction_date', 'egg_size_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_outs');
    }
};

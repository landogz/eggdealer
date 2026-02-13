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
        Schema::create('stock_ins', function (Blueprint $table) {
            $table->id();
            $table->string('supplier_name')->nullable();
            $table->date('delivery_date');
            $table->foreignId('egg_size_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('quantity_pieces');
            $table->decimal('cost_per_piece', 10, 2)->nullable();
            $table->decimal('total_cost', 12, 2)->nullable();
            $table->string('remarks')->nullable();
            $table->string('invoice_path')->nullable();
            $table->timestamps();

            $table->index(['delivery_date', 'egg_size_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_ins');
    }
};

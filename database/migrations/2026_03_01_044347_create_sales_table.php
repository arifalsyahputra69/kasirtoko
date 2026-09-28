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
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            
            // Relasi ke tabel products
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            
            // Detail penjualan
            $table->integer('quantity')->default(1);
            $table->decimal('price', 15, 2); // harga jual per unit
            $table->decimal('cost', 15, 2);  // harga modal per unit

            // Optional: bisa simpan total langsung (juga bisa dihitung via accessor)
            //$table->decimal('total_price', 15, 2)->nullable();
            //$table->decimal('total_cost', 15, 2)->nullable();
            
            $table->timestamps(); // created_at, updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
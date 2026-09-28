<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(){
    Schema::create('bons', function (Blueprint $table) {
        $table->id();
        $table->foreignId('transaction_id')->constrained('transactions')->onDelete('cascade');
        $table->foreignId('user_id')->constrained('users');
        $table->string('nama_pembeli');
        $table->decimal('total_tagihan', 15, 2);
        $table->decimal('total_dibayar', 15, 2)->default(0);
        $table->decimal('sisa_tagihan', 15, 2);
        $table->enum('status', ['lunas', 'cicil'])->default('cicil');
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bons');
    }
};

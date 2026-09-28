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
    Schema::create('bon_payments', function (Blueprint $table) {
        $table->id();
        $table->foreignId('bon_id')->constrained('bons')->onDelete('cascade');
        $table->foreignId('user_id')->constrained('users');
        $table->decimal('jumlah_bayar', 15, 2);
        $table->string('keterangan')->nullable();
        $table->timestamps();
    });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bon_payments');
    }
};

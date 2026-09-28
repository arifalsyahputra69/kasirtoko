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
    Schema::create('returis', function (Blueprint $table) {
        $table->id();
        $table->foreignId('transaction_id')->constrained('transactions')->onDelete('cascade');
        $table->foreignId('user_id')->constrained('users');
        $table->string('alasan')->nullable();
        $table->timestamps();
    });
        }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('returis');
    }
};

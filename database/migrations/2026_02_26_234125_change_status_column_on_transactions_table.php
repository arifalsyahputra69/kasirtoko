<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->string('status')
                  ->default('selesai')
                  ->change();
        });
    }

    public function down()
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->enum('status', ['pending','selesai'])
                  ->default('pending')
                  ->change();
        });
    }
};
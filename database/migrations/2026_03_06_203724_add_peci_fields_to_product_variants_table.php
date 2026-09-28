<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('product_variants', function (Blueprint $table) {

            $table->string('peci_number')->nullable()->after('size');
            $table->string('peci_height')->nullable()->after('peci_number');

        });
    }

    public function down(): void
    {
        Schema::table('product_variants', function (Blueprint $table) {

            $table->dropColumn(['peci_number','peci_height']);

        });
    }
};
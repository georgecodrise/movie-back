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
        Schema::table('carteleras', function (Blueprint $table) {
            $table->dateTime('fin')->after('inicio');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('carteleras', function (Blueprint $table) {
            $table->dropColumn('fin');
        });
    }
};

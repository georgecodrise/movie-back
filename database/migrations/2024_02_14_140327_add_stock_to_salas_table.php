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
        Schema::table('salas', function (Blueprint $table) {
            $table->integer('asientos_total')->after('name');
            $table->integer('asientos_reservados')->after('asientos_total');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('salas', function (Blueprint $table) {
          $table->dropColumn('asientos_total');
          $table->dropColumn('asientos_reservados');
        });
    }
};

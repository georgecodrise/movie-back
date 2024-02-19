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
           $table->integer('asientos')->after('sala_id');
           $table->integer('asientos_reservados')->default(0)->after('asientos');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('carteleras', function (Blueprint $table) {
            $table->dropColumn('asientos');
            $table->dropColumn('asientos_reservados');

        });
    }
};

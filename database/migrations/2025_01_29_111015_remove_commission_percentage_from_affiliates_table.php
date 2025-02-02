<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Esegui le migrazioni.
     */
    public function up(): void
    {
        Schema::table('affiliates', function (Blueprint $table) {
            $table->dropColumn('commission_percentage');
        });
    }

    /**
     * Annulla le migrazioni.
     */
    public function down(): void
    {
        Schema::table('affiliates', function (Blueprint $table) {
            $table->decimal('commission_percentage', 8, 2)->nullable(); // Aggiungi di nuovo la colonna se necessario
        });
    }
};

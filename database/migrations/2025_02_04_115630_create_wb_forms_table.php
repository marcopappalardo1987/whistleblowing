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
        Schema::create('wb_forms', function (Blueprint $table) {
            $table->id();  // chiave primaria
            $table->unsignedBigInteger('id_report'); // Modificato per corrispondere al tipo id()
            $table->mediumInteger('id_user')->unsigned(); // chiave esterna relazionale a id della tabella users
            $table->string('encryption_key', 255);
            $table->timestamps(); // Aggiunge created_at e updated_at

            $table->foreign('id_report')
                  ->references('id')
                  ->on('wb_report')
                  ->onDelete('cascade'); // Definisce la relazione
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wb_forms');
    }
};

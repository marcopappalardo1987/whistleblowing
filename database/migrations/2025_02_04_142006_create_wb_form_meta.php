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
        Schema::create('wb_form_meta', function (Blueprint $table) {
            $table->id(); // chiave primaria
            $table->unsignedBigInteger('id_form'); // Modificato per corrispondere al tipo id()
            $table->string('meta_key', 255); // chiave del meta
            $table->longText('meta_value'); // valore del meta
            $table->timestamps(); // Aggiunge created_at e updated_at

            $table->foreign('id_form')->references('id')->on('wb_forms')->onDelete('cascade'); // Definisce la relazione
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wb_form_meta');
    }
};

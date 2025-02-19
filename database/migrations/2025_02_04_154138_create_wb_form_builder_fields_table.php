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
        Schema::create('wb_form_builder_fields', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('form_id'); // Riferimento al form builder
            $table->string('label'); // Etichetta del campo
            $table->string('name'); // Nome del campo (per il form HTML)
            $table->string('type'); // Tipo di campo (text, textarea, select, ecc.)
            $table->json('options')->nullable(); // Opzioni per select, radio, checkbox
            $table->json('validation_rules')->nullable(); // Regole di validazione
            $table->text('help_text')->nullable(); // Testo di aiuto
            $table->boolean('required')->default(false); // Campo obbligatorio
            $table->integer('order')->default(0); // Ordine di visualizzazione
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            // Relazioni
            $table->foreign('form_id')
                  ->references('id')
                  ->on('wb_form_builder')
                  ->onDelete('cascade');

            // Indici
            $table->index('type');
            $table->index('order');
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wb_form_builder_fields');
    }
}; 
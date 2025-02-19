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
        Schema::create('wb_form_builder', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // ID dell'utente che ha creato il form
            $table->string('title'); // Titolo del form
            $table->string('slug')->unique(); // Slug univoco per identificare il form
            $table->text('description')->nullable(); // Descrizione opzionale del form
            $table->boolean('is_active')->default(true); // Stato del form (attivo/inattivo)
            $table->boolean('is_public')->default(false); // Se il form è pubblico o privato
            $table->string('status')->default('draft'); // Status del form (draft, published, archived)
            $table->timestamps();
            $table->softDeletes(); // Aggiunge deleted_at per il soft delete

            // Relazioni
            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');

            // Indici
            $table->index('status');
            $table->index('is_active');
            $table->index('is_public');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wb_form_builder');
    }
};

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
        Schema::create('affiliates', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->constrained('users'); // id dell'utente relativo alla tabella users
            $table->unsignedBigInteger('parent_id')->nullable(); // id dell'affiliato superiore (null per il primo livello)
            $table->decimal('commission_percentage', 5, 2); // percentuale di commissione
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('affiliates');
    }
};

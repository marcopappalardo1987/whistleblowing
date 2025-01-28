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
        Schema::create('affiliate_earnings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('affiliate_id')->constrained('affiliates'); // id dell'affiliato
            $table->unsignedBigInteger('user_id'); // id dell'utente che ha pagato
            $table->string('stripe_invoice_url'); // url della fattura stripe
            $table->decimal('amount', 10, 2); // importo del pagamento
            $table->decimal('commission', 8, 2); // provvigione
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('affiliate_earnings');
    }
};

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
        Schema::table('affiliate_earnings', function (Blueprint $table) {
            $table->decimal('taxable', 10, 2)->after('stripe_invoice_url'); // Colonna per l'imponibile
            $table->decimal('net', 10, 2)->after('taxable'); // Colonna per il netto
            $table->decimal('commission_percentage', 5, 2)->after('net'); // Colonna per la percentuale di provvigione
            $table->dropColumn('amount'); // Rimuoviamo la colonna amount
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('affiliate_earnings', function (Blueprint $table) {
            $table->dropColumn(['taxable', 'net', 'commission_percentage']);
            $table->decimal('amount', 10, 2)->after('commission_percentage'); // Ripristiniamo la colonna amount
        });
    }
};

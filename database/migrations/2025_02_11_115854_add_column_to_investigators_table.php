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
        Schema::table('investigators', function (Blueprint $table) {
            // Aggiungi la colonna name dopo company_id
            $table->string('name')->after('company_id');
            
            // Aggiungi la colonna branch_id dopo email
            $table->foreignId('branch_id')
                  ->nullable()
                  ->after('email')
                  ->constrained('branches')
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('investigators', function (Blueprint $table) {
            $table->dropForeign(['branch_id']);
            $table->dropColumn(['name', 'branch_id']);
        });
    }
}; 
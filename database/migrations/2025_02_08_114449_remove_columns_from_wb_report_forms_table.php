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
        Schema::table('wb_report_forms', function (Blueprint $table) {
            $table->dropColumn(['id_user', 'encryption_key']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('wb_report_forms', function (Blueprint $table) {
            $table->unsignedBigInteger('id_user')->nullable();
            $table->string('encryption_key')->nullable();
        });
    }
};

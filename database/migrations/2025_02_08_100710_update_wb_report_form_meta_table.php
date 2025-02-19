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
        Schema::table('wb_report_form_meta', function (Blueprint $table) {
            $table->string('meta_key', 500)->change();
            $table->text('meta_value', 16383)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('wb_report_form_meta', function (Blueprint $table) {
            $table->string('meta_key')->change();
            $table->string('meta_value')->change();
        });
    }
};

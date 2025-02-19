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
        Schema::rename('wb_forms', 'wb_report_forms');
        Schema::rename('wb_form_meta', 'wb_report_form_meta');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::rename('wb_report_forms', 'wb_forms');
        Schema::rename('wb_report_form_meta', 'wb_form_meta');
    }
};

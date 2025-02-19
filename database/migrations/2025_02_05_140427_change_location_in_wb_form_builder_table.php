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
        Schema::table('wb_form_builder', function (Blueprint $table) {
            $table->dropColumn('location'); // Dropping the existing location column
            $table->string('location')->nullable()->after('description'); // Adding location column after description
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('wb_form_builder', function (Blueprint $table) {
            $table->dropColumn('location'); // Dropping the location column
        });
    }
};

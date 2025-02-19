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
            $table->string('location')->nullable(); // Adding the location column
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

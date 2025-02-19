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
            $table->boolean('is_file')->default(false)->after('meta_value'); // Adding the is_file column of type boolean after meta_value
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('wb_report_form_meta', function (Blueprint $table) {
            $table->dropColumn('is_file'); // Dropping the is_file column
        });
    }
};

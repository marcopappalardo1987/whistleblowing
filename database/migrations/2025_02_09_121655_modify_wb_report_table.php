<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB; // Added import for DB

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Remove existing values in the 'branch' column before modifying
        DB::table('wb_report')->update(['branch' => null]);

        Schema::table('wb_report', function (Blueprint $table) {
            $table->unsignedBigInteger('branch_id')->nullable()->after('branch');
            $table->foreign('branch_id')->references('id')->on('branches')->onDelete('set null');
            $table->dropColumn('branch'); // Drop the 'branch' column
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('wb_report', function (Blueprint $table) {
            $table->dropForeign(['branch_id']);
            $table->dropColumn('branch_id');
            $table->string('branch')->nullable(); // Recreate the 'branch' column
        });
    }
};

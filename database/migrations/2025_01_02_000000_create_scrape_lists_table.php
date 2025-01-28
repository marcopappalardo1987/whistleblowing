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
        Schema::create('scrape_lists', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->string('list_name'); // LIST NAME
            $table->foreignId('user_id'); // ID USER
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scrape_lists');
    }
};

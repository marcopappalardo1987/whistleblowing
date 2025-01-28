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
        Schema::create('dfs_api_task', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->string('task_id'); // ID TASK
            $table->string('task_type'); // TASK TYPE
            $table->integer('status_code'); // STATUS CODE
            $table->string('status_message'); // STATUS MESSAGE
            $table->foreignId('user_id'); // ID USER
            $table->timestamps(); // created at and updated at
            $table->foreignId('scrape_list_id')->nullable(); // ID SCRAPE LIST
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dfs_api_task');
    }
};

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
        Schema::create('scrape_list_content', function (Blueprint $table) {
            $table->id();
            $table->string('company');
            $table->string('domain');
            $table->string('borough');
            $table->string('address');
            $table->string('city');
            $table->string('post_code');
            $table->string('region');
            $table->string('country_code');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->text('categories')->nullable();
            $table->unsignedBigInteger('scrape_list_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scrape_list_content');
    }
};

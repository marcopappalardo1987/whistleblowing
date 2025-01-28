<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MakeFieldsNullableInScrapeListContent extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('scrape_list_content', function (Blueprint $table) {
            $table->string('company')->nullable()->change();
            $table->string('domain')->nullable()->change();
            $table->string('borough')->nullable()->change();
            $table->string('address')->nullable()->change();
            $table->string('city')->nullable()->change();
            $table->string('post_code')->nullable()->change();
            $table->string('region')->nullable()->change();
            $table->string('country_code')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('scrape_list_content', function (Blueprint $table) {
            $table->string('company')->nullable(false)->change();
            $table->string('domain')->nullable(false)->change();
            $table->string('borough')->nullable(false)->change();
            $table->string('address')->nullable(false)->change();
            $table->string('city')->nullable(false)->change();
            $table->string('post_code')->nullable(false)->change();
            $table->string('region')->nullable(false)->change();
            $table->string('country_code')->nullable(false)->change();
        });
    }
} 
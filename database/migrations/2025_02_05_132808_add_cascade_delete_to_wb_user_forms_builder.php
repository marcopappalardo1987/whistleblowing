<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('wb_user_forms_builder', function (Blueprint $table) {
            $table->dropForeign(['wb_form_builder_id']); // Rimuove la vecchia chiave esterna
            $table->foreign('wb_form_builder_id')
                ->references('id')
                ->on('wb_form_builder')
                ->onDelete('cascade'); // Aggiunge il vincolo di eliminazione a cascata
        });
    }

    public function down()
    {
        Schema::table('wb_user_forms_builder', function (Blueprint $table) {
            $table->dropForeign(['wb_form_builder_id']);
            $table->foreign('wb_form_builder_id')
                ->references('id')
                ->on('wb_form_builder');
        });
    }

};

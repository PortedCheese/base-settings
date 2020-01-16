<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRoleRulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('role_rules', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string("title")
                ->comment("Наименование правил");

            $table->string("slug")
                ->unique()
                ->comment("Slug правила");

            $table->string("policy")
                ->nullable()
                ->comment("Класс политики");

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('role_rules');
    }
}

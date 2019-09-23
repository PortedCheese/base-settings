<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSiteConfigsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('site_configs', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->char("name", 50)
                ->unique()
                ->comment("К чему относится конфиг");

            $table->string("title")
                ->nullable()
                ->comment("Название конфига");

            $table->json("data")
                ->nullable()
                ->comment("Данные в конфиге");

            $table->string("template")
                ->comment("Шаблон формы");

            $table->boolean("package")
                ->default(0)
                ->comment("Конфигурация из пакета");

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
        Schema::dropIfExists('site_configs');
    }
}

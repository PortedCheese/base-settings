<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLoginLinksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('login_links', function (Blueprint $table) {
            $table->bigIncrements("id");

            $table->string("email")
                ->comment("E-mail пользователя");
            $table->string("send")
                ->nullable()
                ->comment("Куда отправить ссылку");
            $table->string("token")
                ->comment("Token для входа");

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
        Schema::dropIfExists('login_links');
    }
}

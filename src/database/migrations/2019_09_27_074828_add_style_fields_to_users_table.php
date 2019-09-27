<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStyleFieldsToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (env("NEED_REFACTOR_USERS_FIELDS")) {
            Schema::table("users", function (Blueprint $table) {
                $table->dropColumn("login");
                $table->dropColumn("sex");
                $table->renameColumn("firstname", "name");
                $table->renameColumn("surname", "last_name");
                $table->renameColumn("fathername", "middle_name");
                $table->renameColumn("avatar_id", "image_id");
            });
        }
        else {
            Schema::table("users", function (Blueprint $table) {
                $table->string("last_name")
                    ->nullable()
                    ->after('email')
                    ->comment("Фамилия");

                $table->string("middle_name")
                    ->nullable()
                    ->after('email')
                    ->comment("Отчество");

                $table->unsignedInteger("image_id")
                    ->nullable()
                    ->after('email')
                    ->comment("Аватар пользователя");
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (env("NEED_REFACTOR_USERS_FIELDS")) {
            Schema::table("users", function (Blueprint $table) {
                $table->string('login', 100)->unique();
                $table->tinyInteger('sex')->default(0);
                $table->renameColumn("name", "firstname");
                $table->renameColumn("last_name", "surname");
                $table->renameColumn("middle_name", "fathername");
                $table->renameColumn("image_id", "avatar_id");
            });
        }
        else {
            Schema::table("users", function (Blueprint $table) {
                $table->dropColumn(["last_name", "middle_name", "image_id"]);
            });
        }
    }
}

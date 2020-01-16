<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRoleRoleRuleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('role_role_rule', function (Blueprint $table) {
            $table->unsignedBigInteger("role_id");
            $table->unsignedBigInteger("role_rule_id");
            $table->unsignedBigInteger("rights");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('role_role_rule');
    }
}

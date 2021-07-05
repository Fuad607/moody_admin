<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserRelationshipTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_relationship', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');
            $table->integer('contacted_user_id')->unsigned();
            $table->foreign('contacted_user_id')->references('id')->on('users');
            $table->integer('relationship_type_id')->unsigned();
            $table->foreign('relationship_type_id')->references('id')->on('relationship_type');
            $table->integer('deleted')->default('0');
            $table->integer('sync')->default('0');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_relationship');
    }
}

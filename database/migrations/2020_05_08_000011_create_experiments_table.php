<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExperimentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('experiments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('admin_id')->unsigned();
            $table->foreign('admin_id')->references('id')->on('admin');
            $table->string('name')->nullable();
            $table->text('user_ids')->nullable();
            $table->integer('frequency')->default('0');
            $table->integer('range')->default('0');
            $table->integer('notifications')->default('0');
            $table->integer('start_timestamp')->default('0');
            $table->integer('end_timestamp')->default('0');
            $table->integer('status')->default('0');
            $table->integer('deleted')->default('0');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('experiments');
    }
}

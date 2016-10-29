<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMemberbooksTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('memberbooks', function(Blueprint $table)
        {
            $table->increments('id');
            $table->string('uid',36)->default('');
            $table->integer('user_id')->unsigned()->nullable();
            $table->integer('book_id')->unsigned()->nullable();
            $table->timestamp('started_at')->default('0000-00-00 00:00:00');
            $table->timestamp('ended_at')->default('0000-00-00 00:00:00');
            $table->string('status', 20)->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('memberbooks');
    }

}
<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class UpdateUsersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::table('users', function($table){
            $table->string('uid',36)->after('id')->default('');
            $table->integer('api_id')->after('uid')->unsigned()->nullable();

            $table->string('first_name', 15)->after('api_id')->default('');
            $table->string('last_name', 15)->after('first_name')->default('');
            $table->string('gender', 15)->after('last_name')->default('');
            $table->date('dob')->after('gender')->default('0000-00-00');
            $table->tinyInteger('no_of_books_borrowed')->after('dob')->default(0);

            $table->softDeletes();

            $table->unique('uid');

            /*
            $table->foreign('api_id')
                ->references('id')->on('apis')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            */
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        Schema::table('users', function($table){

            $table->dropSoftDeletes();

            $table->dropColumn('uid');
            $table->dropColumn('api_id');
            $table->dropColumn('first_name');
            $table->dropColumn('last_name');
            $table->dropColumn('gender');
            $table->dropColumn('photo');
        });
	}

}

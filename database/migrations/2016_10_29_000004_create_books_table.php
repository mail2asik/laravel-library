<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBooksTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('books', function(Blueprint $table)
        {
            $table->increments('id');
            $table->string('uid',36)->default('');
            $table->string('title')->default('');
            $table->string('author', 30)->default('');
            $table->string('isbn', 20)->default('');
            $table->tinyInteger('quantity')->default(0);
            $table->string('shelf_location', 20)->default('');
            $table->tinyInteger('no_of_books_available')->default(0);
            $table->tinyInteger('no_of_books_loan')->default(0);

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
        Schema::drop('books');
    }

}
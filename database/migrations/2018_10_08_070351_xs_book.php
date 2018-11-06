<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class XsBook extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('book', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title')->index();
            $table->string('description');
            $table->string('author_id');
            $table->string('author');
            $table->string('c1');
            $table->string('c2');
            $table->string('c3');
            $table->string('last_chapter_id');
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
        //
        Schema::dropIfExists('book');
    }
}

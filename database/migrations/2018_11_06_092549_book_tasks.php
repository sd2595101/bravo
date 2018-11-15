<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class BookTasks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $connection = config('admin.database.connection') ?: config('database.default');

        Schema::connection($connection)->create('book_task_roots', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 100);
            $table->string('url', 300);
            $table->integer('site_id');
            $table->integer('adeleted')->default(0);
            $table->string('job_class_name');
            $table->integer('job_refresh_page');
            $table->string('rule_url')->nullable();
            $table->string('rule_page_next')->nullable();
            $table->string('rule_page_prev')->nullable();
            $table->string('rule_page_last')->nullable();
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
        $connection = config('admin.database.connection') ?: config('database.default');
        Schema::connection($connection)->dropIfExists('book_task_roots');
    }
}

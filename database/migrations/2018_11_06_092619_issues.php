<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Issues extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        $connection = config('admin.database.connection') ?: config('database.default');
        
        Schema::connection($connection)->create('issues', function (Blueprint $table) {
            $table->increments('id');
            $table->string('subject', 100);
            $table->string('detail')->nullable();
            $table->enum('status', ['Open','Closed'])->default('Open');
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
        Schema::connection($connection)->dropIfExists('issues');
    }
}

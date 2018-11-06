<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSitesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
    
        $connection = config('admin.database.connection') ?: config('database.default');

        Schema::connection($connection)->create('sites', function (Blueprint $table) {
            $table->increments('id');
            $table->string('url', 300)->unique();
            $table->string('name')->nullable();
            $table->integer('official')->default(0)->nullable();
            $table->string('describe')->nullable();
            $table->integer('enable')->default(0);
            $table->integer('searcheable')->default(0);
            $table->string('search_url')->nullable();
            $table->string('search_method', 20)->nullable()->default('GET');
            $table->string('search_key', 30)->nullable();
            $table->string('search_example', 200)->nullable();
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
        $connection = config('admin.database.connection') ?: config('database.default');
        Schema::connection($connection)->dropIfExists('sites');
    }
}

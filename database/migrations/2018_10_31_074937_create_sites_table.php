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
            $table->string('describe')->nullable();
            $table->string('enable', 1);
            $table->datetime('release_at')->nullable();
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

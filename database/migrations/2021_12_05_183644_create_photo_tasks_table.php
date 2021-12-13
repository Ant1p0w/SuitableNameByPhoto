<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePhotoTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('photo_tasks', function (Blueprint $table) {
            $table->id();
            $table->string('status');
            $table->float('result')->nullable();
            $table->string('retry_id')->nullable();
            $table->bigInteger('photo_id');
            $table->timestamps();
            $table->index(['photo_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('photo_tasks');
    }
}

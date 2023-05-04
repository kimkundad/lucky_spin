<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wheelsetting', function (Blueprint $table) {
            $table->id();
            $table->integer('value')->default('0');
            $table->string('text')->nullable();
            $table->string('message')->nullable();
            $table->integer('percent')->default('0');
            $table->integer('minDegree')->default('0');
            $table->integer('maxDegree')->default('0');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('wheelsetting');
    }
};

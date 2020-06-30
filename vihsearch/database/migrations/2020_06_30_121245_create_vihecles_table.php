<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateViheclesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vihecles', function (Blueprint $table) {
            $table->integer("id");
            $table->unsignedBigInteger("series_id");
            $table->unsignedBigInteger("subcateg_id");
            $table->unsignedBigInteger("country_id");
            $table->unsignedBigInteger("sales_id")->nullable();
            $table->string("size");
            $table->string("config")->nullable();
            $table->integer("year");
            $table->integer("cylinder");
            $table->string("eng_output")->nullable();
            $table->string("drivetype")->nullable();
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
        Schema::dropIfExists('vihecles');
    }
}

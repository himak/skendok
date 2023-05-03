<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOdosielatelsTable extends Migration
{
    public function up()
    {
        Schema::create('odosielatels', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('odosielatel')->nullable();
            $table->timestamps();
        });
    }
}

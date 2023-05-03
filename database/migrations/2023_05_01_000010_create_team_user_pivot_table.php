<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTeamUserPivotTable extends Migration
{
    public function up()
    {
        Schema::create('team_user', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id', 'user_id_fk_7987236')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('team_id');
            $table->foreign('team_id', 'team_id_fk_7987236')->references('id')->on('teams')->onDelete('cascade');
        });
    }
}

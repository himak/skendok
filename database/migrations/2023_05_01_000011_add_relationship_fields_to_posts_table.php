<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToPostsTable extends Migration
{
    public function up()
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->unsignedBigInteger('team_id')->nullable();
            $table->foreign('team_id', 'team_fk_7987242')->references('id')->on('teams');
            $table->unsignedBigInteger('odosielatel_id')->nullable();
            $table->foreign('odosielatel_id', 'odosielatel_fk_8415924')->references('id')->on('odosielatels');
        });
    }
}

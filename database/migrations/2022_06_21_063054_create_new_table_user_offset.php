<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNewTableUserOffset extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_offset', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('trx_ticket_h_id');
            $table->foreign('trx_ticket_h_id')->references('id')->on('trx_ticket_h');
            $table->string('user_name',60); 
            $table->string('topic'); 
            $table->string('group'); 
            $table->integer('offset'); 
            $table->smallInteger('status');
            $table->string('created_by', 40);
            $table->timestamp('created_date');
            $table->string('updated_by',40)->nullable();
            $table->timestamp('updated_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_offset', function (Blueprint $table) {
            Schema::dropIfExists('user_offset');
        });
    }
}

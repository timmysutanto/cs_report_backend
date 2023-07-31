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
        Schema::create('trx_ticket_d', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('trx_ticket_h_id');
            $table->foreign('trx_ticket_h_id')->references('id')->on('trx_ticket_h'); 
            $table->string('pic_ur',40)->nullable(); 
            $table->integer('to_company_id'); 
            $table->integer('to_department_id'); 
            $table->integer('to_divisi_id'); 
            $table->integer('to_branch_id'); 
            $table->string('action',40)->nullable(); 
            $table->text('description')->nullable(); 
            $table->smallInteger('status_ticket');
            $table->smallInteger('status');
            $table->string('created_by', 40);
            $table->timestamp('created_date');
            $table->string('updated_by',40)->nullable();
            $table->timestamp('updated_date')->nullable();
            $table->text('remark_pic_ur')->nullable(); 
            $table->text('remark_user')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('trx_ticket_d');
    }
};

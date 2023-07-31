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
        Schema::create('trx_ticket_h', function (Blueprint $table) {
            $table->increments('id');
            $table->string('ticket_number',50);
            $table->integer('from_company_id'); 
            $table->integer('from_department_id'); 
            $table->integer('from_divisi_id'); 
            $table->integer('from_branch_id'); 
            $table->integer('to_company_id'); 
            $table->integer('to_department_id'); 
            $table->integer('to_divisi_id'); 
            $table->integer('to_branch_id'); 
            $table->integer('category_ticket_case_id'); 
            $table->foreign('category_ticket_case_id')->references('id')->on('category_ticket_case'); 
            $table->text('description')->nullable();
            $table->smallInteger('status_ticket'); 
            $table->string('last_approval',40)->nullable();
            $table->string('pic_ur',40)->nullable(); 
            $table->string('priority',20)->nullable(); 
            $table->smallInteger('status');
            $table->string('created_by', 40);
            $table->timestamp('created_date');
            $table->string('updated_by',40)->nullable();
            $table->timestamp('updated_date')->nullable();
            $table->string('deleted_by',40)->nullable(); 
            $table->timestamp('deleted_date')->nullable(); 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('trx_ticket_h');
    }
};

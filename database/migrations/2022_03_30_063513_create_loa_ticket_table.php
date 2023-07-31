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
        Schema::create('loa_ticket', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('trx_ticket_h_id');
            $table->foreign('trx_ticket_h_id')->references('id')->on('trx_ticket_h'); 
            $table->string('loa_type',1); 
            $table->string('approval_id',40)->nullable(); 
            $table->smallInteger('status_approval')->nullable();
            $table->integer('company_id'); 
            $table->integer('branch_id'); 
            $table->integer('department_id'); 
            $table->integer('divisi_id'); 
            $table->integer('jabatan_id'); 
            $table->smallInteger('level'); 
            $table->smallInteger('status');
            $table->string('created_by', 40)->nullable();
            $table->timestamp('created_date')->nullable();
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
        Schema::dropIfExists('loa_ticket');
    }
};

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
        Schema::create('mst_loa', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('category_ticket_id');
            $table->foreign('category_ticket_id')->references('id')->on('mst_category_ticket'); 
            $table->integer('company_id'); 
            $table->integer('branch_id');
            $table->integer('department_id');
            $table->integer('division_id'); 
            $table->integer('jabatan_id'); 
            $table->smallInteger('level');
            $table->string('user_name',60); 
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
        Schema::dropIfExists('mst_loa');
    }
};

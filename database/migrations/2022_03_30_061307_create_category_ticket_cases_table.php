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
        Schema::create('category_ticket_case', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('category_ticket_id');
            $table->foreign('category_ticket_id')->references('id')->on('mst_category_ticket'); 
            $table->integer('case_id');
            $table->foreign('case_id')->references('id')->on('mst_case'); 
            $table->string('description',100)->nullable();
            $table->string('company_id');
            $table->integer('department_id'); 
            $table->integer('division_id'); 
            $table->smallInteger('status');
            $table->string('created_by', 40);
            $table->timestamp('created_date');
            $table->string('updated_by',40)->nullable();
            $table->timestamp('updated_date')->nullable();
            $table->unique(['category_ticket_id', 'case_id']);
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('category_ticket_case');
    }
};

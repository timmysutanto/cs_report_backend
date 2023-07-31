<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SlaTicket extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sla_ticket', function (Blueprint $table) {
            $table->id();
            $table->integer('branch_id'); 
            $table->integer('category_ticket_case_id'); 
            $table->string('priority',20); 
            $table->integer('working_hour'); 
            $table->integer('maximum_working_hour'); 
            $table->text('description')->nullable();
            $table->integer('status'); 
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
        Schema::dropIfExists('sla_ticket');
    }
}

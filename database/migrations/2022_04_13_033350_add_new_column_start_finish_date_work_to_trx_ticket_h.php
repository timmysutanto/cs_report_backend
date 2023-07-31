<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewColumnStartFinishDateWorkToTrxTicketH extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('trx_ticket_h', function (Blueprint $table) {
            $table->dateTime('start_date_work')->nullable(); 
            $table->dateTime('finish_date_work')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('trx_ticket_h', function (Blueprint $table) {
            //
        });
    }
}

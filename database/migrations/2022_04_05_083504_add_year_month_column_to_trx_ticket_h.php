<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddYearMonthColumnToTrxTicketH extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('trx_ticket_h', function (Blueprint $table) {
            $table->char('month',2)->after('priority');
            $table->char('year',4)->after('month'); 
            $table->unique('ticket_number');
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

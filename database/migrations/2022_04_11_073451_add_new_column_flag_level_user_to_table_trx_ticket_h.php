<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewColumnFlagLevelUserToTableTrxTicketH extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('trx_ticket_h', function (Blueprint $table) {
            $table->char('flag_from',10)->nullable(); 
            $table->char('flag_to', 10)->nullable(); 
            $table->smallInteger('user_level')->nullable(); 
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

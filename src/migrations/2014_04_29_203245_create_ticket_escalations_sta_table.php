<?php

use Illuminate\Database\Migrations\Migration;

class CreateTicketEscalationsStaTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ticket_escalations_sta', function($table) {
            $table->increments('id');
			$table->integer('ticket_escalations_id')->unsigned()->nullable();
			$table->integer('ticket_statuses_id')->unsigned()->nullable();
		
 			$table->foreign('ticket_escalations_id')->references('id')->on('ticket_escalations')->onDelete('cascade');
 			$table->foreign('ticket_statuses_id')->references('id')->on('ticket_statuses')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('ticket_escalations_sta');
    }

}
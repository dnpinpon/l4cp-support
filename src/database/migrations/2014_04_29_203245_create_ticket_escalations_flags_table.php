<?php

use Illuminate\Database\Migrations\Migration;

class CreateTicketEscalationsFlagsTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ticket_escalations_flags', function($table) {
            $table->increments('id');
			$table->integer('ticket_escalations_id')->unsigned()->nullable();
			$table->integer('user_id')->unsigned()->nullable();
		
 			$table->foreign('ticket_escalations_id')->references('id')->on('ticket_escalations')->onDelete('cascade');
 			$table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('ticket_escalations_flags');
    }

}
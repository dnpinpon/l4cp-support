<?php

use Illuminate\Database\Migrations\Migration;

class CreateTicketAutoreplyActionsTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ticket_autoreply_actions', function($table) {
            $table->increments('id');
			$table->integer('ticket_autoreply_id')->unsigned()->nullable();
			$table->integer('ticket_actions_id')->unsigned()->nullable();
		
 			$table->foreign('ticket_autoreply_id')->references('id')->on('ticket_autoreply')->onDelete('cascade');
 			$table->foreign('ticket_actions_id')->references('id')->on('ticket_actions')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('ticket_autoreply_actions');
    }

}
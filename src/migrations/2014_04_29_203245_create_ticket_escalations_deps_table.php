<?php

use Illuminate\Database\Migrations\Migration;

class CreateTicketEscalationsDepsTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ticket_escalations_deps', function($table) {
            $table->increments('id');
			$table->integer('ticket_escalations_id')->unsigned()->nullable();
			$table->integer('ticket_deps_id')->unsigned()->nullable();
		
 			$table->foreign('ticket_escalations_id')->references('id')->on('ticket_escalations')->onDelete('cascade');
 			$table->foreign('ticket_deps_id')->references('id')->on('ticket_deps')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('ticket_escalations_deps');
    }

}
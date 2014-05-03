<?php

use Illuminate\Database\Migrations\Migration;

class CreateTicketAutoreplyDepsTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ticket_autoreply_deps', function($table) {
            $table->increments('id');
			$table->integer('ticket_autoreply_id')->unsigned()->nullable();
			$table->integer('ticket_deps_id')->unsigned()->nullable();
		
 			$table->foreign('ticket_autoreply_id')->references('id')->on('ticket_autoreply')->onDelete('cascade');
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
        Schema::drop('ticket_autoreply_deps');
    }

}
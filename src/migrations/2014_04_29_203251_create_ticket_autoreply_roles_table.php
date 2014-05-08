<?php

use Illuminate\Database\Migrations\Migration;

class CreateTicketAutoreplyRolesTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ticket_autoreply_roles', function($table) {
            $table->increments('id');
			$table->integer('ticket_autoreply_id')->unsigned()->nullable();
			$table->integer('role_id')->unsigned()->nullable();
		
 			$table->foreign('ticket_autoreply_id')->references('id')->on('ticket_autoreply')->onDelete('cascade');
 			$table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('ticket_autoreply_roles');
    }

}
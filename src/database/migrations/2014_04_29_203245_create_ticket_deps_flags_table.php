<?php

use Illuminate\Database\Migrations\Migration;

class CreateTicketDepsFlagsTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ticket_deps_flags', function($table) {
            $table->increments('id');
			$table->integer('ticket_deps_id')->unsigned()->nullable();
			$table->integer('user_id')->unsigned()->nullable();
		
 			$table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
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
        Schema::drop('ticket_deps_flags');
    }

}
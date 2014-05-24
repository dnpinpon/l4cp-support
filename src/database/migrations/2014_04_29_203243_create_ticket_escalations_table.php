<?php

use Illuminate\Database\Migrations\Migration;

class CreateTicketEscalationsTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ticket_escalations', function($table) {
            $table->increments('id');

            $table->text('name')->nullable();
            $table->text('content')->nullable();
            $table->text('delay')->nullable();
            $table->longText('reply')->nullable();
	           
			$table->integer('notify_admins')->unsigned()->nullable();
			$table->integer('flag')->unsigned()->nullable();
		
			$table->integer('new_status')->unsigned()->nullable();
			$table->integer('new_priority')->unsigned()->nullable();
			$table->integer('new_department')->unsigned()->nullable();
		
		// needs a lookup for department and status assigned
			
			$table->timestamp('created_at')->default("0000-00-00 00:00:00");
            $table->timestamp('updated_at')->default("0000-00-00 00:00:00");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('ticket_escalations');
    }

}
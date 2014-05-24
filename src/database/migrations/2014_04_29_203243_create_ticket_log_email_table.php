<?php

use Illuminate\Database\Migrations\Migration;

class CreateTicketLogEmailTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ticket_log_email', function($table) {
            $table->increments('id');
            $table->text('to')->nullable();
            $table->text('name')->nullable();
            $table->text('email')->nullable();
            $table->text('subject')->nullable();
            $table->text('message')->nullable();
            $table->text('status')->nullable();
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
        Schema::drop('ticket_log_email');
    }

}
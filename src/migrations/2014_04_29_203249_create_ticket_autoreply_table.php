<?php

use Illuminate\Database\Migrations\Migration;

class CreateTicketAutoreplyTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ticket_autoreply', function($table) {
            $table->increments('id');
            $table->text('title');
            $table->longText('content');
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
        Schema::drop('ticket_autoreply');
    }

}
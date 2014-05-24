<?php

use Illuminate\Database\Migrations\Migration;

class CreateTicketSpamfilterTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ticket_spamfilter', function($table) {
            $table->increments('id');
			$table->enum('type', array('subject', 'sender', 'body'));
            $table->longText('content')->nullable();
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
        Schema::drop('ticket_spamfilter');
    }

}
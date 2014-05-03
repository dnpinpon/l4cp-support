<?php

use Illuminate\Database\Migrations\Migration;

class CreateTicketBreaklinesTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ticket_breaklines', function($table) {
            $table->increments('id');
            $table->longText('breakline');
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
        Schema::drop('ticket_breaklines');
    }

}
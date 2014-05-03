<?php

use Illuminate\Database\Migrations\Migration;

class CreateTicketLogTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ticket_log', function($table) {
            $table->increments('id');
            $table->integer('ticket_id')->unsigned()->index();
            $table->string('action', 255)->nullable();
 			$table->foreign('ticket_id')->references('id')->on('ticket')->onDelete('cascade');
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
        Schema::drop('ticket_log');
    }

}
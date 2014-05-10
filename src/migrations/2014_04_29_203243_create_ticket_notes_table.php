<?php

use Illuminate\Database\Migrations\Migration;

class CreateTicketNotesTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ticket_notes', function($table) {
            $table->increments('id');
            $table->integer('ticket_id')->unsigned()->index();
            $table->integer('admin_id')->unsigned()->index();
            $table->longText('content')->nullable();
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
        Schema::drop('ticket_notes');
    }

}
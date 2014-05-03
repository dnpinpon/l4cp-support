<?php

use Illuminate\Database\Migrations\Migration;

class CreateTicketRepliesTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ticket_replies', function($table) {
            $table->increments('id');
            $table->integer('ticket_id')->unsigned()->index();
            $table->integer('user_id')->unsigned()->index();
            $table->integer('admin_id')->unsigned()->index();

			$table->text('name')->nullable();
			$table->text('email')->nullable();
			$table->text('attachment')->nullable();
			$table->longText('content')->nullable();

            $table->integer('rating')->unsigned()->nullable();
			
			$table->timestamp('created_at')->default("0000-00-00 00:00:00");
            $table->timestamp('updated_at')->default("0000-00-00 00:00:00");

			$table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
 			$table->foreign('ticket_id')->references('id')->on('ticket')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('ticket_replies');
    }

}
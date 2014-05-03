<?php

use Illuminate\Database\Migrations\Migration;

class CreateTicketStatusesTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ticket_statuses', function($table) {
            $table->increments('id');
            $table->text('title');
            $table->text('color')->nullable();
  
			$table->integer('sort')->unsigned()->nullable();
			$table->integer('show_active')->unsigned()->nullable();
			$table->integer('default_button')->unsigned()->nullable();
			$table->integer('default_category')->unsigned()->nullable();
			$table->integer('default_flag')->unsigned()->nullable();
			$table->integer('auto_close')->unsigned()->nullable();

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
        Schema::drop('ticket_statuses');
    }

}
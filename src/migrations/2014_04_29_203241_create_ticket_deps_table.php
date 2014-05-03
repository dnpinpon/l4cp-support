<?php

use Illuminate\Database\Migrations\Migration;

class CreateTicketDepsTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ticket_deps', function($table) {
            $table->increments('id');
            $table->text('name');
            $table->text('description')->nullable();
            $table->text('email')->nullable();
  
            $table->text('pop_host')->nullable();
            $table->text('pop_port')->nullable();
            $table->text('pop_user')->nullable();
            $table->text('pop_pass')->nullable();
		   
            $table->integer('clients_only')->unsigned()->nullable();
            $table->integer('auto_respond')->unsigned()->nullable();
            $table->integer('hidden')->unsigned()->nullable();
            $table->integer('sort')->unsigned()->nullable();

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
        Schema::drop('ticket_deps');
    }

}
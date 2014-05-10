<?php

use Illuminate\Database\Migrations\Migration;

class CreateTicketTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ticket', function($table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned()->index()->nullable();
            $table->integer('admin_id')->unsigned()->index()->nullable();
            $table->integer('department_id')->unsigned()->index()->nullable();
  
	        $table->text('name')->nullable();
            $table->text('email')->nullable();
		    $table->text('cc')->nullable();
		    $table->text('c')->nullable();
		    $table->text('title')->nullable();
		    $table->text('attachment')->nullable();
		    $table->longText('message')->nullable();

	        $table->integer('priority')->unsigned()->nullable();
	        $table->integer('status')->unsigned()->nullable();

            $table->timestamp('created_at')->default("0000-00-00 00:00:00");
            $table->timestamp('updated_at')->default("0000-00-00 00:00:00");

			$table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('ticket');
    }

}
<?php

class TicketStatuses  extends Eloquent {

	protected $table = 'ticket_statuses';
	public static $unguarded = true;
	public $timestamps = false;
}
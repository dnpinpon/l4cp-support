<?php

class TicketActions  extends Eloquent {

	protected $table = 'ticket_actions';
	public static $unguarded = true;

	public function flags()
    {
        return $this->belongsToMany('User', 'ticket_deps_flags');
    }

}
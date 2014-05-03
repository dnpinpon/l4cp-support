<?php

class Ticket  extends Eloquent {

	protected $table = 'ticket';
	public static $unguarded = true;
	
	public function replies()
    {
        return $this->hasMany('TicketReplies');
    }

	public function notes()
    {
        return $this->hasMany('TicketNotes');
    }
	public function flags()
    {
        return $this->belongsToMany('User', 'ticket_flags');
    }
    /**
     * Save users inputted from multiselect
     * @param $inputRoles
     */
    public function saveFlags($input)
    {
 		empty($input) ? $this->flags()->detach() :  $this->flags()->sync($input);
    }
	 /**
     * Returns escalations's current users .
     * @return array|bool
     */
    public function currentFlags()
    {
        $roles = $this->flags;
        $roleIds = false;
        if( !empty( $roles ) ) {
            $roleIds = array();
            foreach( $roles as &$role )
            {
                $roleIds[] = $role->id;
            }
        }
        return $roleIds;
    }

}
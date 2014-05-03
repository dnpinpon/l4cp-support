<?php

class TicketDeps  extends Eloquent {

	protected $table = 'ticket_deps';
	public static $unguarded = true;

	public function flags()
    {
        return $this->belongsToMany('User', 'ticket_deps_flags');
    }

    /**
     * Save deps inputted from multiselect
     * @param $inputRoles
     */
    public function saveFlags($input)
    {
		empty($input) ? $this->flags()->detach() :  $this->flags()->sync($input);
    }

    /**
     * Returns departments's current users .
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
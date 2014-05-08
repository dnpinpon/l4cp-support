<?php

class TicketAutoreply  extends Eloquent {

	protected $table = 'ticket_autoreply';
	public static $unguarded = true;
	
	public function deps()
    {
        return $this->belongsToMany('TicketDeps', 'ticket_autoreply_deps');
    }

	public function actions()
    {
        return $this->belongsToMany('TicketActions', 'ticket_autoreply_actions');
    }

	public function roles()
    {
        return $this->belongsToMany('Role', 'ticket_autoreply_roles');
    }

    /**
     * Save deps inputted from multiselect
     * @param $inputRoles
     */
    public function saveDeps($input)
    {
		empty($input) ? $this->deps()->detach() :  $this->deps()->sync($input);
    }

    /**
     * Save deps inputted from multiselect
     * @param $inputRoles
     */
    public function saveRoles($input)
    {
		empty($input) ? $this->roles()->detach() :  $this->roles()->sync($input);
    }

    /**
     * Save deps inputted from multiselect
     * @param $inputRoles
     */
    public function saveActions($input)
    {
		empty($input) ? $this->actions()->detach() :  $this->actions()->sync($input);
    }

	/**
     * Returns escalations's current deparment ids only.
     * @return array|bool
     */
    public function currentDepIds()
    {
        $roles = $this->deps;
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

	/**
     * Returns escalations's current deparment ids only.
     * @return array|bool
     */
    public function currentActions()
    {
        $roles = $this->actions;
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
	/**
     * Returns escalations's current deparment ids only.
     * @return array|bool
     */
    public function currentRoles()
    {
        $roles = $this->roles;
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
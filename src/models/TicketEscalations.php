<?php

class TicketEscalations  extends Eloquent {

	protected $table = 'ticket_escalations';
	public static $unguarded = true;

	public function deps()
    {
        return $this->belongsToMany('TicketDeps', 'ticket_escalations_deps');
    }

	public function statuses()
    {
        return $this->belongsToMany('TicketStatuses', 'ticket_escalations_sta');
    }

	public function flags()
    {
        return $this->belongsToMany('User', 'ticket_escalations_flags');
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
     * Save statuses inputted from multiselect
     * @param $inputRoles
     */
    public function saveStatuses($input)
    {
		empty($input) ? $this->statuses()->detach() :  $this->statuses()->sync($input);
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
     * Returns escalations's current statuses ids only.
     * @return array|bool
     */
    public function currentStatusesIds()
    {
        $roles = $this->statuses;
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
<?php
/**
 * phpMySandBox - Simple Database Framework in PHP
 *
 * Roles support library.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License', or
 * ('at your option) any later version.
 * (Roman Travé <roman.trave@gmail.com>, 2012)
 *
 * @package    phpMySandBox
 * @subpackage Libraries\Objects
 * @license    http://opensource.org/licenses/gpl-license.php  GNU Public License
 * @author     Roman Travé <roman.trave@gmail.com>
 */

// No direct access.
defined('_MySBEXEC') or die;

/**
 * Role class
 *
 * @package    phpMySandBox
 * @subpackage Libraries\Objects
 */
class MySBRole extends MySBObject {

    /**
     * Unique id
     * @var    integer
     */
    public $id = null;

	/**
	 * Role name
	 * @var    string
	 */
	public $name = null;

	/**
	 * Role comments
	 * @var    string
	 */
	public $comments = null;


    /**
     * Role constructor.
     * @param   integer $id             ID of role, -1 for new role
     * @param   array   $data_role      array of datas
     */
    public function __construct($id=-1,$data_role=array()) {
        global $app;
        if($id!=-1) {
            $req_role = MySBDB::query("SELECT * FROM ".MySB_DBPREFIX.'roles '.
                'WHERE id='.$id,
                "MySBRole::__construct($id)");
            $data_role = MySBDB::fetch_array($req_role);
        }
        parent::__construct((array) ($data_role));
    }

    /**
     * Update role parameters (instance and DB)
     * @param   array   $data_role      array of datas
     */
    public function update($data_role=array()) {
        global $app;
        parent::__update('roles', (array) ($data_role));
    }

    /**
     * Assign role to group
     * @param   string  $group_name     Role is assigned or not to this group
     * @param   bool    $value          true:assigned and false:unassigned
     */
    public function assignToGroup($group_name,$value=true) {
        global $app;
        if($value==true) $ivalue = 1; else $ivalue = 0;
        $this->update(array( 'g'.MySBGroupHelper::getIDByName($group_name) => $ivalue ));
    }

   /**
     * Assign role to an array of groups
     * @param   array   $groups     Array of (Group,value)
     */
    public function assignToGroups($groups) {
        global $app;
        foreach($groups as $group) {
            if($group[1]==true) $ivalue = 1; else $ivalue = 0;
            $this->update(array( 'g'.$group[0]->id => $ivalue ));
        }
    }

   /**
     * Is this role assigned to group ?
     * @param   MySBGroup   $group     group to check
     */
    public function isAssignToGroup($group) {
        global $app;
        $group_key = 'g'.$group->id;
        if( isset($this->$group_key) and $this->$group_key==1 )
            return true;
        return false;
    }

}


/**
 * Role Helper class
 *
 * @package    phpMySandBox
 * @subpackage Libraries\Objects
 */
class MySBRoleHelper {

    /**
     * Create new role
     * @param   string $name       Name of role
     * @param   string $comments   Explicit comment
     * @return  MySBRole
     */
    public static function create($name,$comments) {
        global $app;
        $req_checkname = MySBDB::query('SELECT * FROM '.MySB_DBPREFIX.'roles '.
            "WHERE name='".$name."'",
            "MySBRoleHelper::create($name)");
        if(MySBDB::num_rows($req_checkname)>=1) {
            $data_checkname = MySBDB::fetch_array($req_checkname);
            $app->LOG( 'MySBRoleHelper::create(): name "'.$data_checkname['name'].'" already exists !' );
            $new_role = new MySBRole(-1, $data_checkname);
        } else {
            $rdata = array(
                'id' => MySBDB::lastID('roles')+1,
                'name' => $name, 'comments' => $comments );
            $req_newrole = MySBDB::query('INSERT INTO '.MySB_DBPREFIX.'roles '.
                '(id) VALUES ('.$rdata['id'].')',
                "MySBRoleHelper::create($name)");
            $new_role = new MySBRole(-1,$rdata);
            $new_role->update($rdata);
        }
        if(isset($app->cache_roles))
            $app->cache_roles[$new_role->id] = $new_role;
        return $new_role;
    }

    /**
     * Delete a role
     * @param   string  $name       Role name to delete, or self object
     */
    public static function delete($name='') {
        global $app;
        if($name!='') $role_name = $name;
        else $role_name = $this->name;
        $role = MySBRoleHelper::getByName($name);
        if($role==null) return;
        $req_delrole = MySBDB::query('DELETE from '.MySB_DBPREFIX."roles ".
            "WHERE name='".$role_name."' ",
            "MySBRoleHelper::delete($name)");
        $app->LOG('MySBRoleHelper::delete(): role '.$role_name.' deleted');
        if(isset($app->cache_roles))
            unset($app->cache_roles[$role->id]);
    }

    /**
     * Load an array of all roles
     * @return   array      array returned
     */
    public static function load() {
        global $app;
        if(isset($app->cache_roles)) return $app->cache_roles;
        $app->cache_roles = array();
        $req_roles = MySBDB::query("SELECT * FROM ".MySB_DBPREFIX."roles ".
                "ORDER BY id",
                "MySBRoleHelper::load()",
                true, '', true );
        while($data_role = MySBDB::fetch_array($req_roles)) {
            $app->cache_roles[$data_role['id']] = new MySBRole(-1, $data_role);
        }
        return $app->cache_roles;
    }

    /**
     * Verify the login access against a role.
     * @param    string      $role       Name of role to chcek against
     * @param    boolean     $alert      Stop the process ? (default=true)
     * @return   boolean
     */
    public static function checkAccess($role,$alert=true) {
        global $app;
        if ($role=='') return true;
        if(!isset($app->auth_user) or !$app->auth_user->haveRole($role)) {
            if($alert)
                $app->displayStopAlert(_G('SBGT_unauthorised_alert'));
            return false;
        }
        return true;
    }

    /**
     * Get a role object
     * @param    string      $name       Name of the role
     */
    public static function getByName($name) {
        global $app;
        $roles = MySBRoleHelper::load();
        foreach($roles as $role)
            if($role->name==$name) return $role;
        return null;
    }

}

?>

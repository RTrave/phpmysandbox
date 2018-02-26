<?php
/**
 * phpMySandBox - Simple Database Framework in PHP
 *
 * Base DB objects class.
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
 * Group class
 *
 * @package    phpMySandBox
 * @subpackage Libraries\Objects
 */
class MySBGroup extends MySBObject {

    /**
     * Unique id  (0 is admin group)
     * @var    integer
     */
    public $id = null;

	/**
	 * Group name
	 * @var    string
	 */
	public $name = null;

	/**
	 * Group comments
	 * @var    string
	 */
	public $comments = null;


    /**
     * Group constructor.
     * @param   integer     $id             ID of group, -1 for new group
     * @param   array       $data_group     Group parameters
     */
    public function __construct($id=-1,$data_group=array()) {
        global $app;
        if($id!=-1) {
            $req_group = MySBDB::query("SELECT * FROM ".MySB_DBPREFIX."groups ".
                "WHERE id=".$id,
                "MySBGroup::__construct($id)" );
            $data_group = MySBDB::fetch_array($req_group);
        }
        parent::__construct((array) ($data_group));
    }

    /**
     * Update group parameters (instance and DB)
     * @param   array   $data_group         Group parameters
     */
    public function update($data_group=array()) {
        global $app;
        parent::__update('groups', (array) ($data_group));
    }

}


/**
 * Group Helper class
 *
 * @package    phpMySandBox
 * @subpackage Libraries\Objects
 */
class MySBGroupHelper {

    /**
     * Create new group
     * @param   $name           Group name
     * @param   $comments       Group explicit comment
     * @param   $is_default     Are users assigned in group by default ?
     * @return  MySBGroup
     */
    public static function create($name,$comments,$is_default=false) {
        global $app;
        $req_checkname = MySBDB::query("SELECT * FROM ".MySB_DBPREFIX."groups ".
            "WHERE name='".$name."'",
            "MySBGroupHelper::create($name)" );
        $data_checkname = MySBDB::fetch_array($req_checkname);
        if($data_checkname['name']!='') {
            $app->LOG( 'MySBGroupHelper::create(): name "'.$data_checkname['name'].'" already exists !' );
            $new_group = new MySBGroup(-1, $data_checkname);
        } else {
            $gdata = array(
                'id' => MySBDB::lastID('groups')+1,
                'name' => $name, 'comments' => $comments );
            $req_newgroup = MySBDB::query("INSERT INTO ".MySB_DBPREFIX."groups ".
                "(id) VALUES ".
                "(".$gdata['id'].")",
                "MySBGroupHelper::create($name)", false );
            $new_group = new MySBGroup(-1,$gdata);
            $new_group->update($gdata);

            MySBDB::query("ALTER TABLE ".MySB_DBPREFIX."users ADD ".
                "g".$new_group->id." boolean",
                "MySBGroupHelper::create($name)" );
            MySBDB::query("ALTER TABLE ".MySB_DBPREFIX."roles ADD ".
                "g".$new_group->id." boolean",
                "MySBGroupHelper::create($name)" );

	        if($is_default==true) $ivalue = 1; else $ivalue = 0;
            MySBDB::query("UPDATE ".MySB_DBPREFIX."users SET ".
                "g".$new_group->id."=".$ivalue,
                "MySBGroupHelper::create($name)" );
            $groupname = "g".$new_group->id;
            if( !empty($app->auth_user) )
                $app->auth_user->$groupname = $ivalue; //hack to update auth_user groups
            MySBDB::query("UPDATE ".MySB_DBPREFIX."roles SET ".
                "g".$new_group->id."=0",
                "MySBGroupHelper::create($name)" );
        }
        if(isset($app->cache_groups))
            $app->cache_groups[$new_group->id] = $new_group;
        return $new_group;
    }

    /**
     * Delete a group
     * @param   $name               Group name to delete, or self group
     */
    public static function delete($name='') {
        global $app;
        if($name!='') $group_id = MySBGroupHelper::getIDByName($name);
        else $group_id = $this->id;
        if($group_id=='') return;
        MySBDB::query("DELETE FROM ".MySB_DBPREFIX."groups ".
            "WHERE id=".$group_id,
            "MySBGroupHelper::delete($name)" );
        MySBDB::query("ALTER TABLE ".MySB_DBPREFIX."users ".
            "DROP g".$group_id,
            "MySBGroupHelper::delete($name)" );
        MySBDB::query("ALTER TABLE ".MySB_DBPREFIX."roles ".
            "DROP g".$group_id,
            "MySBGroupHelper::delete($name)" );
        $app->LOG('MySBGroupHelper::delete(): group '.$name.' deleted');
        if(isset($app->cache_groups))
            unset($app->cache_groups[$group_id]);
    }

    /**
     * Load all groups (cached load)
     * @return  array             array of MySBGroup
     */
    public static function load() {
        global $app;
        if(isset($app->cache_groups)) return $app->cache_groups;
        $app->cache_groups = array();
        $req_groups = MySBDB::query("SELECT * FROM ".MySB_DBPREFIX."groups ".
                "ORDER BY id",
                "MySBGroupHelper::load()",
                true, '', true );
        while($data_group = MySBDB::fetch_array($req_groups)) {
            $app->cache_groups[$data_group['id']] = new MySBGroup(-1, $data_group);
        }
        return $app->cache_groups;
    }

    /**
     * Get group id by name
     * @param   string  $name        Group name
     * @return  integer             Id of the search group
     */
    public static function getIDByName($name) {
        global $app;
        $groups = MySBGroupHelper::load();
        foreach($groups as $group) {
            if($group->name==$name) return $group->id;
        }
        $app->LOG('MySBGroupHelper::getIDByName() : group '.$name.'not found !!!');
        return '';
    }

    /**
     * Get group id by name
     * @param   interger    $id        Group Id
     * @return  array           array of MySBGroup
     */
    public static function getByID($id) {
        global $app;
        $groups = MySBGroupHelper::load();
        if( $groups[$id]=='' )
            $app->LOG('MySBGroupHelper::getByID() : group id='.$id.' not found !!!');
        return $groups[$id];
    }


}

?>

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
 * User class
 *
 * @package    phpMySandBox
 * @subpackage Libraries\Objects
 */
class MySBUser extends MySBObject {

    /**
     * Unique id  (0 is default_user, 1 is admin)
     * @var    integer
     */
    public $id = null;

	/**
	 * User login
	 * @var    string
	 */
	public $login = null;

	/**
	 * User lastname
	 * @var    string
	 */
	public $lastname = null;

	/**
	 * User firstname
	 * @var    string
	 */
	public $firstname = null;

	/**
	 * User mail
	 * @var    string
	 */
	public $mail = null;

	/**
	 * User activation flag
	 * @var    integer
	 */
	public $active = 0;


    /**
     * User constructor.
     * @param   integer $id             ID of user, -1 for new user
     * @param   array   $data_user      params to set the MySBUser object
     */
    public function __construct($id,$data_user=array()) {
        global $app;
        if($id!=-1) {
            $req_user = MySBDB::query("SELECT * FROM ".MySB_DBPREFIX."users ".
                "WHERE id=".$id,
                "MySBUser::__construct($id)" );
            $data_user = MySBDB::fetch_array($req_user);
            if($data_user['id']=='')
                $app->LOG('MySBUser::__construct(): user id='.$id.' not found!');
        }
        parent::__construct((array) ($data_user));
        $pluginsUserOption = MySBPluginHelper::loadByType('UserOption');
        foreach($pluginsUserOption as $plugin) {
            $name = $plugin->value0;
            if( !isset($data_user[$name]) ) $data_user[$name] = '';
            $this->$name = $data_user[$name];
        }
    }

    /**
     * Update user parameters (instance and DB)
     * @param   array   $data_user      params to set the MySBUser object
     */
    public function update($data_user=array()) {
        global $app;
        parent::__update('users', (array) ($data_user));
    }

    /**
     * Activate user (instance and DB)
     */
    public function activate() {
        global $app;
        MySBDB::query("UPDATE ".MySB_DBPREFIX."users ".
            "SET active=1 ".
            "WHERE id=".$this->id,
            "MySBUser::activate()" );
        $this->active = 1;
    }

    /**
     * Desactivate user (instance and DB)
     */
    public function desactivate() {
        global $app;
        MySBDB::query("UPDATE ".MySB_DBPREFIX."users ".
            "SET active=0 ".
            "WHERE id=".$this->id,
            "MySBUser::desactivate()" );
        $this->active = 0;
    }

    /**
     * Reset user password (in DB)
     * @param   string  $password   new password
     * @param   integer $id         optional ID of the user, else self
     */
    public function resetPassword($password,$id='') {
        global $app;
        if($id=='') $id = $this->id;
        MySBDB::query("UPDATE ".MySB_DBPREFIX."users ".
            "SET passwd='".password_hash($password,  PASSWORD_DEFAULT)."' ".
            "WHERE id=".$id,
            "MySBUser::resetPassword('password',$id)" );
        MySBDB::query("UPDATE ".MySB_DBPREFIX."users ".
            "SET logattempt_nb=0 ".
            "WHERE id=".$id,
            "MySBUser::resetPassword('password',$id)" );
    }

    /**
     * Assign user to group
     * @param   string  $group_name     User is assigned or not to this group
     * @param   bool    $value          true:assigned and false:unassigned
     */
    public function assignToGroup($group_name,$value=true) {
        global $app;
        if($value==true) $ivalue = 1; else $ivalue = 0;
        $this->update( array( 'g'.MySBGroupHelper::getIDByName($group_name) => $ivalue ) );
        unset ($this->myrole);
    }

   /**
     * Assign user to an array of groups
     * @param   array   $groups     Array of (group,value) to assign
     */
    public function assignToGroups($groups) {
        global $app;
        foreach($groups as $group) {
            if($group[1]==true) $ivalue = 1; else $ivalue = 0;
            $this->update( array( 'g'.$group[0]->id => $ivalue ) );
        }
        unset ($this->myrole);
    }

    /**
     * Verify if user is in group
     * @param   int   $group_id       Group to verify
     */
    public function haveGroup($group_id) {
        $fieldname = 'g'.$group_id;
        if(isset($this->$fieldname) and $this->$fieldname==1) 
            return true;
        return false;
    }

    /**
     * Verify if user has role
     * @param   string  $role       Role to verify
     */
    public function haveRole($role) {
        global $app;

        if ($role=='') { return true;}
        $login = $this->login;

        if( isset($this->myrole) and isset($this->myrole[$role]) and $this->myrole[$role]!='' )
            return $this->myrole[$role];
        else $this->myrole = array();
        $current_role = MySBRoleHelper::getByName($role);

        $groups = MySBGroupHelper::load();

        $this->myrole[$role] = false;
        foreach($groups as $group) {
            $group_name = 'g'.$group->id;
            if( isset($current_role->$group_name) and $current_role->$group_name==1 and
                isset($this->$group_name) and $this->$group_name==1 ) {
                //echo $this->login.' have role '.$current_role->name.'<br>';
                $this->myrole[$role] = true;
                return true;
            }
        }
        return $this->myrole[$role];
    }

    /**
     * Set last_login date.
     */
    public function setLoginDate() {
        global $app;
        $today = getdate();
        $today_date =   $today['year'].'-'.$today['mon'].'-'.$today['mday'].' '.
                        $today['hours'].':'.$today['minutes'].':'.$today['seconds'];
        MySBDB::query("UPDATE ".MySB_DBPREFIX."users SET ".
            "last_login='".$today_date."',".
            "logattempt_nb=0 ".
            "WHERE id=".$this->id,
            "MySBUser::setLoginDate()" );
    }

    /**
     * Get user by login
     * @param   $login            Search user login
     * @return  MySBUser
     */
    public function checkMailattempt() {
        global $app;
        $dtoday = new MySBDateTime('NOW');
        if( !empty($this->mailattempt_date) ) {
            $ldate = new MySBDateTime($this->mailattempt_date);
            $ddiff = $ldate->absDiff('i');
            if( $ddiff<=1440 )
                $app->displayStopAlert(_G('SBGT_mailattempt_max'),10,false);
        }
        $this->update( array( 'mailattempt_date'=>$dtoday->date_string ) );
        return true;
    }

}


/**
 * User Helper class
 *
 * @package    phpMySandBox
 * @subpackage Libraries\Objects
*/
class MySBUserHelper {

    /**
     * Create new user
     * @param   string  $login      user login
     * @param   string  $lastname   user lastname
     * @param   string  $firstname  user firstname
     * @param   string  $mail       user mail
     * @return  MySBUser    created MySBUser
     */
    public static function create($login,$lastname,$firstname,$mail) {
        global $app;
        $req_checklogin = MySBDB::query("SELECT login FROM ".MySB_DBPREFIX."users ".
            "WHERE login='".$login."'",
            "MySBUserHelper::create($login)" );
        $data_checklogin = MySBDB::fetch_array($req_checklogin);
        if($data_checklogin['login']!='') {
            $app->ERR( 'MySBUserHelper::create(): login "'.$data_checklogin['login'].'" already exists !' );
            $new_user = null;
        } else {
            $udata = array(
                'id' => MySBDB::lastID('users')+1, 'login' => $login,
                'lastname' => $lastname, 'firstname' => $firstname, 'mail' => $mail );
            MySBDB::query("INSERT INTO ".MySB_DBPREFIX."users ".
                "(id,active) VALUES ".
                "(".$udata['id'].",0)",
                "MySBUserHelper::create($login)" );
            $new_user = new MySBUser(-1,$udata);
            $new_user->update($udata);

            $req_default = MySBDB::query("SELECT * FROM ".MySB_DBPREFIX."users ".
                "WHERE id=0",
                "MySBUserHelper::create($login)" );
            $data_default = MySBDB::fetch_array($req_default);
            $req_groups = MySBDB::query("SELECT * FROM ".MySB_DBPREFIX."groups",
                "MySBUserHelper::create($login)" );

            $coma = 0;
            $sql_setdefault = 'UPDATE '.MySB_DBPREFIX."users SET ";
            while($group = MySBDB::fetch_array($req_groups)) {
                if($coma==0) $coma = 1;
                else $sql_setdefault .= ',';
                $sql_setdefault .= 'g'.$group['id'].'='.$data_default['g'.$group['id']];
            }
            $sql_setdefault .= ' WHERE id='.$new_user->id;
            $req_setdefault = MySBDB::query($sql_setdefault,
                "MySBUserHelper::create($login)" );
            $app->LOG( 'MySBUserHelper::create(): user "'.$login.'" created.' );
        }

        if(!isset($app->cache_users)) $app->cache_users = array();
        $app->cache_users[$new_user->id] = $new_user;

        return $new_user;
    }

    /**
     * Delete user
     * @param   $id         user id to delete, self if null
     */
    public static function delete($id=null) {
        global $app;
        $cuser = new MySBUser($id);
        if(!isset($cuser) or $cuser==null or $cuser->id=='' ) {
            $app->ERR("MySBUserHelper::delete(): user ID ".$id." not found.");
            return;
        }
        $app->LOG("MySBUserHelper::delete(): user with ID ".$id." deleted.");
        MySBDB::query("DELETE from ".MySB_DBPREFIX."users ".
            "WHERE id=".$id,
            "MySBUserHelper::delete($id)" );
    }

    /**
     * Get user by login
     * @param   $login            Search user login
     * @return  MySBUser
     */
    public static function getByLogin($login) {
        global $app;

        if(!isset($app->cache_users)) $app->cache_users = array();
        foreach($app->cache_users as $user)
            if($user->login==$login) return $user;

        $req_bylogin = MySBDB::query('SELECT * FROM '.MySB_DBPREFIX."users ".
            "WHERE login='".$login."'",
            "MySBUserHelper::getByLogin($login)" );
        if(MySBDB::num_rows($req_bylogin)>=1) {
            $data_bylogin = MySBDB::fetch_array($req_bylogin);
            $userbylogin = new MySBUser(-1,$data_bylogin);
            $app->cache_users[$userbylogin->id] = $userbylogin;
        } else {
            $userbylogin = null;
            //$app->ERR( 'MySBUserHelper::getByLogin(): login '.$login.' don\'t exists !!!' );
        }
        return $userbylogin;
    }

    /**
     * Get user by mail
     * @param   $login            Search user login
     * @return  MySBUser
     */
    public static function getByMail($mail) {
        global $app;
        $users = array();
        if(!isset($app->cache_users)) 
            $app->cache_users = array();
        else {
            foreach($app->cache_users as $user)
                if($user->mail==$mail) $users[] = $user;
            return $users;
        }
        $req_bymail = MySBDB::query('SELECT * FROM '.MySB_DBPREFIX."users ".
            "WHERE mail='".$mail."'",
            "MySBUserHelper::getByMail($mail)" );
        while( $data_bymail = MySBDB::fetch_array($req_bymail) ) {
            $userbymail = new MySBUser(-1,$data_bymail);
            $app->cache_users[$userbymail->id] = $userbymail;
            $users[] = $userbymail;
        }
        return $users;
    }

    /**
     * Get user by ID
     * @param   integer     $id            Search user Id
     * @return  MySBUser
     */
    public static function getByID($id) {
        global $app;

        if(!isset($app->cache_users)) $app->cache_users = array();
        foreach($app->cache_users as $user)
            if($user->id==$id) return $user;

        $req_byid = MySBDB::query('SELECT * FROM '.MySB_DBPREFIX."users ".
            "WHERE id='".$id."'",
            "MySBUserHelper::getByID($id)" );
        if(MySBDB::num_rows($req_byid)>=1) {
            $data_byid = MySBDB::fetch_array($req_byid);
            $userbyid = new MySBUser(-1,$data_byid);
            $app->cache_users[$userbyid->id] = $userbyid;
        } else {
            $userbyid = null;
            $app->LOG( 'MySBUserHelper::getByID(): id '.$id.' don\'t exists !!!' );
        }
        return $userbyid;
    }

    /**
     * Search user by keyword
     * @param   string  $pattern    Search user with pattern
     * @param   string  $col        Column used to search
     * @return  array               Array of users
     */
    public static function searchBy($pattern,$col='login') {
        global $app;
        $users = array();
        $users_whereclause = '';
        if( $pattern!='' )
            $users_whereclause .= $col.' RLIKE \''.$pattern.'\'';
    if( $users_whereclause!='' ) 
        $users_whereclause = 'WHERE '.$users_whereclause;

        $req_bymail = MySBDB::query('SELECT * FROM '.MySB_DBPREFIX."users ".
            $users_whereclause,
            "MySBUserHelper::searchBy($pattern,$col)" );
        while( $data_bymail = MySBDB::fetch_array($req_bymail) ) {
            $userbymail = new MySBUser(-1,$data_bymail);
            $app->cache_users[$userbymail->id] = $userbymail;
            $users[] = $userbymail;
        }
        return $users;
    }

    /**
     * Check for login attempts
     * @param   string      $login  Search user login
     * @return  boolean             false if OK
     */
    public static function checkLogattempt($login) {
        global $app;

        $req_bylogin = MySBDB::query('SELECT * FROM '.MySB_DBPREFIX."users ".
            "WHERE login='".$login."'",
            "MySBUserHelper::checkLogattempt($login)" );

        if( $data_bylogin = MySBDB::fetch_array($req_bylogin) ) {
            $dtoday = new MySBDateTime('NOW');
            $ldate = new MySBDateTime($data_bylogin['logattempt_date']);
            $ddiff = $ldate->absDiff('i');
            if( $data_bylogin['logattempt_nb']>=5 and $ddiff<=5 ) {
                $app->displayStopAlert(_G('SBGT_logattempt_max'),10,false);
            }
            MySBDB::query("UPDATE ".MySB_DBPREFIX."users SET ".
                "logattempt_date='".$dtoday->date_string."', ".
                "logattempt_nb=".($data_bylogin['logattempt_nb']+1)." ".
                "WHERE id=".$data_bylogin['id'],
                "MySBUserHelper::checkLogattempt($login)" );
        }
        return false;
    }

    /**
     * Check if user if authenticated.
     * @return  MySBUser
     */
    public static function checkAuth() {
        global $app,$_POST,$_COOKIE;

        $user_login = null;
        $pluginsAuthLayer = MySBPluginHelper::loadByType('AuthLayer');

        foreach($pluginsAuthLayer as $plugin) {
            $user_login = $plugin->checkAuth();
            if( $user_login===null ) {
                continue;
            } elseif( $user_login===false ) {
                $app->displayStopAlert(_G('SBGT_bad_auth'),10,false);
            } elseif( $user_login!=null ) {
                $user_login->auth = $plugin;
                $user_login->setLoginDate();
                break;
            }
        }

        if( $user_login!=null and isset($_GET['logout_flag']) ) { // LOGOUT
            $user_login->auth->logout();
            $app->resetSession();
            $app->displayStopAlert(_G('SBGT_logged_off'),3,false);
        }
        return $user_login;
    }

}

?>

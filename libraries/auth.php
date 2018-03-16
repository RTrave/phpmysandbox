<?php
/**
 * phpMySandBox - Simple Database Framework in PHP
 *
 * Native Auth layer plugin library.
 * 
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License', or
 * ('at your option) any later version. 
 * (Roman Travé <roman.trave@gmail.com>, 2012)
 *
 * @package    phpMySandBox
 * @subpackage Libraries\Core
 * @license    http://opensource.org/licenses/gpl-license.php  GNU Public License
 * @author     Roman Travé <roman.trave@gmail.com>
 */


// No direct access.
defined('_MySBEXEC') or die;

/**
 * Auth API.
 *
 * @package    phpMySandBox
 * @subpackage Libraries\APIs
 */

interface MySBIAuthLayer {

    /**
     * Check authentication process.
     * @return  MySBUser        return User object, false if wrong credentials
     */
    public function checkAuth();

    /**
     * HTML form (authbox call)
     * @return  string          HTML entity output
     */
    public function formAuthbox();

    /**
     * Logout procedure
     */
    public function logout();

}



/**
 * AuthLayer plugin support class.
 *
 * Plugin values
 *
 * value0: Layer name
 *
 * value1: Decription
 *
 * @package    phpMySandBox
 * @subpackage Libraries\Plugins
 */
class MySBPluginAuthLayer extends MySBPlugin implements MySBIAuthLayer {

    /**
     * Constructor.
     * @param   array   $plugin     parameters of plugin
     */
    public function __construct($plugin = array()) {
        parent::__construct((array) ($plugin));
    }

    /**
     * HTML form (authbox call)
     * @return  string              HTML entity output
     */
    public function formAuthbox() {
        global $app;
        $output = '
<form method="post">
<div>
  <div class="row">
    <label for="login">login:</label>
  </div>
  <div class="row">
    <input type="text" name="login" id="login" maxlength="32">
  </div>
  <div class="row">
    <label for="passwd">password:</label>
  </div>
  <div class="row">
    <input type="password" name="passwd" id="passwd" maxlength="32">
  </div>
  <div class="row" style="text-align: center; position: relative;">
    <input type="hidden" name="native_login" value="1">
    <input type="submit" class="btn-primary"
           value="'._G('SBGT_log_in').'">
  </div>
</div>
</form>
<span style="text-align: center;">
  <a href="index.php?tpl=users/reset_pw" class="resetpw">
    '._G('SBGT_forgot_password').'?</a>
</span>';
        return $output;
    }

    /**
     * Simple processto check password against MD5 login's one
     * @return  boolean              true if succes, false if not
     */
    static public function checkPassword($password) {
        global $app;
        if(!isset($app->auth_user) or $app->auth_user==null)
            return false;
        $req_getpass = MySBDB::query("SELECT * from ".MySB_DBPREFIX."users ".
            "WHERE login='".$app->auth_user->login."'",
            "MySBPluginAuthLayer::checkPassword()" );
        $data_getpass = MySBDB::fetch_array($req_getpass);
        if(md5($password)==$data_getpass['passwd']) 
            return true;
        return false;
    }

    /**
     * Html form process, die with bad authentication message if failed
     * @return  boolean              true if authentication is a succes, false for malformed credentials
     */
    private function formAuthbox_Process() {
        global $app, $_SESSION, $_POST;
        if( !isset($_POST['native_login']) ) //{ // LOGIN check
            return false;
        if( $_POST['login']=='' or $_POST['passwd']=='' )
            return false;
        if( !MySBUtil::strverif($_POST['login']) or
            !MySBUtil::strverif($_POST['passwd'],false) )
            return false;
        MySBUserHelper::checkLogattempt($_POST['login']);
        $req_getpass = MySBDB::query("SELECT * from ".MySB_DBPREFIX."users ".
            "WHERE login='".$_POST['login']."'",
            "MySBPluginAuthLayer::formAuthbox_Process()" );
        $data_getpass = MySBDB::fetch_array($req_getpass);
        if(md5($_POST['passwd'])==$data_getpass['passwd']) {
            $_SESSION['mysb_login'] = $_POST['login'];
            $urandom = session_id();
            MySBDB::query("UPDATE ".MySB_DBPREFIX."users ".
                "SET auth_rand='".$urandom."' ".
                "WHERE login='".$_POST['login']."'",
                "MySBPluginAuthLayer::formAuthbox_Process()" );
            return true;
        }
        return false;
    }

    /**
     * Authentication check procedure
     * @return  MySBUser        return User object, false if wrong credentials
     */
    public function checkAuth() {
        global $app,$_SESSION;
        if(!isset($_SESSION['mysb_login']) ) {
            if(!$this->formAuthbox_Process())
                if( isset($_POST['login']) and $_POST['login']!='' )
                    return false;
                else
                    return null;
        }
        $ulogin = $_SESSION['mysb_login'];
        $req_checkrand = MySBDB::query("SELECT * from ".MySB_DBPREFIX."users ".
            "WHERE login='".$ulogin."'",
            "MySBPluginAuthLayer::checkAuth()" );
        $data_checkrand = MySBDB::fetch_array($req_checkrand);
        $urandom = $_COOKIE[session_name()];
        if($data_checkrand['auth_rand']!=$urandom or $data_checkrand['auth_rand']=='') {
            $this->logout();
            $app->resetSession();
            $app->displayStopAlert(_G('SBGT_logged_off'),2,false);
        }
        $uuser = new MySBUser(-1,$data_checkrand);
        return $uuser;
    }

    /**
     * Logout procedure
     */
    public function logout() {
        global $app,$_SESSION;
        unset($_SESSION['mysb_login']);
    }
}
?>

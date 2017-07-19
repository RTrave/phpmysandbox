<?php
/**
 * phpMySandBox - Simple Database Framework in PHP
 *
 * UserOption plugin support library.
 * 
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License', or
 * ('at your option) any later version. 
 * (Roman Travé <roman.trave@gmail.com>, 2012)
 *
 * @package    phpMySandBox
 * @subpackage Libraries\Plugins
 * @license    http://opensource.org/licenses/gpl-license.php  GNU Public License
 * @author     Roman Travé <roman.trave@gmail.com>
 */


// No direct access.
defined('_MySBEXEC') or die;

/**
 * UserOption plugin support class.
 *
 * Plugin values
 *
 * value0: Option name
 *
 * value1: Decription
 *
 * value2: Mail to notify changes
 *
 * value3: Default value (0/1 for checkbox)
 *
 * ivalue0: Data type (type from libraries/value.php)
 *
 * ivalue1: User can edit in profile.
 *
 * @package    phpMySandBox
 * @subpackage Libraries\Plugins
 */
class MySBPluginUserOption extends MySBPlugin {

    /**
     * @var MySBValue               reference to MySBValue object
     */
    public $uo_value = null;

    /**
     * Constructor.
     * @param   array   $plugin     parameters of plugin
     */
    public function __construct($plugin = array()) {
        parent::__construct((array) ($plugin));
        $this->uo_value = new MySBValue(array(
            'type' => $this->ivalue0,
            'keyname' => $this->value0 ));
    }

    /**
     * Post creation callback.
     */
    public function post_create() {
        global $app;
        MySBDB::query("ALTER TABLE ".MySB_DBPREFIX."users ".
            "ADD COLUMN ".$this->value0." ".MySBValue::Val2SQLType($this->ivalue0)."",
            "MySBPluginUserOption::post_create()",
            false );
    }

    /**
     * Pre deletion callback.
     */
    public function pre_delete() {
        global $app;
        MySBDB::query("ALTER TABLE ".MySB_DBPREFIX."users ".
            "DROP COLUMN ".$this->value0,
            "MySBPluginUserOption::pre_delete()",
            false );
    }

    /**
     * Html form (plugin edition)
     * @return  string          HTML entity output
     */
    public function html_valueform() {
        global $app;
        $output = '';
        $output .= '
<div class="row">
    <div class="right"><input type="text" name="plg_optval_name" value="'.$this->value0.'"></div>
    Option name
</div>
<div class="row">
    <div class="rightA" style="displayA: inline-block; right: 0px; text-align: right;"><textarea name="plg_optval_text">'.$this->value1.'</textarea></div>
    Option text<br>
</div>
<div class="row">
    <div class="right"><input type="checkbox" name="plg_optval_useredit" '.MySBUtil::form_ischecked($this->ivalue1,1).'></div>
    Option editable by users
</div>
<div class="row">
    <div class="right"><input type="text" name="plg_optval_default" value="'.$this->value3.'"></div>
    Option default value (1 for checkbox checked)
</div>
<div class="row">
    <div class="right"><input type="text" name="plg_optval_mail" value="'.$this->value2.'"></div>
    Option mail contact
</div>';
        return $output;
    }

    /**
     * Html form process (plugin edition)
     */
    public function html_valueprocess() {
        global $app, $_POST;
        if( isset($_POST['plg_optval_useredit']) and $_POST['plg_optval_useredit']=='on' ) 
            $optval_useredit = 1;
        else $optval_useredit = '';
        $this->update(array(
            'value0' => $_POST['plg_optval_name'],
            'value1' => $_POST['plg_optval_text'],
            'value2' => $_POST['plg_optval_mail'],
            'value3' => $_POST['plg_optval_default'],
            'ivalue1' => $optval_useredit ));
    }


    /**
     * HTML form (value edition)
     * @param   MySBUser    $user   user, or auth_user, or new user
     * @return  string              HTML entity output
     */
    public function formDisplay($user=null) {
        global $app;
        if($user==null and $app->auth_user!=null) $user = $app->auth_user;
        if($user==null and $app->auth_user==null) 
            return $this->uo_value->htmlForm('uo_', $this->value3);
        $req_ou = MySBDB::query("SELECT * from ".MySB_DBPREFIX."users ".
            "WHERE id=".$user->id,
            "MySBPluginUserOption::formDisplay()" );
        $data_ou = MySBDB::fetch_array($req_ou);
        return $this->uo_value->htmlForm('uo_', $data_ou[$this->value0]);
    }
    
    /**
     * Html form process (value edition)
     * 
     * @param   MySBUser    $user   user, or auth_user, or new user
     */
    public function formProcess($user=null) {
        global $app, $_POST;
        if($user==null) $user = $app->auth_user;
        $getvalue = $this->uo_value->htmlProcessValue('uo_');
        MySBDB::query("UPDATE ".MySB_DBPREFIX."users SET ".
            $this->value0."='".$getvalue."'".
            " WHERE id=".$user->id,
            "MySBPluginUserOption::formProcess()" );
        if($this->value2!='') {
            $valuename = $this->value0;
            if( $user->$valuename!=$getvalue ) {
                $uomail = new MySBMail('useroption');
                $uomail->addTO($this->value2,'');
                $uomail->data['geckos'] = '';
                $uomail->data['lastname'] = $user->lastname;
                $uomail->data['firstname'] = $user->firstname;
                $uomail->data['mail'] = $user->mail;
                $uomail->data['optioninfos'] = $this->value1.' ('.$this->value0.')';
                if($getvalue=='') $uomail->data['status'] = 'NULL';
                else $uomail->data['status'] = $getvalue;
                $uomail->send();
            }
        }
    }

}
?>

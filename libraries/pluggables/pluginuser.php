<?php
/***************************************************************************
 *
 *   phpMySandBox - TRoman<roman.trave@abadcafe.org> - 2022
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License', or
 *   ('at your option) any later version.
 *
***************************************************************************/

// No direct access.
defined('_MySBEXEC') or die;

/**
 * User plugin class
 * value0       Helper plugin class
 *
 * @package    phpMySandBox
 * @subpackage Libraries\Plugins
 */
class MySBPluginUser extends MySBPlugin {

    /**
     * PluginUser constructor.
     * @param   array       $plugin           Paremeters of plugin
     */
    public function __construct($plugin = array()) {
        parent::__construct((array) ($plugin));
    }

    /**
     * Callback after user creation
     * @param   MySBUser  $user     user created
     */
    public function post_usercreate($user) {
        global $app;
        if( $this->value0!='' and
            method_exists($this->value0, 'post_usercreate')) 
            $this->value0::post_usercreate($user);
    }

    /**
     * Callback before user deletion
     * @param   MySBUser  $user     user deleted
     */
    public function pre_userdelete($user) {
        global $app;
        if( $this->value0!='' and
            method_exists($this->value0, 'pre_userdelete') )
            $this->value0::pre_userdelete($user);
    }

    /**
     * Callback after user update
     * @param   MySBUser  $user     user updated
     */
    public function post_userupdate($user) {
        global $app;
        if( $this->value0!='' and
            method_exists($this->value0, 'post_userupdate')) 
            $this->value0::post_userupdate($user);
    }

    /**
     * Callback after user change in group group
     * @param   MySBUser  $user     user updated
     */
    public function post_userchangegroup($user) {
        global $app;
        if( $this->value0!='' and
            method_exists($this->value0, 'post_userchangegroup')) 
            $this->value0::post_userchangegroup($user);
    }


}

?>


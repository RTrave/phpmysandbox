<?php
/**
 * phpMySandBox - Simple Database Framework in PHP
 *
 * Frontpage plugin support library.
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
 * FrontPage plugin class
 * value0       Template path
 *
 * @package    phpMySandBox
 * @subpackage Libraries\Plugins
 */
class MySBPluginFrontPage extends MySBPlugin {

    /**
     * Plugin constructor.
     * @param   array   $plugin            Parameters of plugin
     */
    public function __construct($plugin = array()) {
        parent::__construct((array) ($plugin));
    }

    /**
     * Process form section
     * @param
     * @return  string     true if controler is loaded
     */
    public function callControler() {
        global $app;
        if( !isset($app->auth_user) or 
            MySBRoleHelper::checkAccess($this->role,false) ) {
            ob_start();
            include( _pathT($this->value0.'_ctrl',$this->module,false) );
            return ob_get_clean();
        }
        return '';
    }

    /**
     * Process form section
     * @param
     */
    public function processForms() {
        global $app;
        if(!isset($app->auth_user) or MySBRoleHelper::checkAccess($this->role,false)) 
            _incT($this->value0.'_process',$this->module,false);
    }

    /**
     * Display body section
     * @param
     */
    public function displayBody() {
        global $app;
        if(!isset($app->auth_user) or MySBRoleHelper::checkAccess($this->role,false)) 
            _incT($this->value0,$this->module);
    }

}

?>

<?php
/**
 * phpMySandBox - Simple Database Framework in PHP
 *
 * Include plugin support library.
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
 * Aditionnals include plugin class
 * value0       PHP file path to include
 *
 * @package    phpMySandBox
 * @subpackage Libraries\Plugins
 */
class MySBPluginInclude extends MySBPlugin {

    /**
     * PluginInclude constructor.
     * @param   array       $plugin           Paremeters of plugin
     */
    public function __construct($plugin = array()) {
        parent::__construct((array) ($plugin));
    }

    /**
     * Post-create process
     * @param   
     */
    public function post_create() {
        global $app;
        include (MySB_ROOTPATH.'/modules/'.$this->module.'/'.$this->value0);
    }

    /**
     * Include process
     * @param   
     */
    public function includeFile() {
        global $app;
        include (MySB_ROOTPATH.'/modules/'.$this->module.'/'.$this->value0);
    }

}

?>

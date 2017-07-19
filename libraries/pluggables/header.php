<?php
/**
 * phpMySandBox - Simple Database Framework in PHP
 *
 * Headers plugin support library.
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
 * Header declaration plugin class
 * value0       Header type (CSS,)
 * value1       CSS: file url
 *
 * @package    phpMySandBox
 * @subpackage Libraries\Plugins
 */
class MySBPluginHeader extends MySBPlugin {

    /**
     * PluginHeader constructor.
     * @param   array       $plugin           Paremeters of plugin
     */
    public function __construct($plugin = array()) {
        parent::__construct((array) ($plugin));
    }

    /**
     * Print header declaration
     * @param   
     */
    public function displayHeader() {
        global $app;
        if($this->value0=='CSS') echo '<link rel="stylesheet" type="text/css" href="modules/'.$this->module.'/'.$this->value1.'">';
    }

}

?>

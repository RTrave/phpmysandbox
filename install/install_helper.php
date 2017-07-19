<?php
/**
 * phpMySandBox - Simple Database Framework in PHP
 *
 * DB install and checks library.
 * 
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License', or
 * ('at your option) any later version. 
 * (Roman Travé <roman.trave@gmail.com>, 2012)
 *
 * @package    phpMySandBox
 * @subpackage Install
 * @license    http://opensource.org/licenses/gpl-license.php  GNU Public License
 * @author     Roman Travé <roman.trave@gmail.com>
 */


// No direct access.
defined('_MySBEXEC') or die;


/**
 * Install and checks helper class.
 *
 * Provide functions to check if files and tables are OK.
 * 
 * @package    phpMySandBox
 * @subpackage Install
*/
class MySBInstallHelper {

    /**
     * Check config.php existence
     *
     * @return  boolean     true if OK, message and die if NOT
     */
    public static function isConfigInit() {
        if(!file_exists(MySB_ROOTPATH.'/config.php')) {
            echo '<!DOCTYPE html>
<html>
<head>
    <title>PHPMySandBox - INSTALLATION</title>
    <link rel="stylesheet" type="text/css" href="mysb.css">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
</head>
<body>   
<div id="mysbMiddle"><div class="content">
<h3>!!!ERROR!!! : config.php not present.</h3>
<p>Edit <b>config.php.template</b> and rename it as <b>config.php</b>,<br>
and then, reload to init the DB: <br>
<a href="">Init DB - installation</a>
</p>
</div></div>
</body>
</html>';
            die;
        }
        return true;
    }

    /**
     * Check core tables existence
     *
     * @return  boolean     true if OK, false if NOT
     */
    public static function isInit() {
        if(MySBDB::table_exists('users'))
            return true;
        return false;
    }

}


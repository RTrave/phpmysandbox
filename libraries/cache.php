<?php
/**
 * phpMySandBox - Simple Database Framework in PHP
 *
 * SQL Cache handler library.
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
 * SQL Cache support class.
 *
 * Minimal per-load cache, to prevent some multiples SQL calls.
 *
 * @package    phpMySandBox
 * @subpackage Libraries\Core
 */
class MySBDBCache {

    /**
	 * @var    boolean      active cache flag
	 */
    public $enabled = false;


    /**
     * Constructor.
     * @param   MySBApplication     $app        application object
     */
    public function __construct($app) {
        $this->enabled = true;
        if( !isset($app->SESSION) ) 
            $app->SESSION = array();
    }

    /**
     * Get query result from cache
     * @param   string      $query              SQL query referenced in cache
     * @return  array                           query result object
     */
    public function get($query) {
        global $app;
        if( $this->enabled ) {
            $result = &$app->SESSION[$query];
            if( isset($result) and !empty($result) ) {
                MySBDB::data_seek($result,0);
                return $result;
            }
        }
        return null;
    }

    /**
     * Store query result in cache
     * @param   string      $query              SQL query to reference in cache
     * @param   array       $result             query result object to store in cache
     */
    public function store($query,$result) {
        global $app;
        if( $this->enabled ) 
            $app->SESSION[$query] = $result;
    }

}

?>

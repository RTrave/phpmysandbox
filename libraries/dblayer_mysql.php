<?php
/**
 * phpMySandBox - Simple Database Framework in PHP
 *
 * DB handler library: mysql support.
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
 * Mysql handler class.
 *
 * @package    phpMySandBox
 * @subpackage Libraries\Core
 */
class MySBDBLayer_mysql implements MySBIDBLayer {

    /**
     * @var    resource         réference to DB connection
     */
    public $db = null;


    /**
     * DB constructor.
     */
    public function __construct() {
    }

    /**
     * Connection to the base through the layer
     * @param   string      $dbhost         DB hostname or IP
     * @param   string      $dbuser         DB username
     * @param   string      $dbpasswd       DB user password
     * @param   string      $dbname         DB base name
     */
    public function connect($dbhost, $dbuser, $dbpasswd, $dbname ) {
        $this->db = mysql_connect($dbhost, $dbuser, $dbpasswd);
        mysql_select_db($dbname,$this->db);
    }

    /**
     * Close DB connection 
     * @param   
     */
    public function close() {
        mysql_close();
        $this->db = null;
    }

    /**
     * Send a SQL query
     * @param   string      $sql_query          SQL query
     * @return  object                      result object
     */
    public function query($sql_query) {
        $req_query = mysql_query($sql_query);
        return $req_query;
    }

    /**
     * Fetch the SQL query result
     * @param   array       $query_result       query result object
     * @return  array                           row as results array 
     */
    public function fetch_array($query_result) {
        return mysql_fetch_array($query_result);
    }

    /**
     * Return the result array size
     * @param   array       $query_result       query result object
     * @return  integer                         result row count
     */
    public function num_rows($query_result) {
        return mysql_num_rows($query_result);
    }

    /**
     * Move internal pointer
     * @param   array       $query_result       query result object
     * @param   int         $row_number         offset for result pointer
     */
    public function data_seek($query_result, $row_number) {
        mysql_data_seek($query_result, $row_number);
    }

    /**
     * Get the error
     * @return  string                          error string from the layer
     */
    public function error() {
    }

}

?>

<?php
/**
 * phpMySandBox - Simple Database Framework in PHP
 *
 * DB handler library.
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
 * DB Layer API.
 *
 * @package    phpMySandBox
 * @subpackage Libraries\APIs
 */
interface MySBIDBLayer {

    /**
     * Connection to the base through the layer
     * @param   string      $dbhost         DB hostname or IP
     * @param   string      $dbuser         DB username
     * @param   string      $dbpasswd       DB user password
     * @param   string      $dbname         DB base name
     */
    public function connect($dbhost, $dbuser, $dbpasswd, $dbname );

    /**
     * Send a SQL query
     * @param   string      $sql_query      SQL query
     * @return  object                      result object
     */
    public function query($sql_query);

    /**
     * Fetch the SQL query result
     * @param   array       $query_result       query result object
     * @return  array                           row as results array
     */
    public function fetch_array($query_result);

    /**
     * Return the result array size
     * @param   array       $query_result       query result object
     * @return  integer                         result row count
     */
    public function num_rows($query_result);

    /**
     * Move internal pointer
     * @param   array       $query_result       query result object
     * @param   int         $row_number         offset for result pointer
     */
    public function data_seek($query_result, $row_number);

    /**
     * Close DB connection
     */
    public function close();

    /**
     * Get the error
     * @return  string                          error string from the layer
     */
    public function error();

}


/**
 * Database connection class.
 *
 *
 *
 * @package    phpMySandBox
 * @subpackage Libraries\Core
 */
class MySBDB {

    /**
     * DB Layer constructor.
     * @return      MySBIDBLayer     réference to DB connection
     */
    public static function connect() {
        global $app;
        include(MySB_ROOTPATH.'/config.php');
        $dblayer_t = explode(':',$mysb_dblayer);
        $dblayer_class = 'MySBDBLayer_'.$dblayer_t[0];
        if( !class_exists($dblayer_class) )
            die('Bad DB layer .. exiting');
        if( isset($dblayer_t[1]) )
            $dblayer = new $dblayer_class($dblayer_t[1]);
        else
            $dblayer = new $dblayer_class();
        $dblayer->connect($mysb_dbhost, $mysb_dbuser, $mysb_dbpasswd, $mysb_dbname);
        return $dblayer;
    }

    /**
     * Close DB connection
     */
    public static function close() {
        global $app;
        $app->dblayer->close();
        $app->dblayer = null;
    }

    /**
     * Verifiy if a table exists
     * @param   string      $table      Canonical name of the table
     * @return  bool                    true if exists
     */
    public static function table_exists($table) {
        global $app;
        if( (   MySBDB::query("SELECT * FROM ".MySB_DBPREFIX.$table."",
                "MySBDB::table_exists($table)", false) ) )
            return true;
        else return false;
    }

    /**
     * Get the last ID of a table (-1 if empty)
     * @param   $table      array  symbolic name of the table
	 * @return  integer
     */
    public static function lastID($table) {
        global $app;
        $req_lastid = MySBDB::query('SELECT id from '.MySB_DBPREFIX.$table.' '.
            'ORDER BY id DESC',
            "MySBDB::lastID($table)" );
        $data_lastid = MySBDB::fetch_array($req_lastid);
        if($data_lastid['id']=='') return -1;
        return $data_lastid['id'];
    }

    /**
     * Get the first free ID of a table
     * @param   $table      array symbolic name of the table
	 * @return  integer
     */
    public static function firstID($table) {
        global $app;
        $req_firstid = MySBDB::query('SELECT id from '.MySB_DBPREFIX.$table.' '.
            'ORDER BY id',
            "MySBDB::firstID($table)" );
        $current_id = 1;
        while($data_firstid = MySBDB::fetch_array($req_firstid)) {
            if($data_firstid['id']>$current_id) return $current_id;

            $current_id = $data_firstid['id'] + 1;
        }
        return $current_id;
    }

    /**
     * Send a SQL query
     * @param   string      $sql_query          SQL query
     * @param   string      $function           calling function (log facility)
     * @param   boolean     $die                when fail occurs, process dies or not
     * @param   string      $module             module calling query()
     * @param   boolean     $cached             result can be cached or not
     * @return  object                          object result for the SQL query
     */
    public static function query($sql_query,$function='???::???()',$die=true,$module='',$cached=false) {
        global $app;
        if($module=='') $module = 'core';
        $querymask = 'sql_queries_'.$module;
        if(!isset($app->$querymask)) $app->$querymask = array();
        $sql_querymask = &$app->$querymask;
        $app->sql_queriesall[] = '['.$module.'] <b>'.$function."</b>:\n";
        $sql_querymask[] = '<b>'.$function."</b>:\n";

        if($cached==true) {
            $cache_result = $app->dbcache->get($sql_query);
            if($cache_result!=null) {
                $app->sql_queriesall[] = htmlspecialchars($sql_query).' <b>(from cache)</b>'; //MySBUtil::str2html
                return $cache_result;
            }
        }

        $app->sql_queriesnb++;
        //echo $sql_query;
        //print_r($app->sql_queriesall);
        if ($die==true)
            $req_query = $app->dblayer->query($sql_query)
                or $app->ERR($function.": ".htmlspecialchars($sql_query)."\nSQL Error: ".$app->dblayer->error(),$module);
        else
            $req_query = $app->dblayer->query($sql_query)
                or $app->LOG($function.": ".htmlspecialchars($sql_query)."\nSQL Error: ".$app->dblayer->error(),$module);

        if($cached==true) {
            $app->dbcache->store($sql_query,$req_query);
            $app->sql_queriesall[] = htmlspecialchars($sql_query).' <b>(saved in cache)</b>';
        } else {
            $app->sql_queriesall[] = htmlspecialchars($sql_query);
        }
        return $req_query;
    }

    /**
     * Fetch the SQL query result
     * @param   array       $query_result       query result object
     * @return  array                           row as results array
     */
    public static function fetch_array($query_result) {
        global $app;
        return $app->dblayer->fetch_array($query_result);
    }

    /**
     * Return the result array size
     * @param   array       $query_result       query result object
     * @return  integer                         result row count
     */
    public static function num_rows($query_result) {
        global $app;
        return $app->dblayer->num_rows($query_result);
    }

    /**
     * Move internal pointer
     * @param   array       $query_result       query result object
     * @param   int         $row_number         offset for result pointer
     */
    public static function data_seek($query_result, $row_number) {
        global $app;
        if( MySBDB::num_rows($query_result)>=1 )
            $app->dblayer->data_seek($query_result, $row_number);
    }

}

?>

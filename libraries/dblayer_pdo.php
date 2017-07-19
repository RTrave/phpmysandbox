<?php
/**
 * phpMySandBox - Simple Database Framework in PHP
 *
 * DB handler library: PDO support.
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
 * PDO Statement object class.
 *
 * @package    phpMySandBox
 * @subpackage Libraries\Core
 */
class MySBDB_pdoobj {

    /**
     * @var    integer          current table offset
     */
    public $offset = 0;

    /**
     * @var    string           SQL query
     */
    public $query = '';

    /**
     * @var    PDOStatement     PDO statement of query
     */
    public $statement = null;

    /**
     * @var    array            Array result of query
     */
    public $result = null;

    /**
     * @var    integer          Size of result array (rowCount)
     */
    public $count = 0;


    /**
     * DB constructor.
     * @param   PDOStatement    $statement          PDO statement of query
     * @param   string          $query              SQL query
     */
    public function __construct($statement, $query) {
        $this->statement = $statement;
        $this->query = $query;
        $str = explode(' ',$this->query);
        if( $str[0]=='select' or $str[0]=='SELECT' ) {
            if( $this->result==null ) 
                $this->result = $this->statement->fetchAll();
            $this->count = count($this->result);
        } else $this->count = $this->statement->rowCount();
    }

    /**
     * Fetch an array for the current offset for the result
     * @return  array                               array result
     */
    public function fetch_array() {
        if( $this->result==null ) 
            $this->result = $this->statement->fetchAll();
        if( count($this->result)<=$this->offset) 
            return false;
        return $this->result[$this->offset++];
    }

    /**
     * Return the count of result rows
     * @return  integer                             result rows count
     */
    public function rowCount() {
        return $this->count;
    }

}


/**
 * PDO  handler class.
 *
 * @package    phpMySandBox
 * @subpackage Libraries\Core
 */
class MySBDBLayer_pdo implements MySBIDBLayer {

    /**
     * @var    string           keyname for the driver used
     */
    public $driver = '';

    /**
     * @var    array            array containing errors
     */
    public $error = array();


    /**
     * DB constructor.
     * @param   string      $driver         PDO driver (declared in config.php as pdo:driver)
     */
    public function __construct($driver) {
        if( !class_exists('PDO') )
            die( "PDO class not found. Checks your PHP." );
        $this->driver = $driver;
    }

    /**
     * Connection to the base through the layer
     * @param   string      $dbhost         DB hostname or IP
     * @param   string      $dbuser         DB username
     * @param   string      $dbpasswd       DB user password
     * @param   string      $dbname         DB base name
     */
    public function connect($dbhost, $dbuser, $dbpasswd, $dbname ) {
        $dsn = $this->driver.':dbname='.$dbname.';host='.$dbhost;
        try {
            $this->db = new PDO($dsn,$dbuser,$dbpasswd);
        } catch (PDOException $e) {
            echo "Failed to get DB handle: " . $e->getMessage() . "\n";
            exit;
        }
    }

    /**
     * Close DB connection 
     */
    public function close() {
        $this->db = null;
    }

    /**
     * Send a SQL query
     * @param   string          $sql_query      SQL query
     * @return  MySBDB_pdoobj                   result object
     */
    public function query($sql_query) {
        global $app;
        if( isset($this->query) and $this->query!=null )
            $this->query->closeCursor();
        $this->error = array();
        $this->query = $this->db->prepare($sql_query);
        if( !$this->query ) {
            $this->error = $this->db->errorInfo();
            $app->LOG('PDO query prepare() error:'.$sql_query."\n".$this->error[2]);
            return false;
        }
        if(!$this->query->execute()) {
            $this->error = $this->query->errorInfo();
            $app->LOG('PDO query execute() error:'.$sql_query."\n".$this->error[2]);
            return false;
        }
        return new MySBDB_pdoobj($this->query,$sql_query);
    }

    /**
     * Fetch the SQL query result
     * @param   array       $query_result       query result object
     * @return  array                           row as results array 
     */
    public function fetch_array($query_result) {
        if( !$query_result )    
            return null;
        return $query_result->fetch_array();
    }

    /**
     * Return the result array size
     * @param   array       $query_result       query result object
     * @return  integer                         result row count
     */
    public function num_rows($query_result) {
        if( $query_result==null ) return 0;
        return $query_result->rowCount();
    }

    /**
     * Move internal pointer
     * @param   array       $query_result       query result object
     * @param   int         $row_number         offset for result pointer
     */
    public function data_seek($query_result, $row_number) {
        $query_result->offset = $row_number;
    }

    /**
     * Get the error
     * @return  string                          error string from the layer
     */
    public function error() {
        return $this->error[2];
    }

}

?>

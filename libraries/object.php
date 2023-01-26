<?php
/**
 * phpMySandBox - Simple Database Framework in PHP
 *
 * DB Object support library.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License', or
 * ('at your option) any later version.
 * (Roman Travé <roman.trave@gmail.com>, 2012)
 *
 * @package    phpMySandBox
 * @subpackage Libraries\Objects
 * @license    http://opensource.org/licenses/gpl-license.php  GNU Public License
 * @author     Roman Travé <roman.trave@gmail.com>
 */

// No direct access.
defined('_MySBEXEC') or die;


/**
 * Object value class
 *
 * @package    phpMySandBox
 * @subpackage Libraries\Objects
 */

#[\AllowDynamicProperties]

abstract class MySBObject {

    /**
     * Unique ID
     * @var    integer
     */
    public $id = null;


    /**
     * Oject constructor.
     * @param   array   $data_object        Array of values to set
     * @param   string  $prefix             Prefix of instance properties
     */
    protected function __construct($data_object=array(),$prefix='') {
        foreach($data_object as $index => $value) {
            if(!is_int($index)) { //we dont use int indexes from fetch_array
                $pre_index = $prefix.$index;
                $this->$pre_index = $value;
            }
        }
    }

    /**
     * Oject update (instance and DB).
     * @param   string  $table              Symbolic name of table to update
     * @param   array   $data_object        Array of values to set
     * @param   int     $id                 Overwrite the ID of object
     * @param   string  $prefix             Prefix of instance properties
     */
    public function __update($table,$data_object=array(),$id=null,$prefix='') {
        global $app;
        if(count($data_object)==0) return;
        if($id==null) $id = $this->id;
        $coma_flag = 0;
        $sql_updobj = "UPDATE ".MySB_DBPREFIX.$table." SET ";
        foreach($data_object as $index => $value) {
            if($coma_flag==0) $coma_flag = 1;
            else $sql_updobj .= ", ";
            $sql_updobj .= $index."='".MySBUtil::str2db($value)."'";
            $pre_index = $prefix.$index;
            $this->$pre_index = $value;
        }
        $sql_updobj .= " WHERE id=".$id;
        MySBDB::query($sql_updobj,
            "MySBObject::__update()" );
    }

}

?>

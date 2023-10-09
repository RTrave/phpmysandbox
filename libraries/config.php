<?php
/**
 * phpMySandBox - Simple Database Framework in PHP
 *
 * Application handling library class and factory.
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
 * Configuration value class.
 *
 * @package    phpMySandBox
 * @subpackage Libraries\Core
 */
class MySBConfig extends MySBValue {

    /**
     * Value
     * @var    string
     */
    public $value = null;

    /**
     * Comments
     * @var    string
     */
    public $comments = null;


    /**
     * Constructor init config value.
     * @param   string $keyname         Key of the config parameter
     * @param   string $grp             Group of the config parameter
     * @param   array  $data_config     Array of config parameters
     */
    public function __construct($keyname, $grp, $data_config=array()) {
        global $app;
        if($keyname!='') {
            $req_config = MySBDB::query("SELECT * from ".MySB_DBPREFIX."config".
                " WHERE (keyname='".$keyname."' AND grp='".$grp."')",
                "MySBConfig::__construct($keyname,$grp)");
            $data_config = MySBDB::fetch_array($req_config);
            if($data_config['keyname']=='')
                $app->ERR("MySBConfig::__construct($keyname, $grp): config ".$keyname.'/'.$grp.' not found!');
        }
        parent::__construct((array) ($data_config));
    }

    /**
     * Write config value in DB
     * @param   string  $value          Value set.
     */
    public function setValue($value) {
        global $app;
        if($value==$this->value) return;
        parent::__update('config', array('value'=>$value) );
        $app->LOG("MySBConfig::setValue($value): '".$this->keyname."' = '".$value."'",'');
    }

    /**
     * Write config value in DB
     * @return  *           Value of config
     */
    public function getValue() {
        return $this->value;
    }

}


/**
 * Configuration class helper.
 *
 * @package    phpMySandBox
 * @subpackage Libraries\Core
 */
class MySBConfigHelper {

    /**
     * create config values in array to return
     * @param   string  $keyname        Key of the config
     * @param   string  $grp            Group of the config
     * @param   string  $comments       Explicit comments
     * @param   int     $value          Initial value
     * @param   int     $type           Value type (MySBValue)
     * @return  MySBConfig              Return a config object
     */
    public static function create($keyname,$value,$type,$comments,$grp='') {
        global $app;
        if( isset($app->cache_configs) and isset($app->cache_configs[$keyname.'_'.$grp]) )
            return $app->cache_configs[$keyname.'_'.$grp];
        $new_id = MySBDB::lastID('config')+1;
        MySBDB::query("INSERT INTO ".MySB_DBPREFIX."config (".
            "id,keyname,value,type,comments,grp) VALUES (".
            "$new_id,'$keyname','$value','$type','$comments','$grp' )",
            "MySBConfigHelper::create($keyname,$grp)",
            false);
        $app->LOG("MySBConfigHelper::create(): ".
            "config['".$keyname."'] ('".$type."' from '".$grp."') = '".$value."'",'');
        $new_config = new MySBConfig($keyname,$grp);
        if(isset($app->cache_configs))
            $app->cache_configs[$keyname.'_'.$grp] = $new_config;
        return $new_config;
    }

    /**
     * delete config values in array and DB
     * @param   string  $keyname        Key of the config
     * @param   string  $grp            Group of the config
     */
    public static function delete($keyname,$grp='') {
        global $app;
        $config = MySBConfigHelper::get($keyname,$grp);
        if(!$config) return;
        $config->delValueOptions();
        MySBDB::query("DELETE FROM ".MySB_DBPREFIX."config ".
            "WHERE keyname='$keyname' AND grp='$grp'",
            "MySBConfigHelper::delete($keyname,$grp)");
        $app->LOG("MySBConfigHelper::delete(): config['".$keyname."'] (from '".$grp."') deleted",'');
        unset ($app->cache_configs[$keyname.'_'.$grp]);
    }

    /**
     * Load config values in array to return
     * @return  array
     */
    public static function load() {
        global $app;
        if(isset($app->cache_configs)) return $app->cache_configs;
        $app->cache_configs = array();
        $req_configs = MySBDB::query("SELECT * FROM ".MySB_DBPREFIX."config ".
                "ORDER BY id",
                "MySBConfigHelper::load()",
                true, '', true );
        while($data_config = MySBDB::fetch_array($req_configs)) {
            $config = new MySBConfig('', '', $data_config);
            $app->cache_configs[$data_config['keyname'].'_'.$data_config['grp']] = $config;
        }
        return $app->cache_configs;
    }

    /**
     * Load config values from specific group in array to return
     * @param   $grp           MySBGroup Group of config
     * @return  array
     */
    public static function loadByGrp($grp) {
        $configs = MySBConfigHelper::load();
        $config_grp = array();
        foreach($configs as $config) {
            if($config->grp==$grp)
                $config_grp[] = $config;
        }
        return $config_grp;
    }

    /**
     * Get config object
     * @param   string  $keyname        Key of the config
     * @param   string  $grp            Group of the config
     * @return  MySBConfig
     */
    public static function get($keyname,$grp='') {
        global $app;
        $configs = MySBConfigHelper::load();
        if( isset($configs[$keyname.'_'.$grp]) )
            return $configs[$keyname.'_'.$grp];
        return null;
    }

    /**
     * Get config value
     * @param   string  $keyname        Key of the config
     * @param   string  $grp            Group of the config
     * @return  string
     */
    public static function Value($keyname,$grp='') {
        global $app;
        $oconf = MySBConfigHelper::get($keyname,$grp);
        if($oconf==null) return '';
        return $oconf->getValue();
    }

}

?>

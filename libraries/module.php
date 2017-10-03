<?php
/**
 * phpMySandBox - Simple Database Framework in PHP
 *
 * Module handling library class and factory.
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

/***************************************************************************
 *
 *   phpMySandBox - TRoman<abadcafe@free.fr> - 2012
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License', or
 *   ('at your option) any later version.
 *
***************************************************************************/

// No direct access.
defined('_MySBEXEC') or die;

/**
 * Module class
 *
 * @package    phpMySandBox
 * @subpackage Libraries\Core
 */
class MySBModule {

	/**
	 * Module name
	 * @var    string
	 */
	public $name = null;

	/**
	 * Module directory path
	 * @var    string
	 */
	public $dirpath = null;  //TODO


    /**
     * Module constructor.
     * @param   string   $name     Name of the module
     */
    public function __construct($name) {
        $this->name = $name;
        $this->dirpath = MySB_ROOTPATH.'/modules/'.
        $initfile = MySB_ROOTPATH.'/modules/'.$this->name.'/__init.php';
        include_once($initfile);
        $class_name = 'MySBModule_'.$this->name;
        if( class_exists($class_name) )
            $this->module_helper = new $class_name();
    }

    /**
     * Module init
     */
    public function init() {
        global $app;
        $this->module_helper->create();
        MySBConfigHelper::create('mod_'.$this->name.'_enabled',
            0,MYSB_VALUE_TYPE_INT,
            MySBUtil::str2db("Module '".$this->name."' is enabled"),
            'modules');
        MySBConfigHelper::create('mod_'.$this->name.'_tables',
            1,MYSB_VALUE_TYPE_INT,
            MySBUtil::str2db("Module '".$this->name."' table exists"),
            'modules');
        $includefile = MySB_ROOTPATH.'/modules/'.$this->name.'/framework.php';
        if(file_exists($includefile))
            include_once($includefile);
        $this->upgrade();
        $app->locales->loadINIFile($this->name,$this->name);
    }

    /**
     * Module re-init
     * @param
     */
    public function reinit() {
        global $app;
        $mod_conf = MySBConfigHelper::get('mod_'.$this->name.'_enabled','modules');
        if($mod_conf==null) return;
        $mod_conf->setValue(0);
        $includefile = MySB_ROOTPATH.'/modules/'.$this->name.'/framework.php';
        if(file_exists($includefile))
            include_once($includefile);
        $this->upgrade();
        $app->locales->loadINIFile($this->name,$this->name);
    }

    /**
     * Module uninit
     * @param
     */
    public function uninit() {
        $this->module_helper->uninit();
        $mod_conf = MySBConfigHelper::get('mod_'.$this->name.'_enabled','modules');
        if($mod_conf==null) return;
        $mod_conf->setValue(-1);
    }

    /**
     * Module deletion
     * @param
     */
    public function delete() {
        $this->module_helper->delete();
        MySBConfigHelper::delete('mod_'.$this->name.'_enabled','modules');
        MySBConfigHelper::delete('mod_'.$this->name.'_tables','modules');
    }

    /**
     * Module upgrade
     * @param
     */
    public function upgrade() {
        global $app;
        $mod_conf = MySBConfigHelper::get('mod_'.$this->name.'_enabled','modules');
        if($mod_conf==null) return;
        $run_version = $mod_conf->getValue();
        if($run_version==-1 or $run_version==$this->module_helper->version) return;
        for($i=$run_version+1;$i<=$this->module_helper->version;$i++) {
            $meth = 'init'.$i;
            $this->module_helper->$meth();
        }
        $mod_conf->setValue($this->module_helper->version);
    }

    /**
     * Module is loaded ?
     * @return  boolean         true if loaded.
     */
    public function isloaded() {
        global $app;
        $mod_conf = MySBConfigHelper::get('mod_'.$this->name.'_enabled','modules');
        if($mod_conf==null) return false;
        $run_version = $mod_conf->getValue();
        if($run_version>=0) return true;
        return false;
    }

}

/**
 * ModuleHelper class
 *
 * @package    phpMySandBox
 * @subpackage Libraries\Core
*/
class MySBModuleHelper {

    /**
     * Load  modules in array
     * @return  array
     */
    public static function load() {
        global $app;
        if(isset($app->cache_modules)) return $app->cache_modules;
        $app->cache_modules = array();
        if(is_dir(MySB_ROOTPATH.'/modules'))
            $mod_dirs = scandir(MySB_ROOTPATH.'/modules');
        else $app->ERR('MySBModuleHelper::load(): directory modules not found or readable.');
        foreach($mod_dirs as $value) {
            if($value === '.' || $value === '..' || $value === 'index.html') {continue;}
            if(file_exists('modules/'.$value.'/__init.php')) {
                $app->cache_modules[$value] = new MySBModule($value);
            }
        }
        return $app->cache_modules;
    }

    /**
     * Load  modules in array
     * @param   string      $name
     * @return  array
     */
    public static function getByName($name) {
        global $app;
        $modules = MySBModuleHelper::load();
        return $modules[$name];
    }

}

?>

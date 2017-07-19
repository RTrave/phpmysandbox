<?php
/**
 * phpMySandBox - Simple Database Framework in PHP
 *
 * Plugin support library.
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
 * MySBPlugin class
 *
 * @package    phpMySandBox
 * @subpackage Libraries\Plugins
 */
class MySBPlugin extends MySBObject {

    /**
     * @var    string       plugin name
     */
    public $name = null;

    /**
     * @var    string       plugin type
     */
    public $type = null;

    /**
     * @var    array        array of string values
     */
    public $value = array();

    /**
     * @var    array        array of integer values
     */
    public $ivalue = array();

    /**
     * @var    integer      plugin priority
     */
    public $priority = null;

    /**
     * @var    string       plugin role
     */
    public $role = null;

    /**
     * @var    string       module handling this plugin
     */
    public $module = null;


    /**
     * Constructor.
     * @param   array   $data_plugin        paremeters of plugin
     */
    public function __construct($data_plugin) {
        global $app;
        parent::__construct((array) ($data_plugin));
    }

    /**
     * Update plugin parameters (instance and DB)
     * @param   array   $data_plugin        paremeters of plugin
     */
    public function update($data_plugin=array()) {
        global $app;
        parent::__update('plugins', (array) ($data_plugin));
    }

    /**
     * Post creation callback.
     */
    public function post_create() {
    }

    /**
     * Pre deletion callback.
     */
    public function pre_delete() {
    }

    /**
     * Html form (plugin edition)
     * @return  string          HTML entity output
     */
    public function html_valueform() {
        return '';
    }

    /**
     * Html form process (plugin edition)
     */
    public function html_valueprocess() {
    }

}

/**
 * PluginHelper class
 *
 * @package    phpMySandBox
 * @subpackage Libraries\Plugins
 */
class MySBPluginHelper {

    /**
     * Create plugin object to return
     * @param   string  $name           plugin name
     * @param   string  $type           plugin type
     * @param   array   $value          plugin string values
     * @param   array   $ivalue         plugin interger values
     * @param   integer $priority       plugin priority
     * @param   string  $role           plugin role
     * @param   string  $module         module handling plugin
     * @param   string  $childclass     plugin sub class referenced (obsolete)
     */
    public static function create($name,$type,$value=array(),$ivalue=array(),$priority,$role,$module,$childclass='') {
        global $app;

        if( !isset($app->cache_plugins) or count($app->cache_plugins)==0)
            MySBPluginHelper::load();
            if(isset($app->cache_plugins[$module.'_'.$name])) {
                $app->LOG("MySBPluginHelper::create(): ".
                          "tplugins['".$name."'] ('".$type."' from '".$module."') exists: deletion",'');
                MySBPluginHelper::delete($name,$module);
                //unset( $app->cache_plugins[$module.'_'.$name]);
                //return;
            }
        $className = 'MySBPlugin' . $type.$childclass;
        if(!class_exists($className))
            $app->pushAlert($className.' not found!');
        $new_id = MySBDB::lastID('plugins')+1;
        MySBDB::query("INSERT INTO ".MySB_DBPREFIX."plugins (".
            "id,name,type,".
            "value0,value1,value2,value3,".
            "ivalue0,ivalue1,ivalue2,ivalue3,".
            "priority,role,module,childclass ) VALUES (".
            "$new_id,'$name', '$type', ".
            "'$value[0]', '$value[1]', '$value[2]', '$value[3]', ".
            "$ivalue[0], $ivalue[1], $ivalue[2], $ivalue[3], ".
            "$priority, '$role', '$module', '$childclass')",
            "MySBPluginHelper::create($name,$module)",
            false);
        $app->LOG("MySBPluginHelper::create(): tplugins['".$name."'] ('".$type."' from '".$module."') added",'');
        if (!class_exists($className))
            if(isset($app))
                $app->LOG('MySBPluginHelper::create(): class "'.$className.'" not found');
        $req_plugin = MySBDB::query("SELECT * FROM ".MySB_DBPREFIX.'plugins '.
            'WHERE id='.$new_id,
            "MySBPluginHelper::create($name,$module)");
        if(MySBDB::num_rows($req_plugin)==0) return;
        $data_plugin = MySBDB::fetch_array($req_plugin);
        $app->cache_plugins[$module.'_'.$name] = new $className((array) ($data_plugin));
        $app->cache_plugins[$module.'_'.$name]->post_create();
    }

    /**
     * Delete plugin
     * @param   string  $name           plugin name
     * @param   string  $module         module handling plugin
     */
    public static function delete($name,$module) {
        global $app;
        $plugin_obj = MySBPluginHelper::get($name,$module);
        MySBDB::query( "DELETE FROM ".MySB_DBPREFIX."plugins ".
            "WHERE name='$name' AND module='$module'",
            "MySBPluginHelper::delete($name,$module)",
            false);
        $app->LOG("MySBPluginHelper::delete(): tplugins['".$name."'] (from '".$module."') deleted",'');
        if( $plugin_obj!=null ) {
            $plugin_obj->pre_delete();
            unset( $app->cache_plugins[$plugin_obj->module.'_'.$plugin_obj->name] );
        }
    }

    /**
     * Load  plugins in array (cache)
     * @return  array               array of MySBPlugin[$plugtype]
     */
    public static function load() {
        global $app;
        if(isset($app->cache_plugins))
            return $app->cache_plugins;
        $app->cache_plugins = array();
        $req_plugins = MySBDB::query( "SELECT * FROM ".MySB_DBPREFIX."plugins ".
            "ORDER BY type,priority DESC",
            "MySBPluginHelper::load()" );
        while($plugin = MySBDB::fetch_array($req_plugins)) {
            $className = 'MySBPlugin' . $plugin['type']. $plugin['childclass'];
            if (!class_exists($className)) {
                if(isset($app))
                    $app->LOG('MySBPluginHelper::load(): class "'.$className.'" not found');
            } else
                $app->cache_plugins[$plugin['module'].'_'.$plugin['name']] = new $className((array) ($plugin));
        }
        return $app->cache_plugins;
    }

    /**
     * Load  plugins in array by type
     * @param   string  $type       plugin type
     * @return  array               array of MySBPlugin[$plugtype]
     */
    public static function loadByType($type) {
        global $app;
        $plugins = MySBPluginHelper::load();
        $plugins_bytype = array();
        foreach($plugins as $plugin) {
            if($plugin->type==$type)
                $plugins_bytype[] = $plugin;
        }
        return $plugins_bytype;
    }

    /**
     * Load  plugins in array by module
     * @param   string  $module     module handling plugin
     * @return  array               array of MySBPlugin[$plugtype]
     */
    public static function loadByModule($module) {
        global $app;
        $plugins = MySBPluginHelper::load();
        $plugins_bymodule = array();
        foreach($plugins as $plugin) {
            if($plugin->module==$module)
                $plugins_bymodule[] = $plugin;
        }
        return $plugins_bymodule;
    }

    /**
     * Get a plugins with his name and module (or type)
     * @param   string  $name           plugin name
     * @param   string  $module         module handling plugin (if $type='')
     * @param   string  $type           plugin type
     * @return  MySBPlugin              plugin found (class MySBPlugin[$plugtype])
     */
    public static function get($name,$module,$type='') {
        global $app;
        $plugins = MySBPluginHelper::load();
        if($type='')
            return $plugins[$module.'_'.$name];
        else {
            foreach($plugins as $plugin) {
                if($plugin->name==$name and $plugin->type==$type)
                    return $plugin;
            }
        }
        return null;
    }

    /**
     * Get a plugins with his ID
     * @param   integer $id             plugin ID
     * @return  MySBPlugin              plugin found (class MySBPlugin[$plugtype])
     */
    public static function getByID($id) {
        global $app;
        $plugins = MySBPluginHelper::load();
        foreach($plugins as $plugin) {
            if($plugin->id==$id)
                return $plugin;
        }
        return null;
    }

}

?>

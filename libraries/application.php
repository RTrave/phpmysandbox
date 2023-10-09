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
 * Application class.
 *
 * @package    phpMySandBox
 * @subpackage Libraries\Core
 */

#[\AllowDynamicProperties]

class MySBApplication extends MySBRender {

    /**
     * @var     MySBIDBLayer     MySBDB object (db.php)
     */
    public $dblayer = null;

    /**
     * @var     MySBDBCache       MySBCache object (cache.php)
     */
    public $dbcache = null;

    /**
     * @var     array       SQL Queries cache (cache.php)
     */
    public $sql_queriesall = null;

    /**
     * @var     array       Mask queries to cache (cache.php)
     */
    public $querymask = null;

    /**
     * @var     MySBUser        Réference to the authentified user
     */
    public $auth_user = null;

    /**
     * @var         integer           Skip __init.php stages merged.
     */
    public $init_skip = 0;

    /**
     * @var         array           PHP Session values
     */
    public $SESSION = NULL;


    /**
     * App constructor.
     */
    public function __construct() {
        parent::__construct();
        session_start();
        $this->dblayer = MySBDB::connect();
        $this->dbcache = new MySBDBCache($this);
    }

    /**
     * Reset the PHP user session.
     */
    public function resetSession() {
        global $_COOKIE;
        session_unset();
        session_destroy();
        session_write_close();
        unset($_COOKIE['PHPSESSID']);
        setcookie(session_name(),'',0,'/');
        unset($this->auth_user);
        $this->auth_user = null;
    }

    /**
     * Set and load locales INI files.
     * @param   string   $locale    Language used.
     * @param   string   $timezone  TimeZone used.
     */
    public function setlocale($locale=null,$timezone=null) {
        if($locale==null) {
            include( MySB_ROOTPATH.'/config.php' );
            $locale = $mysb_locale;
            $timezone = $mysb_timezone;
        }
        $this->locales = new MySBLocales($locale,$timezone);
        $this->locales->loadINIFile('core');
        $modules = MySBModuleHelper::load();
        foreach($modules as $module) {
            $mod_conf = MySBConfigHelper::get('mod_'.$module->name.'_enabled','modules');
            if($mod_conf!=null and $mod_conf->getValue()>=1) 
                $this->locales->loadINIFile($module->name,$module->name);
        }
    }

    /**
     * Upgrade core system.
     */
    public function upgrade() {
        $core_conf = MySBConfigHelper::get('core_version','modules');
        $initfile = MySB_ROOTPATH.'/__init.php';
        if(file_exists($initfile)) {
            include_once($initfile);
            $core = new MySBCore;
            $version = $core_conf->getValue();
            if($version==$core->version) return;
            for($i=$version+1;$i<=$core->version;$i++) {
                $meth = 'init'.$i;
                $core->$meth();
            }
            $core_conf->setValue($core->version);
        }
    }

    /**
     * Upgrade modules.
     */
    public function upgrade_modules() {
        $modules = MySBModuleHelper::load();
        foreach($modules as $module) 
            if($module->isloaded()) {
                $includefile = MySB_ROOTPATH.'/modules/'.$module->name.'/framework.php';
                if(file_exists($includefile))
                    include_once($includefile);
                $module->upgrade();
            }
    }

    /**
     * Authentication process and auth_user setting.
     */
    public function authenticate() {
        $this->auth_user = MySBUserHelper::checkAuth();
    }

    /**
     * Close DB connection and write session
     */
    public function close() {
        MySBDB::close();
        session_write_close();
    }

    /**
     * Check script access conditions: password, attempts, ..
     */
    public function scriptCheck() {
        global $app, $_GET;
        $scriptconf = MySBConfigHelper::get('script_passwd');
        $scriptattempts = MySBConfigHelper::get('script_attempts','scripts');
        $passwd = $scriptconf->getValue();
        $attempts = $scriptattempts->getValue();
        if( !isset($_GET['spw']) ) {
            $app->close();
            die("ERROR: Password script access needed (spw=)\n");
        }
        if( $passwd=='' ) {
            $app->close();
            die("ERROR: Init a password for script access\n");
        }
        if( $passwd!=$_GET['spw'] )
            if( $attempts<6 ) {
                $scriptattempts->setValue($attempts+1);
                $app->close();
                die("ERROR: Bad pass for script access\n");
            } else {
                $scriptconf->setValue('');
                $app->close();
                die("ERROR: Bad pass for script access (password reset!!!)\n");
            }
        $scriptattempts->setValue(0);
    }

}


?>

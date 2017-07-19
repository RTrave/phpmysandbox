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
class MySBApplication {

    /**
     * @var     MySBDBLayer     MySBDB object (db.php)
     */
    public $dblayer = null;

    /**
     * @var     MySBCache       MySBCache object (cache.php)
     */
    public $dbcache = null;

    /**
     * @var     MySBDisplay     MySBDisplay object (display.php)
     */
    public $display = null;

    /**
     * @var     MySBUser        Réference to the authentified user
     */
    public $auth_user = null;

    /**
     * @var         array           Optional queries logger
     */
    public $sql_queries = array();
    
    /**
     * @var         integer           Optional queries logger
     */
    public $sql_queriesnb = 0;

    /**
     * @var         integer           Skip __init.php stages merged.
     */
    public $init_skip = 0;


    /**
     * App constructor.
     */
    public function __construct() {
        session_start();
        $this->dblayer = MySBDB::connect();
        $this->dbcache = new MySBDBCache($this);
        $this->display = new MySBDisplay();
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
     * Process forms and performs page preparation.
     */
    public function process() {
        if( !empty($_GET['tpl']) ) {
            _incT($_GET['tpl'].'_process',$_GET['mod'],false);
        } elseif( !empty($_GET['inc']) ) {
            _incI($_GET['inc'].'_process',$_GET['mod'],false);
        } else {
            $pluginsFrontPage = MySBPluginHelper::loadByType('FrontPage');
            foreach($pluginsFrontPage as $plugin) 
                $plugin->processForms();
        }
        $this->display->header();
    }

    /**
     * Run templates and display.
     */
    public function display() {
        $this->display->bodyStart();
        if( !empty($_GET['tpl']) ) {
            _incT($_GET['tpl'],$_GET['mod']);
        } elseif( !empty($_GET['inc']) ) {
            _incI($_GET['inc'],$_GET['mod']);
        } else {
            $pluginsFrontPage = MySBPluginHelper::loadByType('FrontPage');
            foreach($pluginsFrontPage as $plugin) 
                $plugin->displayBody();
        }
        $this->display->bodyStop();
    }


    /**
     * Alert message writing, and die.
     * @param   string      $message        ERROR message to display.
     * @param   integer     $refresh_time   Delay before page refresh.
     * @param   bool        $with_menu      false for alert without top menu.
     */
    public function displayStopAlert($message,$refresh_time=0,$with_menu=true) {
        if( $this->display->hidelay )
            $this->pushMessage($message);
        $this->display->header($refresh_time);
        $this->display->bodyStart($with_menu);
        echo '<div id="mysbAlerts"><div style="display: table-cell; height: 100%; vertical-align: middle;"><p><img src="images/icons/dialog-error.png"></div><div style="display: table-cell; height: 100%; vertical-align: middle;">';
        echo $message;
        echo '</div><script type="text/javascript">offSpin();</script>';
        echo '</p></div>';
        $this->display->bodyStop();
        $this->close();
        die;
    }

    /**
     * Clean HTML quotes
     * @param   string      $message        HTML tip message to clean.
     * @return  string                      HTML cleaned.
     */
    private function MsgCleaner($message) {
        $str = str_replace( '"', '\'', $message );
        return $str;
    }

    /**
     * Push message to current user.
     * @param   string      $message        HTML tip message to show.
     */
    public function pushMessage($message) {
        $this->display->Messages .= '<div style="display: table-row; width: 100%;">
        <div style="display: table-cell; height: 100%; vertical-align: middle;"><img src="images/icons/dialog-warning.png"></div><div style="display: table-cell; height: 100%; vertical-align: middle;"><p>'.$this->MsgCleaner($message).'</p></div>
        </div>';
    }

    /**
     * Push alert to current user (and die after display).
     * @param   string      $message        HTML alert to show.
     */
    public function pushAlert($message) {
        $this->display->Alerts .= '<div style="display: table-row; width: 100%;">
        <div style="display: table-cell; height: 100%; vertical-align: middle;"><img src="images/icons/dialog-error.png"></div><div style="display: table-cell; height: 100%; vertical-align: middle;"><p>'.$this->MsgCleaner($message).'</p></div>
        </div>';
    }

    /**
     * Close DB connection and write session
     */
    public function close() {
        MySBDB::close();
        session_write_close();
    }

    /**
     * LOG facility
     * @param   string  $message    Message to log
     * @param   string  $dest       File destination
     */
    public function LOG($message,$dest=null) {
        $logfile = MySB_ROOTPATH."/log/mysb.txt";
        if($dest) $logfile = MySB_ROOTPATH.'/log/'.$dest.'.txt';
        $today = getdate();
        $today_str = 
            $today['mday'].'-'.$today['mon'].'-'.$today['year'].' '.
            $today['hours'].':'.$today['minutes'].':'.$today['seconds'];
        $fh = fopen($logfile, 'a') 
            or die("can't open log file: check permissions on log/");
        if( !empty($this->auth_user) ) $loguser = $this->auth_user->login;
        else $loguser='anonymous';
        $log_msg = "---  ".$today_str.' - '.$loguser.'('.$_SERVER['REMOTE_ADDR'].') - '.$_SERVER['REQUEST_URI'].
            "\n".$message;
        $log_msg = str_replace( "\n", "\n   ", $log_msg );
        fwrite($fh, "\n".$log_msg."\n");
        fclose($fh);
        $this->display->logPush($log_msg);
    }

    /**
     * ERROR facility (and die)
     * @param   string  $message    Message to log
     * @param   string  $dest       File destination
     */
    public function ERR($message,$dest=null) {
        $errfile = MySB_ROOTPATH."/log/mysb.txt";
        if($dest) $errfile = MySB_ROOTPATH.'/log/'.$dest.'.txt';
        $today = getdate();
        $today_str = 
            $today['mday'].'-'.$today['mon'].'-'.$today['year'].' '.
            $today['hours'].':'.$today['minutes'].':'.$today['seconds'];
        $fh = fopen($errfile, 'a') 
            or die("can't open log file: check permissions on log/");
        if(isset($this->auth_user)) $loguser = $this->auth_user->login;
        else $loguser='anonymous';
        $error_msg = 
            $today_str.' - '.$loguser.'('.$_SERVER['REMOTE_ADDR'].') - '.$_SERVER['REQUEST_URI'].
            "\n".$message;
        $error_msg = str_replace( "\n", "\n   ", $error_msg );
        fwrite($fh, "\nERR: ".$error_msg."\n");
        fclose($fh);
        echo '
<!--  !!!ERROR!!!  --><br>
'.MySBUtil::str2html($error_msg).'<br>
<!--  !!!ERROR!!!  --><br>
';
        $this->close();
        die;
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

<?php
/**
 * phpMySandBox - Simple Database Framework in PHP
 *
 * MVC handling library class and factory.
 * 
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License', or
 * ('at your option) any later version. 
 * (Roman Travé <roman.trave@gmail.com>, 2017)
 *
 * @package    phpMySandBox
 * @subpackage Libraries\Core
 * @license    http://opensource.org/licenses/gpl-license.php  GNU Public License
 * @author     Roman Travé <roman.trave@gmail.com>
 */


// No direct access.
defined('_MySBEXEC') or die;

/**
 * Loging facilities class.
 *
 * @package    phpMySandBox
 * @subpackage Libraries\Core
 */
class MySBLog {

    /**
     * @var         array           Optional queries logger
     */
    public $sql_queries = array();
    
    /**
     * @var         integer           Optional queries logger
     */
    public $sql_queriesnb = 0;

    /**
     * @var         array           Optional LOG entry
     */
    public $log_entries = array();


    /**
     * Logging constructor.
     */
    public function __construct() {
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
        $this->Messages .= '
    <div style="display: table-row; width: 100%;">
        <div style="padding-left: 15px;"><img src="images/icons/dialog-warning.png" alt="Warning"></div>
        <div style="padding-right: 15px;"><p>'.$this->MsgCleaner($message).'</p></div>
    </div>';
        $this->display->Messages = $this->Messages;
    }

    /**
     * Push alert to current user (and die after display).
     * @param   string      $message        HTML alert to show.
     */
    public function pushAlert($message) {
        $this->Alerts .= '
    <div style="display: table-row; width: 100%;">
        <div style="padding-left: 15px;"><img src="images/icons/dialog-warning.png" alt="Error"></div>
        <div style="padding-right: 15px;"><p>'.$this->MsgCleaner($message).'</p></div>
        </div>';
        $this->display->Alerts = $this->Alerts;
    }

    /**
     * Alert message writing, and die.
     * @param   string      $message        ERROR message to display.
     * @param   integer     $refresh_time   Delay before page refresh.
     * @param   bool        $with_menu      false for alert without top menu.
     */
    public function displayStopAlert($message,$refresh_time=0,$with_menu=true) {
        if( $this->hidelay )
            $this->pushMessage($message);
        $errorcode = '
<div id="mysbAlerts">
    <div><img src="images/icons/dialog-error.png" alt="Error"></div>
    <div style="padding-right: 15px;">'.$message.'</div>
</div>
<script type="text/javascript">offSpin();</script>';
        $this->view_menu($with_menu);
        $this->view_refresh($refresh_time);
        echo $this->view_render($errorcode);
        $this->close();
        die;
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
        $this->log_entries[] = $log_msg;
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
     * SQL log prepare.
     */
    public function getlLogSQL() {
        global $app;
        include MySB_ROOTPATH.'/config.php';
        $output = '';
        if(count($this->log_entries)!=0) {
            $output .= '<p>LOG entries:<br><br>';
            foreach($this->log_entries as $log_entry) 
                $output .= MySBUtil::str2html($log_entry).'<br>';
            $output .= '</p>';
        }
        if($mysb_DEBUGMASK=='') $sql_queries = &$app->sql_queriesall;
        else {
            $queriesmask = 'sql_queries_'.$mysb_DEBUGMASK;
            $sql_queries = &$app->$queriesmask;
        }
        if($mysb_DEBUGMASK=='') $output .= '<p>'.$app->sql_queriesnb." sql access<br><br>\n";
        else $output .= '<p>'.$app->sql_queriesnb." sql access for ".$mysb_DEBUGMASK."<br><br>\n";
        if(count($sql_queries)==0) return;
        foreach($sql_queries as $query) $output .= "$query<br>\n";
        $output .= "</p>\n";
        return $output;
    }
}

?>

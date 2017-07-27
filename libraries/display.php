<?php
/**
 * phpMySandBox - Simple Database Framework in PHP
 *
 * HTML display library class and factory.
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


define('MYSB_DISPLAY_LEVEL_UNSET', -1);
define('MYSB_DISPLAY_LEVEL_SET', 0);
define('MYSB_DISPLAY_LEVEL_HEADER_START', 1);
define('MYSB_DISPLAY_LEVEL_HEADER_STOP', 2);
define('MYSB_DISPLAY_LEVEL_BODY_START', 3);
define('MYSB_DISPLAY_LEVEL_BODY_STOP', 4);


/**
 * HTML display class.
 *
 * @package    phpMySandBox
 * @subpackage Libraries\Core
 */
class MySBDisplay {

    /**
     * @var         bool         true for overlay display.
     */
    public $overlay = false;

    /**
     * @var         bool         true for hidelay display.
     */
    public $hidelay = false;

    /**
     * @var         bool         true for itemlay display.
     */
    public $itemlay = false;

    /**
     * @var         bool         true for blank display.
     */
    public $blanklay = false;

    /**
     * @var         integer         Current application level
     */
    public $level = MYSB_DISPLAY_LEVEL_UNSET;

    /**
     * @var         string          Notifications to user
     */
    public $Messages = '';

    /**
     * @var         string          Alerts to user
     */
    public $Alerts = '';

    /**
     * @var         array           Optional LOG entry
     */
    public $log_entries = array();

    /**
     * @var         array           Optional local custom header lines
     */
    public $custom_headers = array();


    /**
     * Display constructor.
     */
    public function __construct() {
        global $_GET;
        if( (isset($_GET['overlay']) and $_GET['overlay']==1 ) )
            $this->overlay = true;
        if( (isset($_GET['hidelay']) and $_GET['hidelay']==1 ) )
            $this->hidelay = true;
        if( (isset($_GET['itemlay']) and $_GET['itemlay']==1 ) )
            $this->itemlay = true;
        if( (isset($_GET['blanklay']) and $_GET['blanklay']==1 ) )
            $this->blanklay = true;

        $this->level = MYSB_DISPLAY_LEVEL_SET;
    }

    /**
     * register local custom header line
     * @param   string     $header      Header line to append in <head section> (null to reset)
     */
    public function headerADD($header=null) {
        if( $header==null )
            $this->custom_headers = array();
        else
            $this->custom_headers[] = $header;
    }

    /**
     * <head> section stop
     * @param   integer     $refresh_time   delay to load index.php in seconds
     */
    public function header($refresh_time=0) {
        if( $this->level<MYSB_DISPLAY_LEVEL_HEADER_START and
            !$this->overlay and !$this->hidelay and !$this->itemlay and !$this->blanklay ) {
            $this->level<MYSB_DISPLAY_LEVEL_HEADER_START;
            echo '<!DOCTYPE html>
<html>
<head>';
            _incI('head');
            foreach($this->custom_headers as $custom_header) 
                echo '
'.$custom_header;
            if( $refresh_time!=0 ) 
                echo '
    <meta http-equiv="refresh" content="'.$refresh_time.'; URL=index.php">';
            echo '
</head>';
        }
        $this->level = MYSB_DISPLAY_LEVEL_HEADER_STOP;
    }

    /**
     * BODY section (start)
     * @param   bool    $with_menu      false if no top menu
     */
    public function bodyStart($with_menu=true) {
        if( $this->level<MYSB_DISPLAY_LEVEL_BODY_START and
            !$this->overlay and !$this->hidelay and !$this->itemlay and !$this->blanklay ) {
            $this->level<MYSB_DISPLAY_LEVEL_BODY_START;
            echo '
<body>
<noscript><div class="advert" style="background-color: #ffe4e7; border: 4px solid #ffab67; font-size: 24px;">Javascript needed but not activated.</div><br></noscript>
<div id="spinlayer">
</div>
<div id="overlayBg">
</div>
<script>
desactiveOverlay();
</script>

<div id="allshadow">';

            if($with_menu==true) {
                echo '
<div id="mysbTop" class="roundtop">';
                _incI('top');
                echo '
</div>
<div id="mysbMiddle">';
            } else {
                echo '
<div id="mysbMiddle" class="roundtop">';
            }
            echo '
<div class="content"> 

<div id="mysbMessages"></div>';
        }
        $this->level = MYSB_DISPLAY_LEVEL_BODY_START;
    }

    /**
     * BODY section (stop)
     */
    public function bodyStop() {
        global $app;
        $this->msgWrite();
        if( $this->level==MYSB_DISPLAY_LEVEL_BODY_START ) {
            if( !$this->blanklay ) 
                echo $this->layerWrite();
            if( !$this->overlay and !$this->hidelay and !$this->itemlay and !$this->blanklay ) 
                echo '
</div>
</div>';
            if( !$this->overlay and !$this->hidelay and !$this->itemlay and !$this->blanklay ) {
                echo '
<div id="mysbBottom" class="roundbottom">';
                _incI('bottom');
                echo '
</div>
</div>';
                echo $this->logsqlWrite();
                echo '
</body>
</html>';
            } else {
                echo $this->logsqlWrite();
            }
        }
        if( $this->hidelay )
            echo '
<script type="text/javascript">
offSpin();
wrapLayerCalls();
</script>';
        $this->level = MYSB_DISPLAY_LEVEL_BODY_STOP;
    }


    /**
     * Messages and Alerts (die) writing.
     */
    public function msgWrite() {
        global $app;
        $output = '';
        if(!empty($this->Messages)) {
            $message = str_replace("\n","\\\n", $this->Messages);
            $message = str_replace("'","\'", $message);
            echo '
<script>
    var msgwrap = $("div#mysbMessages");
    msgwrap.promise().done(function(){
        msgwrap.html(\''.$message.'\');
        msgwrap.fadeIn(500);
        msgwrap.promise().done(function(){
            hideMessageTip();
        });
    });
</script>';
            $this->Messages = '';
        }
        if(!empty($this->Alerts)) {
            echo '<div id="mysbAlerts">'.$this->Alerts;
            echo '</div>
            <br>
            <div style="text-align: center; width: 100%;"><a href="index.php" class="button" title="'._G('SBGT_topmenu_homeinfos').'">'._G('SBGT_return_home').'</a></div><br>';
            $this->Alerts = '';
            $this->bodyStop();
            $app->close();
            die;
        }
    }

    /**
     * Layers writing.
     */
    private function layerWrite() {
        $output = '';
        if( !$this->overlay and !$this->hidelay and !$this->itemlay )
            $output .= '
<div id="overlay" class="mysb_overlay roundtop">
    <div class="close" >
    <img src="images/window-close32.png"
         alt="'._G('SBGT_overlay_close').'"
         title="'._G('SBGT_overlay_close').'">
    </div>
    <div class="contentWrap" id="contentWrap">...</div>
</div>
<div id="hidelayer">
</div>
<script type="text/javascript">
loadSpin();
</script>';

        if( $this->overlay )
            $output .= '
<script type="text/javascript">
activeOverlay();
resizeOverWin();
$("#overlayBg").promise().done(function(){
$("#overlay").promise().done(function(){
    offSpin();
});
});
</script>';

        if( $this->itemlay )
            $output .= '
<script type="text/javascript">
show_slide(\''.$_GET['iid'].'\');
offSpin();
wrapLayerCalls();
</script>';
        else $output .= '
<script type="text/javascript">
wrapLayerCalls();
</script>';

        return $output;
    }

    /**
     * SQL log writing.
     */
    public function logsqlWrite() {
        global $app;
        $output = '';
        include MySB_ROOTPATH.'/config.php';
        if( !isset($mysb_DEBUG) or !$mysb_DEBUG ) return '';
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

        if( !$this->overlay and !$this->itemlay and !$this->hidelay and !$this->blanklay ) {
            return '
<div id="mysbLogSql">
'.$output.'
</div>';
        } elseif( $this->overlay or $this->itemlay or $this->hidelay ) {
            $clean_output = str_replace("'","\\'",$output);
            $clean_output = str_replace("\n"," ",$clean_output);
            return '
<script type="text/javascript">
var logwrap = $("#mysbLogSql");
logwrap.html(\''.$clean_output.'\');
</script>';
        } elseif( $this->blanklay ) {
            $clean_output = str_replace("'","\\'",$output);
            $clean_output = str_replace("\n"," ",$clean_output);
            //TODO: push log in a file ?
        }
        return '';
    }

    /**
     * SQL log writing.
     * @param   string  $entry      Entry to push on screen
     */
    public function logPush($entry) {
        $this->log_entries[] = $entry;
    }

}

?>

<?php
/**
 * phpMySandBox - Simple Database Framework in PHP
 *
 * Extended DateTime support library.
 * (absolute diffing, html form calls, multiple string outputs)
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
 * MySBDateTime class
 *
 * @package    phpMySandBox
 * @subpackage Libraries\Core
 */
class MySBDateTime extends DateTime {

    /**
     * @var      string          SQL date string
     */
    public $date_string = '';

    /**
     * @var      integer         minimal year in selections
     */
    protected $year_min = 2011;

    /**
     * @var      string          maximal year in selections
     */
    protected $year_max = 2040;

    /**
     * @var      DateFormatter          DateTime new formatter object
     */
    protected $fmt = null;


    /**
     * Constructor
     * @param   string  $cs_string      SQL date string to initialize ('now')
     *                                  ('now' or 'NOW' for actual date)
     */
    public function __construct( $cs_string=null ) {
        global $app;
        if($cs_string==null or $cs_string=='' or $cs_string=='0')
            $cs_string = '';
        if($cs_string=='now' or $cs_string=='NOW')
            $cs_string = date('Y-m-d H:i:s');
        parent::__construct($cs_string);
        if($cs_string!='') $this->date_string = $cs_string;
        else $this->date_string = '0000-00-00 00:00:00';
        $this->fmt = new IntlDateFormatter($app->locales->t_locale,
            IntlDateFormatter::FULL,
            IntlDateFormatter::FULL
            );
        $this->fmt->setPattern("yyyy");
        $refdate = $this->fmt->format(strtotime($this->date_string));
        $this->setYearMaxMin(((int)$refdate)-20,((int)$refdate)+25);
    }

    /**
     * HTML output
     * @return   string                 output
     */
    public function html() {
        global $app;
        $this->fmt->setPattern("EEEE dd MMMM yyyy '&bull;' HH'h'mm");
        $resdate = $this->fmt->format(strtotime($this->date_string));
        //$app->LOG('html: '.$resdate);
        return $resdate;
    }

    /**
     * string format output
     * @return   string                 output
     */
    public function strEMY_s() {
        global $app;
        $this->fmt->setPattern("dd'/'MM'/'yyyy");
        $resdate = $this->fmt->format(strtotime($this->date_string));
        //$app->LOG('strEMY_s: '.$resdate);
        return $resdate;
    }

    /**
     * string format output
     * @return   string                 output
     */
    public function strEBY_l() {
        global $app;
        $this->fmt->setPattern("dd MMM yyyy");
        $resdate = $this->fmt->format(strtotime($this->date_string));
        //$app->LOG('strEBY_l: '.$resdate);
        return $resdate;
    }

    /**
     * string format output
     * @return   string                 output
     */
    public function strEBY_l_whm() {
        global $app;
        $this->fmt->setPattern("dd MMM yyyy - HH'h'mm");
        $resdate = $this->fmt->format(strtotime($this->date_string));
        //$app->LOG('strEBY_l_whm: '.$resdate);
        return $resdate;
    }

    /**
     * string format output
     * @return   string                 output
     */
    public function strAEBY() {
        global $app;
        $this->fmt->setPattern("EEEE dd MMMM yyyy");
        $resdate = $this->fmt->format(strtotime($this->date_string));
        //$app->LOG('strAEBY: '.$resdate);
        return $resdate;
    }

    /**
     * string format output
     * @return   string                 output
     */
    public function strAEBY_l() {
        global $app;
        $this->fmt->setPattern("EEE dd MMM yyyy");
        $resdate = $this->fmt->format(strtotime($this->date_string));
        //$app->LOG('strAEBY_l: '.$resdate);
        return $resdate;
    }

    /**
     * string format output
     * @return   string                 output
     */
    public function strAEBY_l_whm() {
        global $app;
        $this->fmt->setPattern("EEE dd MMM yyyy - HH'h'mm");
        $resdate = $this->fmt->format(strtotime($this->date_string));
        //$app->LOG('strAEBY_l_whm: '.$resdate);
        return $resdate;
    }

    /**
     * string format output
     * @return   string                 output
     */
    public function strmark() {
        global $app;
        $this->fmt->setPattern("yyyyMMdd-HHmmss");
        $resdate = $this->fmt->format(strtotime($this->date_string));
        //$app->LOG('STRMARK: '.$resdate);
        return $resdate;
    }

    /**
     * custom string format output
     * @param   string  $format         date() format
     * @return  string                  output
     */
    public function str_get($format) {
        global $app;
        if( $this->date_string!='' ) {
            $this->fmt->setPattern($format);
            $resdate = $this->fmt->format(strtotime($this->date_string));
            //$app->LOG('STRGET: '.$this->fmt->format(strtotime($this->date_string)));
            return $resdate;
        }
        //else return strftime($format);
        return '0';
    }

    /**
     * set maximal and minimal years
     * @param   string      $year_min   minimal year in selections
     * @param   string      $year_max   maximal year in selections
     */
    public function setYearMaxMin($year_min,$year_max) {
        $this->year_min = $year_min;
        $this->year_max = $year_max;
    }

    /**
     * get the HTML form code corresponding to this date
     * @param   string      $prefix     prefix used for tag's names (default is 'mysbdt_')
     * @param   boolean     $nohours    set hours and minutes (default) or not
     * @return  string                  HTML form output
     */
    public function html_form($prefix='mysbdt_', $nohours=false) {
        if($this->date_string != '0000-00-00 00:00:00') {
            $cday = (int) $this->str_get('dd');
            $cmonth = (int) $this->str_get('MM');
            $cyear = (int) $this->str_get('yyyy');
            $chour = (int) $this->str_get('HH');
            $cminute = (int) $this->str_get('mm');
        } else {
            $cday = '00';
            $cmonth = '00';
            $cyear = '0000';
            $chour = '00';
            $cminute = '00';
        }

        include (MySB_ROOTPATH.'/config.php');
        if(isset($mysb_datetime_oldschool) && $mysb_datetime_oldschool==true) {
            $form = '<span style="white-space: nowrap;">';
            $form .= '<select name="'.$prefix.'day" title="day" class="w-auto" style="text-align: right; padding: .25rem;">'."\n    ";
            $form .= "\n".'<option value="00">--</option>';
            for($i=1;$i<32;$i++)
                $form .= '<option value="'.$i.'" '.MySBUtil::form_isselected($i,$cday).'>'.$i.'</option>';
            $form .= "\n"."</select>\n".'<select name="'.$prefix.'month" title="month" class="w-auto" style="text-align: right; padding: .25rem;">'."\n    ";
            $form .= "\n".'<option value="00">--</option>';
            $this->fmt->setPattern('MMM');
            for($i=1;$i<13;$i++)
                $form .= '<option value="'.$i.'" '.MySBUtil::form_isselected($i,$cmonth).'>'.$this->fmt->format(strtotime('2000-'.$i.'-01')).'</option>';
            $form .= "\n"."</select>\n".'<select name="'.$prefix.'year" title="year" class="w-auto" style="text-align: right; padding: .25rem;">'."\n    ";
            $form .= "\n".'<option value="0000">--</option>';
            for($i=$this->year_min;$i<=$this->year_max;$i++)
                $form .= '<option value="'.$i.'" '.MySBUtil::form_isselected($i,$cyear).'>'.$i.'</option>';
            $form .= "\n".'</select>'."\n";
            $form .= '</span>';
            if($nohours==true) {
                $form .= '<input type="hidden" name="'.$prefix.'hour" value="0">'."\n";
                $form .= '<input type="hidden" name="'.$prefix.'minute" value="0">'."\n";
                return $form;
            }
            $form .= ' &bull; <span style="white-space: nowrap;">';
            $form .= '<input type="text"  name="'.$prefix.'hour" maxlength="2" size="2" title="hour" class="w-auto" style="text-align: center; padding: .5rem 0;" value="'.$chour.'">:';
            $form .= '<input type="text"  name="'.$prefix.'minute" maxlength="2" size="2" title="minute" class="w-auto" style="text-align: center; padding: .5rem 0;" value="'.$cminute.'">';
            $form .= '</span>';
            return $form;
        }
        if($nohours) {
            $dateexp = explode(' ', $this->date_string);
            $form = '<input type="date" id="'.$prefix.'date" name="'.$prefix.'date" '.
                    'value="'.$dateexp[0].'">';
        } else {
            $datestr = str_replace(' ', 'T', $this->date_string);
            $form = '<input type="datetime-local" id="'.$prefix.'date" name="'.$prefix.'date" '.
                    'value="'.$datestr.'">';
        }
        return $form;
    }

    /**
     * get the difference between to dates
     * in a unit at choice (years, months, days, hours, minutes or seconds)
     * @param   string          $format     format of absolute diff: y, m, d(default), h, i, s
     * @param   MySBDateTime    $c_date     set hours and minutes (default) or not
     * @return  integer                     diff
     */
    public function absDiff($format='d',$c_date=null) {
        if( $c_date==null )
            $c_date = new MySBDateTime('NOW');
        if( method_exists('DateTime','diff') ) {
            if( $this>$c_date ) $op = -1;
            else $op = 1;
            $diffdate = $this->diff($c_date);
            $absdiff = $diffdate->format('%y');
            if( $format=='y' ) return $op*$absdiff;
            $absdiff = $absdiff*12 + $diffdate->format('%m');
            if( $format=='m' ) return $op*$absdiff;
            $absdiff = $absdiff*31 + $diffdate->format('%d');
            if( $format=='d' ) return $op*$absdiff;
            $absdiff = $absdiff*24 + $diffdate->format('%h');
            if( $format=='h' ) return $op*$absdiff;
            $absdiff = $absdiff*60 + $diffdate->format('%i');
            if( $format=='i' ) return $op*$absdiff;
            $absdiff = $absdiff*60 + $diffdate->format('%s');
            if( $format=='s' ) return $op*$absdiff;
        } else {
            $tdate = getdate(strtotime($this->date_string));
            $cdate = getdate(strtotime($c_date->date_string));
            $absdiff = ($cdate['year']-$tdate['year']);
            if( $format=='y' ) return $absdiff;
            $absdiff = $absdiff*12 + ($cdate['mon']-$tdate['mon']);
            if( $format=='m' ) return $absdiff;
            $absdiff = $absdiff*31 + ($cdate['mday']-$tdate['mday']);
            if( $format=='d' ) return $absdiff;
            $absdiff = $absdiff*24 + ($cdate['hours']-$tdate['hours']);
            if( $format=='h' ) return $absdiff;
            $absdiff = $absdiff*60 + ($cdate['minutes']-$tdate['minutes']);
            if( $format=='i' ) return $absdiff;
            $absdiff = $absdiff*60 + ($cdate['seconds']-$tdate['seconds']);
            if( $format=='s' ) return $absdiff;
        }
        return;
    }

}


/**
 * Extended DateTime helper class
 *
 * @package    phpMySandBox
 * @subpackage Libraries\Core
 */
class MySBDateTimeHelper {

    /**
     * create a MySBDateTime corresponding to a form
     * @param   string      $prefix     prefix used for tag's names (default is 'mysbdt_')
     * @return  MySBDateTime            loaded DateTime from HTML form
     */
    public static function html_formLoad($prefix='mysbdt_') {
        global $_POST;
        include (MySB_ROOTPATH.'/config.php');
        if(isset($mysb_datetime_oldschool) && $mysb_datetime_oldschool==true) {
            $str = $_POST[$prefix.'year'].'-'.$_POST[$prefix.'month'].'-'.$_POST[$prefix.'day'].
                ' '.$_POST[$prefix.'hour'].':'.$_POST[$prefix.'minute'].':00';
        } else {
            $str = str_replace('T', ' ', $_POST[$prefix.'date']);
        }
        return new MySBDateTime($str);
    }

}

?>

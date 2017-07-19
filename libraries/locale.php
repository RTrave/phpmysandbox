<?php
/**
 * phpMySandBox - Simple Database Framework in PHP
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License', or
 * ('at your option) any later version.
 * (Roman Travé <roman.trave@gmail.com>, 2012)
 *
 * @package    phpMySandBox
 * @subpackage Libraries\Utils
 * @license    http://opensource.org/licenses/gpl-license.php  GNU Public License
 * @author     Roman Travé <roman.trave@gmail.com>
 */

// No direct access.
defined('_MySBEXEC') or die;


/**
 * Localisation functions class
 *
 * @package    phpMySandBox
 * @subpackage Libraries\Utils
 */
class MySBLocales {

    /**
     * @var    string          Locale complete
     */
    public $locale = 'en_US.UTF-8';
    /**
     * @var    string          Timezone complete
     */
    public $timezone = "Europe/London";
    /**
     * @var    string          ANSI name
     */
    public $lang = 'C';
    /**
     * @var    string          reduced locales
     */
    public $t_locale = 'en';
    /**
     * @var    array          Array of localized strings
     */
    public $array_locales = array();

    /**
     * Get a localised text (loaded in template)
     * @param   string  $locale         C version of localised text
     * @param   string  $timezone       Timezone
	 * @return  string
     */
    public function __construct($locale,$timezone) {
        global $app;
        include( MySB_ROOTPATH.'/config.php' );
        if($locale!=null) $this->locale = $locale;
        else $this->locale = $mysb_locale;
        if($timezone!=null) $this->timezone = $timezone;
        else $this->timezone = $mysb_timezone;
        $this->t_locale = $this->locale[0].$this->locale[1];
        $this->lang = $this->locale[0].$this->locale[1].'_'.$this->locale[3].$this->locale[4];
        setlocale (LC_ALL, $this->locale);
        date_default_timezone_set ($this->timezone);
    }

    /**
     * Get the language
     * @return  string                  language code (eg. fr_FR)
     */
    public static function getLanguage() {
        global $app;
        return $app->locales->lang;
    }

    /**
     * Get a localised text (loaded in template)
     * @param   string  $domain         C version of localised text
     * @param   string  $module         module containing the localized string
	 * @return  string
     */
    public function loadINIFile($domain,$module='') {
        global $app;
        $file = $domain.'.ini';
        if($module=='') $prefix = 'core';
        else $prefix = $module;
        // default ('en') locale file
        if($this->t_locale!='en') {
            if($module!='')
                $i_file = MySB_ROOTPATH.'/modules/'.$module.'/locales/en/'.$file;
            else $i_file = MySB_ROOTPATH.'/locales/en/'.$file;
            //echo $i_file.'<br>';
            if(file_exists($i_file)) {
                $ini_array = parse_ini_file($i_file);
                foreach($ini_array as $id => $str) 
                    if($str!="")
                        $this->array_locales[$id] = $str;
            }
        }
        // locale file
        if($module!='')
            $i_file = MySB_ROOTPATH.'/modules/'.$module.'/locales/'.$this->t_locale.'/'.$file;
        else $i_file = MySB_ROOTPATH.'/locales/'.$this->t_locale.'/'.$file;
        //echo $i_file.'<br>';
        if(file_exists($i_file)) {
            $ini_array = parse_ini_file($i_file);
            foreach($ini_array as $id => $str) 
                if($str!="")
                    $this->array_locales[$id] = $str;
        }
        // locale INI custom file (custom/core_fr_*.php)
        $i_file = MySB_ROOTPATH.'/custom/'.$prefix.'_'.$this->t_locale.'_'.$file;
        //echo $i_file.'<br>';
        if(file_exists($i_file)) {
            $ini_array = parse_ini_file($i_file);
            foreach($ini_array as $id => $str) 
                if($str!="")
                    $this->array_locales[$id] = $str;
        }
    }

    /**
     * Get a localised text (loaded in INI file)
     * @param   string  $str         string to translate
	 * @return  string
     */
    public function getText($str) {
        global $app;
        if( !isset($this->array_locales[$str]) or $this->array_locales[$str]==="" )
            return $str;
        return $this->array_locales[$str];
    }

    /**
     * Include a localised template
     * @param   string  $file         file translated
     * @param   string  $module         module containing the localized string
	 * @return  boolean
     */
    public function includeTemplate($file,$module='') {
        global $app;
        // locale custom file (custom/core_fr_*.php)
        if($module=='')
            $l_file = MySB_ROOTPATH.'/custom/core_'.$this->t_locale.'_'.$file;
        else $l_file = MySB_ROOTPATH.'/custom/'.$module.'_'.$this->t_locale.'_'.$file;
        if(file_exists($l_file)) { include ($l_file); return true; };
        // locale file
        if($module=='') {
            $l_file = MySB_ROOTPATH.'/locales/'.$this->t_locale.'/'.$file;
        } else {
            $l_file = MySB_ROOTPATH.'/modules/'.$module.'/locales/'.$this->t_locale.'/'.$file;
        }
        if(file_exists($l_file)) { include ($l_file); return true; };
        // default file
        if($module=='') {
            $l_file = MySB_ROOTPATH.'/locales/en/'.$file;
        } else {
            $l_file = MySB_ROOTPATH.'/modules/'.$module.'/locales/en/'.$file;
        }
        if(file_exists($l_file)) { include ($l_file); return true; }
        $app->LOG("MySBLocales::includeTemplate($file,$module): template not found");
        return false;
    }
}

/**
 * Get a localised text (sequential form)
 * @param   string  $string         string to translate
 * @return  string
 */
function _G($string) { global $app; return $app->locales->getText($string); }
/**
 * Include a localised template (sequential form)
 * @param   string  $file         file translated
 * @param   string  $module         module containing the localized string
 * @return  boolean
 */
function _incG($file,$module='') { global $app; return $app->locales->includeTemplate($file,$module); }

?>

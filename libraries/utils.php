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
 * @subpackage Libraries\Utils
 * @license    http://opensource.org/licenses/gpl-license.php  GNU Public License
 * @author     Roman Travé <roman.trave@gmail.com>
 */

// No direct access.
defined('_MySBEXEC') or die;

/**
 * Utils functions class
 *
 * @package    phpMySandBox
 * @subpackage Libraries\Utils
 */
class MySBUtil {

    /**
     * Verify string against injection
     * @param   string  $str        string to verify
     * @param   bool    $match_html verifiy string against HTML injection
	 * @return  bool
     */
    public static function strverif($str,$match_html=true) {
        if( ( $match_html==true ) and
            ( strstr($str, '>') or
              strstr($str, '<') ) )
            return false;
        if( stristr($str, 'SELECT') or stristr($str, 'JOIN') or
            stristr($str, 'UPDATE') or stristr($str, 'CREATE') or
            stristr($str, 'DELETE') or stristr($str, 'ALTER') or
            stristr($str, 'INSERT') or stristr($str, 'GRANT') )
             return false;
        return true;
    }

    /**
     * Transform string to be SQL compliant
     * @param   string  $str      string to transform
	 * @return  string
     */
    public static function str2db($str) {
        $str = str_replace( '\\\'', '\'', $str );
        $str = str_replace( '\'', '\\\'', $str );
        $str = str_replace( '"', '\\\'', $str );
        return $str;
    }

    /**
     * Transform string to be HTML compliant
     * @param   string  $str      string to transform
	 * @return  string
     */
    public static function str2html($str) {
        //$str = htmlspecialchars($str);
        if(strpos($str,'<')===false or strpos($str,'>')===false) {  // non-html
            $str = str_replace( "\r\n", '<br>', $str );
            $str = str_replace( "\n", '<br>', $str );
            //$str = '<p>'.$str.'</p>';
        }
        return $str;
    }

    /**
     * Transform HTML to string
     * @param   string  $text      string to transform
	 * @return  string
     */
    public static function html2str($text){
        $text = str_replace( '&nbsp;', " ", $text );
        $text = str_replace( '<br />', "\r\n", $text );
        $text = str_replace( '</p>', "</p>\r\n", $text );
        $text = strip_tags(html_entity_decode($text));
        return $text;
    }

    /**
     * Transform string to be strict string
     * @param   string  $str      string to transform
	 * @return  string
     */
    public static function str2strict($str) {
        $str = str_replace( "\r\n", '\n', $str );
        $str = str_replace( "\'", "'", $str );
        $str = str_replace( "'", '\\\'', $str );
        $str = str_replace( '"', '\\\'', $str );
        $str = str_replace( '<br>', '\n', $str );
        //$str = str_replace( '<[]>', '\n', $str );
        $str = preg_replace('#<[^>]+>#','',$str);
        return $str;
    }

    /**
     * transform WHERE statement string to large search
     * @param   string  $str      string to transform
	 * @return  string
     */
    public static function str2whereclause($str) {
	    if($str=='') return '$';
	    $str = strtolower($str);
	    $str = str_replace( "'", "\'", $str );
	    $str = str_replace( 'a', '[aàâãä]+', $str );
	    $str = str_replace( 'e', '[eéèêë]+', $str );
	    $str = str_replace( 'o', '[oôö]+', $str );
	    $str = str_replace( 'u', '[uùûü]+', $str );
	    $str = str_replace( 'y', '[yŷÿ]+', $str );
	    $str = str_replace( 'i', '[iîïy]+', $str );
	    return $str;
    }

    /**
     * transform string to be uppercase and mysql compliant
     * @param   string  $str      string to transform
	 * @return  string
     */
    public static function str2upper($str) {
	    $str = strtoupper( $str );
	    $str = str_replace( "\'", '\\\'', $str );
	    return $str;
    }

    /**
     * transform string in its abbrevation
     * @param   string  $string             string to transform
     * @param   int     $max_lenght         returned max complete lenght
     * @param   int     $word_max_lenght    returned words max lenght
	 * @return  string
     */
    public static function str2abbrv($string,$max_lenght=6,$word_max_lenght=4) {
        if(strlen($string)<=$max_lenght) return $string;
        $newstr = '';
        $words = explode(' ',$string);
        foreach($words as $word) {
            if(strlen($word)>$word_max_lenght) {
                $newstr .= preg_replace('#^(?:[\x00-\x7F]|[\xC0-\xFF][\x80-\xBF]+){0,0}'.'((?:[\x00-\x7F]|[\xC0-\xFF][\x80-\xBF]+){0,'.$word_max_lenght.'}).*#s','$1', $word);
                $newstr .= '. ';
            } else $newstr .= $word.' ';
            if( strlen($newstr)>=$max_lenght ) break;
        }
        return $newstr;
    }

    /**
     * Return 'selected' if values are equals
     * @param   string  $value1         Arg1 to compare
     * @param   string  $value2         Arg2 to compare
	 * @return  string
     */
    public static function form_isselected($value1,$value2) {
        if($value1==$value2) return ' selected="selected" ';
        return '';
    }
    /**
     * Return 'checked' if values are equals
     * @param   string  $value1         Arg1 to compare
     * @param   string  $value2         Arg2 to compare
	 * @return  string
     */
    public static function form_ischecked($value1,$value2) {
        if($value1==$value2) return ' checked="checked" ';
        return '';
    }
    /**
     * Return 1 if value is 'on', 0 if not
     * @param   string  $checked        boolean html value
	 * @return  integer
     */
    public static function form_checkbool($checked) {
        if($checked=='on') return 1;
        return 0;
    }

    /**
     * Extract mails form string to an array
     * @author Hugo HAMON <webmaster@apprendre-php.com>
     * @licence LGPL
     * @param string $sChaine la chaine contenant les e-mails
     * @return array $aEmails[0] Tableau dédoublonné des e-mails
     */
    public static function extractEmails($sChaine) {
        if(false !== preg_match_all('`\w(?:[-_.]?\w)*@\w(?:[-_.]?\w)*\.(?:[a-z]{2,4})`', $sChaine, $aEmails)) {
            if(is_array($aEmails[0]) && sizeof($aEmails[0])>0) {
                return array_unique($aEmails[0]);
            }
        }
        return null;
    }

    /**
     * Load a customisable template
     * @param   string  $name       name of the template (mytpl for templates/mytpl.php)
     * @param   string  $module     module containing the template
     * @param   string  $log        log of the *_process* par loading
     */
    public static function loadTemplate($name,$module='',$log=true) {
        global $app;
        if($module=='') $prefix = 'core';
        else $prefix = $module;
        $name = str_replace( '.', '', $name );

        // custom file (custom/*_*.php eg.:custom/core_admin/users.php)
        $l_file = MySB_ROOTPATH.'/custom/'.$prefix.'_'.$name.'.php';
        //echo $l_file.'<br>';
        if(file_exists($l_file)) { include ($l_file); return true; };

        // initial file
        if($module!='') {   // module file (modules/$module/templates/*.php)
            $l_file = MySB_ROOTPATH.'/modules/'.$module.'/templates/'.$name.'.php';
        } else {            // file (templates/*.php)
            $l_file = MySB_ROOTPATH.'/templates/'.$name.'.php';
        }
        //echo $l_file.'<br>';
        if(file_exists($l_file)) { include ($l_file); return true; }

        if( $log==true ) {
            $app->LOG("MySBUtil::loadTemplate($name,$module): template not found");
            $app->pushAlert("Fatal: template <i>$name</i> in module <i>$module</i> not found!");
        }
        return false;
    }

    /**
     * Load a customisable include
     * @param   string  $name       name of the include (mytpl for includes/myinc.php)
     * @param   string  $module     module containing the include
     * @param   string  $log        log of the *_process* par loading
     */
    public static function loadInclude($name,$module='',$log=true) {
        global $app;
        if($module=='') $prefix = 'core_includes';
        else $prefix = $module.'_includes';
        $name = str_replace( '.', '', $name );

        // custom file (custom/*_*.php eg.:custom/core_include/user_display.php)
        $l_file = MySB_ROOTPATH.'/custom/'.$prefix.'/'.$name.'.php';
        //echo $l_file.'<br>';
        if(file_exists($l_file)) { include ($l_file); return true; };

        // initial file
        if($module!='') {
            // module file (modules/$module/*.php)
            $l_file = MySB_ROOTPATH.'/modules/'.$module.'/includes/'.$name.'.php';
        } else {
            // file (templates/*.php)
            $l_file = MySB_ROOTPATH.'/includes/'.$name.'.php';
        }
        //echo $l_file.'<br>';
        if(file_exists($l_file)) { include ($l_file); return true; }

        if( $log==true )
            $app->LOG("MySBUtil::loadInclude($name,$module): include not found");
        return false;
    }


}

/**
 * Load a customisable template (sequential form of MySBUtil::loadTemplate())
 * @param   string  $name       name of the template (mytpl for templates/mytpl.php)
 * @param   string  $module     module containing the template
 * @param   string  $log        log of the *_process* par loading
*/
function _incTOBS($name,$module='',$log=true) { return MySBUtil::loadTemplate($name,$module,$log); }
/**
 * Load a customisable include (sequential form of MySBUtil::loadInclude())
 * @param   string  $name       name of the include (mytpl for includes/myinc.php)
 * @param   string  $module     module containing the include
 * @param   string  $log        log of the *_process* par loading
*/
function _incIOBS($name,$module='',$log=true) { return MySBUtil::loadInclude($name,$module,$log); }


/**
 * Coma-Separeted values class
 *
 * @package    phpMySandBox
 * @subpackage Libraries\Utils
 */
class MySBCSValues {

    /**
     * @var     array     Initial values to reference
     */
    public $values = array();

    /**
     * Init the values array
     * @param   string  $cs_string      string to transform
     */
    public function __construct( $cs_string=null ) {
        if($cs_string!=null and $cs_string!='') {
            $c_values = explode(',',$cs_string);
            foreach($c_values as $value)
                $this->values[] = (float) $value;
        }
    }

    /**
     * Add a value in array
     * @param   integer  $value      Value to add
     */
    public function add( $value ) {
        $value = str_replace(',','.',$value);
        $this->values[] = (float) $value;
    }

    /**
     * Del a value in array
     * @param   integer  $value      Value to delete
     * @param   bool  $multiple   Delete all occurences ? (default=true)
     */
    public function del($value,$multiple=true) {
        $value = str_replace(',','.',$value);
        foreach($this->values as $i=>$current_value) {
            if($current_value==$value) {
                unset($this->values[$i]);
                if($multiple==false) return;
            }
        }
    }

    /**
     * Return values in coma-separated string
     * @return   string
     */
    public function csstring() {
        $new_string = '';
        $cs_tag = 0;
        foreach($this->values as $i=>$value) {
            if($cs_tag==0) $cs_tag = 1 ;
            else $new_string .= ',';
            //$new_string .= floatval((float)$value);
            $new_string .= preg_replace("[^-0-9\.]",".",$value);
        }
        return $new_string;
    }

    /**
     * Return lenght of coma-separated string
     * @return   integer
     */
    public function len() {
        if(count($this->values)==0) return 0;
        return ((2*count($this->values))-1);
    }

    /**
     * Return true if $search_value is found in values
     * @param    integer  $search_value      Value to search
     * @return   boolean
     */
    public function have($search_value) {
        $search_value = str_replace(',','.',$search_value);
        foreach($this->values as $value)
            if($search_value==$value) return true;
        return false;
    }

}


?>

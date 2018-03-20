<?php
/**
 * phpMySandBox - Simple Database Framework in PHP
 *
 * Base DB objects class.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License', or
 * ('at your option) any later version.
 * (Roman Travé <roman.trave@gmail.com>, 2012)
 *
 * @package    phpMySandBox
 * @subpackage Libraries\Objects
 * @license    http://opensource.org/licenses/gpl-license.php  GNU Public License
 * @author     Roman Travé <roman.trave@gmail.com>
 */

// No direct access.
defined('_MySBEXEC') or die;


define('MYSB_VALUE_TYPE_INT', 1);
define('MYSB_VALUE_TYPE_BOOL', 2);
define('MYSB_VALUE_TYPE_VARCHAR64', 3);
define('MYSB_VALUE_TYPE_VARCHAR512', 4);
define('MYSB_VALUE_TYPE_TEXT', 5);
define('MYSB_VALUE_TYPE_VARCHAR64_SELECT', 6);
define('MYSB_VALUE_TYPE_TEL', 10);
define('MYSB_VALUE_TYPE_URL', 11);


/**
 * Value object class
 *
 * @package    phpMySandBox
 * @subpackage Libraries\Objects
 */
class MySBValue extends MySBObject {

    /**
     * Value type
     * @var    integer
     */
    public $type = null;

    /**
     * Config key name
     * @var    string
     */
    public $keyname = null;

    /**
     * Config grp ('' for root MySB)
     * @var    string
     */
    public $grp = null;


    /**
     * Value object constructor.
     * @param   array   $data_value        Array of values to set
     */
    public function __construct($data_value=array()) {
        global $app;
        parent::__construct((array) ($data_value));
    }

    /**
     * Value update.
     * @param     string    $table      DB table to update
     * @param     array     $data_value array of values
     * @param     integer   $id         id of the object
     * @param     string    $prefix     Prefix of the DB
     */
    public function __update($table,$data_value=array(),$id=null,$prefix='') {
        parent::__update($table, (array) ($data_value), $id, $prefix);
    }

    /**
     * Correspondance between Values and SQL types.
     * @param   string  $type       Internal ID of the value's type.
     * @return  string              SQL type of the value.
     */
    public static function Val2SQLType($type=null) {
        if($type==null) return '';
        switch($type) {
            case MYSB_VALUE_TYPE_INT:
                return 'int';
            case MYSB_VALUE_TYPE_BOOL:
                return 'int';
            case MYSB_VALUE_TYPE_VARCHAR64:
                return 'varchar(64)';
            case MYSB_VALUE_TYPE_VARCHAR512:
                return 'varchar(512)';
            case MYSB_VALUE_TYPE_TEXT:
                return 'varchar(512)';
            case MYSB_VALUE_TYPE_VARCHAR64_SELECT:
                return 'varchar(64)';
            case MYSB_VALUE_TYPE_TEL:
                return 'varchar(64)';
            case MYSB_VALUE_TYPE_URL:
                return 'varchar(128)';
        }
    }

    /**
     * Get the coresponding SQL type.
     * @param   string $type    force type of the value.
     * @return  string   SQL type of the value.
     */
    public function getSQLType() {
        return $this->Val2SQLType($this->type);
    }

    /**
     * Get the native MySB type.
     * @return  string     Type of the value.
     */
    public function getType() {
        switch($this->type) {
            case MYSB_VALUE_TYPE_INT:
                return 'int';
            case MYSB_VALUE_TYPE_BOOL:
                return 'boolean';
            case MYSB_VALUE_TYPE_VARCHAR64:
                return 'varchar64';
            case MYSB_VALUE_TYPE_VARCHAR512:
                return 'varchar512';
            case MYSB_VALUE_TYPE_TEXT:
                return 'text';
            case MYSB_VALUE_TYPE_VARCHAR64_SELECT:
                return 'select';
            case MYSB_VALUE_TYPE_TEL:
                return 'tel';
            case MYSB_VALUE_TYPE_URL:
                return 'url';
        }
    }

    /**
     * Get the HTML input form for the value.
     * @param   string  $prefix         form name prefix
     * @param   string  $value          initial value
     * @param   string  $title          comments
     * @param   boolean $directlink     show the link for mail, tel or url
     * @return  string                  input form in HTML format.
     */
    public function htmlForm($prefix,$value,$title='',$directlink=true) {
        global $app;
        switch($this->type) {
            case MYSB_VALUE_TYPE_INT:
                return '<input type="text" '.
                       'name="'.$prefix.$this->keyname.'" id="'.$prefix.$this->keyname.
                       '" maxlength="4" value="'.$value.'">';
            case MYSB_VALUE_TYPE_BOOL:
                return '<input style="float: right;" type="checkbox" '.
                       'name="'.$prefix.$this->keyname.'" id="'.$prefix.$this->keyname.'" '.
                       MySBUtil::form_ischecked($value,1).'>';
            case MYSB_VALUE_TYPE_VARCHAR64:
                return '<input type="text" '.
                       'name="'.$prefix.$this->keyname.'" id="'.$prefix.$this->keyname.
                       '" maxlength="62" value="'.$value.'">';
            case MYSB_VALUE_TYPE_VARCHAR512:
                return '<input type="text" name="'.$prefix.$this->keyname.'" id="'.$prefix.$this->keyname.
                       '" maxlength="510" value="'.$value.'">';
            case MYSB_VALUE_TYPE_TEXT:
                return '<textarea name="'.$prefix.$this->keyname.'" id="'.$prefix.$this->keyname.
                       '" rows="3">'.$value.'</textarea>';
            case MYSB_VALUE_TYPE_VARCHAR64_SELECT:
                $req_seloptions = MySBDB::query("SELECT * from ".MySB_DBPREFIX."valueoptions ".
                    "WHERE value_keyname='".$this->grp."-".$this->keyname."' ".
                    "ORDER BY value0",
                    "MySBValue::htmlForm($prefix,$value)",
                    true, '', true );
                $form_str = '
<select name="'.$prefix.$this->keyname.'" id="'.$prefix.$this->keyname.'">
    <option value="">&nbsp;</option>';
                while($seloption = MySBDB::fetch_array($req_seloptions)) {
                    $form_str .= '
    <option value="'.$seloption['value0'].'" '.MySBUtil::form_isselected($value,$seloption['value1']).'>'._G($seloption['value1']).'</option>';
                }
                $form_str .= '
</select>';
                return $form_str;
            case MYSB_VALUE_TYPE_TEL:
                $img = '';

                if( $value!='' and $directlink) $img = '<div style="float: left;"><a href="tel:'.$value.'" title="Tel:'.$value.' '.$title.'"><img src="images/icons/call-start.png" alt="phone call" class="mysbIcons_valuetel icon24"></a></div>';
                return $img.'<input type="tel" name="'.$prefix.$this->keyname.'" size="18" maxlength="62" value="'.$value.'" pattern="^((\+\d{1,3}(-| )?\(?\d\)?(-| )?\d{1,5})|(\(?\d{2,6}\)?))(-| )?(\d{3,4})(-| )?(\d{4})(( x| ext)\d{1,5}){0,1}$">';
            case MYSB_VALUE_TYPE_URL:
                $img = '';
                if( $value!='' and $directlink) $img = '<div style="float: left;"><a href="'.$value.'" target="_blank" title="URL:'.$value.' '.$title.'"><img src="images/icons/web-browser.png" alt="url link" class="mysbIcons_valueurl icon24"></a></div>';
                return $img.'<input type="url" name="'.$prefix.$this->keyname.'" size="20" maxlength="128" value="'.$value.'">';
        }
    }

    /**
     * Get the HTML input form for the value.
     * @param   string  $prefix         form name prefix
     * @param   string  $value          initial value
     * @param   boolean $directlink     show the link for mail, tel or url
     * @param   string  $label          label
     * @param   string  $help           help comments
     * @return  string                  input form in HTML format.
     */
    public function innerRow($prefix,$value,$directlink=true,$label='',$help='') {
        global $app;
        switch($this->type) {

            case MYSB_VALUE_TYPE_INT:
                return '
<label class="col-sm-4" for="'.$prefix.$this->keyname.'">
  '.$label.'<br>
  <span class="help">'.$help.'</span>
</label>
<div class="col-sm-8">
  <input type="text" maxlength="4" value="'.$value.'"
         name="'.$prefix.$this->keyname.'" id="'.$prefix.$this->keyname.'" >
</div>';

            case MYSB_VALUE_TYPE_BOOL:
                return '
<label class="col-sm-12" for="'.$prefix.$this->keyname.'">
  <input type="checkbox" class="mysbValue-checkbox"
         '.MySBUtil::form_ischecked($value,1).'
         name="'.$prefix.$this->keyname.'" id="'.$prefix.$this->keyname.'">
  '.$label.'<br>
  <span class="help">'.$help.'</span>
</label>';

            case MYSB_VALUE_TYPE_VARCHAR64:
                return '
<label class="col-sm-4" for="'.$prefix.$this->keyname.'">
  '.$label.'<br>
  <span class="help">'.$help.'</span>
</label>
<div class="col-sm-8">
  <input type="text" maxlength="62" value="'.$value.'"
         name="'.$prefix.$this->keyname.'" id="'.$prefix.$this->keyname.'" >
</div>';

            case MYSB_VALUE_TYPE_VARCHAR512:
                return '
<label class="col-sm-4" for="'.$prefix.$this->keyname.'">
  '.$label.'<br>
  <span class="help">'.$help.'</span>
</label>
<div class="col-sm-8">
  <input type="text" maxlength="510" value="'.$value.'"
         name="'.$prefix.$this->keyname.'" id="'.$prefix.$this->keyname.'" >
</div>';

            case MYSB_VALUE_TYPE_TEXT:
                return '
<label class="col-md-4" for="'.$prefix.$this->keyname.'">
  '.$label.'<br>
  <span class="help">'.$help.'</span>
</label>
<div class="col-md-8">
  <textarea name="'.$prefix.$this->keyname.'" id="'.$prefix.$this->keyname.'"
            rows="3">'.$value.'</textarea>
</div>';

            case MYSB_VALUE_TYPE_VARCHAR64_SELECT:
                $req_seloptions = MySBDB::query("SELECT * from ".MySB_DBPREFIX."valueoptions ".
                    "WHERE value_keyname='".$this->grp."-".$this->keyname."' ".
                    "ORDER BY value0",
                    "MySBValue::htmlForm($prefix,$value)",
                    true, '', true );
                $form_str = '
<label class="col-sm-4" for="'.$prefix.$this->keyname.'">
  '.$label.'<br>
  <span class="help">'.$help.'</span>
</label>
<div class="col-sm-8">
  <select name="'.$prefix.$this->keyname.'" id="'.$prefix.$this->keyname.'">
    <option value="">&nbsp;</option>';
                while($seloption = MySBDB::fetch_array($req_seloptions)) {
                    $form_str .= '
    <option value="'.$seloption['value0'].'" '.MySBUtil::form_isselected($value,$seloption['value1']).'>'._G($seloption['value1']).'</option>';
                }
                $form_str .= '
  </select>
</div>';
                return $form_str;

            case MYSB_VALUE_TYPE_TEL:
                if( $value!='' and $directlink)
                  $wform = 7;
                else
                  $wform = 8;
                $form_str = '
<label class="col-sm-4" for="'.$prefix.$this->keyname.'">
  '.$label.'<br>
  <span class="help">'.$help.'</span>
</label>
<div class="col-sm-'.$wform.' mysbValue-directlink-input1">
  <input type="tel" name="'.$prefix.$this->keyname.'" id="'.$prefix.$this->keyname.'"
         maxlength="62" value="'.$value.'"
         pattern="^((\+\d{1,3}(-| )?\(?\d\)?(-| )?\d{1,5})|(\(?\d{2,6}\)?))(-| )?(\d{3,4})(-| )?(\d{4})(( x| ext)\d{1,5}){0,1}$">
</div>';
                if( $value!='' and $directlink)
                  $form_str .= '
<a href="tel:'.$value.'" class="col-1 btn btn-primary-light mysbValue-directlink-img"
   title="'.$label.':'.$value.'">
  <img src="images/icons/call-start.png" alt="call-start">
</a>';
                return $form_str;
            case MYSB_VALUE_TYPE_URL:
                if( $value!='' and $directlink)
                  $wform = 7;
                else
                  $wform = 8;
                $form_str = '
<label class="col-sm-4" for="'.$prefix.$this->keyname.'">
  '.$label.'<br>
  <span class="help">'.$help.'</span>
</label>
<div class="col-sm-'.$wform.' mysbValue-directlink-input1">
  <input type="url" name="'.$prefix.$this->keyname.'" id="'.$prefix.$this->keyname.'"
         maxlength="62" value="'.$value.'">
</div>';
                if( $value!='' and $directlink)
                  $form_str .= '
<a href="'.$value.'" class="col-1 btn btn-primary-light mysbValue-directlink-img"
   title="'.$label.':'.$value.'" target="_blank">
  <img src="images/icons/web-browser.png" alt="web-browser">
</a>';
                return $form_str;
        }
    }

    /**
     * Get the HTML non-editable output for the value.
     * @param   string  $prefix         form name prefix
     * @param   string  $value          initial value
     * @param   string  $title          comments
     * @param   boolean $onlyicon       return just an icon (default=false)
     * @return  string                      input form in HTML format.
     */
    public function htmlFormNonEditable($prefix,$value,$title='',$onlyicon=false,$directlink=true) {
        global $app;
        $text = '';
        switch($this->type) {
            case MYSB_VALUE_TYPE_INT:
                if( $value=='' ) $value = '0';
                $title = MySBUtil::str2abbrv($title,4,4);
                if( $title!='' ) $text = '<b>'.$title.'</b>: ';
                return $text.$value.'';
            case MYSB_VALUE_TYPE_BOOL:
                //echo $value.' '.MySBUtil::form_ischecked($value,1).'<br>';
                if( $title!='' ) {
                    $title = MySBUtil::str2abbrv($title,10,10);
                    if( $value==1 ) return '<b>'.$title.'</b>';
                    else return '<span style="text-decoration: line-through;">'.$title.'</span>';
                }
                if( $value==1 )
                    return '<img src="images/icons/emblem-ok.png" alt="OK" class="mysbIcons_valueok">';
                else
                    return '';
            case MYSB_VALUE_TYPE_VARCHAR64:
                $title = MySBUtil::str2abbrv($title,4,4);
                if( $title!='' ) $text = '<b>'.$title.'</b>: ';
                return $text.$value.'';
            case MYSB_VALUE_TYPE_VARCHAR512:
                $title = MySBUtil::str2abbrv($title,4,4);
                if( $title!='' ) $text = '<b>'.$title.'</b>: ';
                return $text.$value.'';
            case MYSB_VALUE_TYPE_TEXT:
                $title = MySBUtil::str2abbrv($title,4,4);
                if( $title!='' ) $text = '<b>'.$title.'</b>: ';
                return $text.MySBUtil::str2html($value).'';
            case MYSB_VALUE_TYPE_VARCHAR64_SELECT:
                $title = MySBUtil::str2abbrv($title,4,4);
                if( $title!='' ) $text = '<b>'.$title.'</b>: ';
                return $text._G($value).'';
            case MYSB_VALUE_TYPE_TEL:
                if( $title!='' ) $text = $title.': ';
                $img = '';
                $valuetel = '';
                $vallen = strlen($value);
                for($i=1;$i<=$vallen;$i+=2) {
                    if( $valuetel!='' and $i<=10 ) $valuetel = ' '.$valuetel;
                    if( $vallen-$i>0 )
                        $valuetel = $value[$vallen-$i-1].$value[$vallen-$i].$valuetel;
                    else
                        $valuetel = $value[$vallen-$i].$valuetel;
                }
                if( $onlyicon!=true ) $texturl = '<span class="cell_hide" style="white-space: nowrap;">'.$valuetel.'</span>';
                if( $value!='' ) $img = '<img src="images/icons/call-start.png" alt="phone call" class="mysbIcons_valuetel icon24">';
                if($directlink)
                    return (string) '
                    <a href="tel:'.$value.'" title="'.$text.''.$valuetel.'">'.$img.$texturl.'</a>';
                else 
                    return (string) '
                    '.$texturl;
            case MYSB_VALUE_TYPE_URL:
                if( $title!='' ) $text = ''.$title.': ';
               $img = '';
                if( $onlyicon!=true ) {
                    $text_abbr = str_replace('http://','',$value);
                    $text_abbr = str_replace('https://','',$text_abbr);
                    $text_abbr = str_replace('www.','',$text_abbr);
                    $texturl = '<span class="cell_hide" style="white-space: nowrap;">'.MySBUtil::str2abbrv($text_abbr,12,12).'</span>';
                }
                if( $value!='' ) $img = '<img src="images/icons/web-browser.png" alt="url link" class="mysbIcons_valueurl icon24">';
                if( $value!='' ) return (string) '
                    <a href="'.$value.'" target="_blank" title="'.$text.': '.$value.'">'.$img.''.$texturl.'</a>';
                return;
        }
    }

    /**
     * Get the SQL 'where' part from the HTML input form for the value.
     * @param   string  $prefix             form name prefix
     * @return  string                      WHERE condition in SQL format.
     */
    public function htmlProcessValue($prefix) {
        global $_POST;
        if( isset($_POST[$prefix.$this->keyname]) ) $post_value = $_POST[$prefix.$this->keyname];
        else $post_value = '';
        switch($this->type) {
            case MYSB_VALUE_TYPE_INT:
                if( $post_value!=''and MySBUtil::strverif($post_value) )
                    return (string) $post_value;
                return (string) 0;
            case MYSB_VALUE_TYPE_BOOL:
                if( $post_value=='on' )
                    return (string) 1;
                return (string) 0;
            case MYSB_VALUE_TYPE_VARCHAR64:
                if( !MySBUtil::strverif($post_value) )
                    return '';
                return (string) $post_value;
            case MYSB_VALUE_TYPE_VARCHAR512:
                if( !MySBUtil::strverif($post_value) )
                    return '';
                return (string) $post_value;
            case MYSB_VALUE_TYPE_TEXT:
                return (string) $post_value;
            case MYSB_VALUE_TYPE_VARCHAR64_SELECT:
                $req_seloption = MySBDB::query("SELECT * from ".MySB_DBPREFIX."valueoptions ".
                    "WHERE ( value_keyname='".$this->grp."-".$this->keyname."' ".
                    "AND value0='".$post_value."' )",
                    "MySBValue::htmlProcessValue($prefix)");
                $seloption = MySBDB::fetch_array($req_seloption);
                return $seloption['value1'];
            case MYSB_VALUE_TYPE_TEL:
                if( !MySBUtil::strverif($post_value) )
                    return '';
                return (string) $post_value;
            case MYSB_VALUE_TYPE_URL:
                return (string) $post_value;
        }
    }

    /**
     * Get the HTML input form to search matching values.
     * @param   string  $prefix             form name prefix
     * @return  string                      input form in HTML format.
     */
    public function innerRowWhereClause($prefix,$label='',$help='',$colsize=12) {
        global $app;
        $output = '';
        $checknull = '
<div class="col-2 t-right" style="padding-left: 0; padding-right: 0;">
  !<input type="checkbox" name="'.$prefix.$this->keyname.'_null"
          style="margin-left: 0;">
</div>';
        switch($this->type) {
            case MYSB_VALUE_TYPE_INT:
                $output .= '
<label class="col-sm-'.($colsize-7).'" for="'.$prefix.$this->keyname.'">
  '.$label.'<br>
  <span class="help">'.$help.'</span>
</label>
<div class="col-sm-7">
<div class="content list"><div class="row">
  <div class="col-10 t-right">
    <input type="text" name="'.$prefix.$this->keyname.'_min" style="width: auto;"
           size="3" maxlength="15" value="">
    <small>&le;</small>val<small>&le;</small>
    <input type="text" name="'.$prefix.$this->keyname.'_max" style="width: auto;"
           size="3" maxlength="15" value="">
  </div>
  '.$checknull.'
</div></div>
</div>';
                break;
            case MYSB_VALUE_TYPE_BOOL:
                $output .= '
<label class="col-'.($colsize-4).'" for="'.$prefix.$this->keyname.'">
  '.$label.'<br>
  <span class="help">'.$help.'</span>
</label>
<div class="col-2 t-right">
  <input type="checkbox"
         name="'.$prefix.$this->keyname.'" id="'.$prefix.$this->keyname.'">
</div>
  '.$checknull.'';
                break;
            case MYSB_VALUE_TYPE_VARCHAR64:
                $output = '
<label class="col-sm-'.($colsize-7).'" for="'.$prefix.$this->keyname.'">
  '.$label.'<br>
  <span class="help">'.$help.'</span>
</label>
<div class="col-sm-7">
<div class="content list"><div class="row">
  <div class="col-10 t-right">
    <input type="text" name="'.$prefix.$this->keyname.'" id="'.$prefix.$this->keyname.'"
           maxlength="32" value="">
  </div>
  '.$checknull.'
</div></div>
</div>';
                break;
            case MYSB_VALUE_TYPE_VARCHAR512:
                $output .= '
<label class="col-sm-'.($colsize-7).'" for="'.$prefix.$this->keyname.'">
  '.$label.'<br>
  <span class="help">'.$help.'</span>
</label>
<div class="col-sm-7">
<div class="content list"><div class="row">
  <div class="col-10 t-right">
    <input type="text" name="'.$prefix.$this->keyname.'" id="'.$prefix.$this->keyname.'"
           maxlength="32" value="">
  </div>
  '.$checknull.'
</div></div>
</div>';
                break;
            case MYSB_VALUE_TYPE_TEXT:
                $output .= '
<label class="col-sm-'.($colsize-7).'" for="'.$prefix.$this->keyname.'">
  '.$label.'<br>
  <span class="help">'.$help.'</span>
</label>
<div class="col-sm-7">
<div class="content list"><div class="row">
  <div class="col-10 t-right">
    <input type="text" name="'.$prefix.$this->keyname.'" id="'.$prefix.$this->keyname.'"
           maxlength="32" value="">
  </div>
  '.$checknull.'
</div></div>
</div>';
                break;
            case MYSB_VALUE_TYPE_VARCHAR64_SELECT:
                $req_seloptions = MySBDB::query("SELECT * from ".MySB_DBPREFIX."valueoptions ".
                    "WHERE value_keyname='".$this->grp."-".$this->keyname."' ".
                    "ORDER BY value0",
                    "MySBValue::htmlFormWhereClause($prefix)",
                    true, '', true);
                $form_str = '
<label class="col-sm-'.($colsize-7).'" for="'.$prefix.$this->keyname.'">
  '.$label.'<br>
  <span class="help">'.$help.'</span>
</label>
<div class="col-sm-7">
<div class="content list"><div class="row">
  <div class="col-10">
    <select name="'.$prefix.$this->keyname.'" id="'.$prefix.$this->keyname.'">
      <option value="">&nbsp;</option>
      <option value="_any">'._G('SBGT_select_any').'</option>';
                while($seloption = MySBDB::fetch_array($req_seloptions)) {
                    $form_str .= '
      <option value="'.$seloption['value0'].'">'._G($seloption['value1']).'</option>';
                }
                $form_str .= '
    </select>
  </div>
  '.$checknull.'
</div></div>
</div>';
                $output .= $form_str;
                break;
            case MYSB_VALUE_TYPE_TEL:
                $output .= '
<label class="col-sm-'.($colsize-7).'" for="'.$prefix.$this->keyname.'">
  '.$label.'<br>
  <span class="help">'.$help.'</span>
</label>
<div class="col-sm-7">
<div class="content list"><div class="row">
  <div class="col-10">
    <input type="text" name="'.$prefix.$this->keyname.'" id="'.$prefix.$this->keyname.'" maxlength="32" value="">
  </div>
  '.$checknull.'
</div></div>
</div>';
                break;
            case MYSB_VALUE_TYPE_URL:
                $output .= '
<label class="col-sm-'.($colsize-7).'" for="'.$prefix.$this->keyname.'">
  '.$label.'<br>
  <span class="help">'.$help.'</span>
</label>
<div class="col-sm-7">
<div class="content list"><div class="row">
  <div class="col-10">
    <input type="text" name="'.$prefix.$this->keyname.'" maxlength="32" value="">
  </div>
  '.$checknull.'
</div></div>
</div>';
                break;
        }
/*
        $output .= '
<div class="col-1 t-right" style="padding-left: 0; padding-right: 0;">
  !<input type="checkbox" name="'.$prefix.$this->keyname.'_null"
          style="margin-left: 0;">
</div>';
*/
        return $output;
    }

    public function htmlFormWhereClause($prefix) {
        global $app;
        $output = '';
        switch($this->type) {
            case MYSB_VALUE_TYPE_INT:
                $output .= '<input type="text" name="'.$prefix.$this->keyname.'_min" size="3" maxlength="3" value=""><small>&lt;=</small>value<small>&lt;=</small><input type="text" name="'.$prefix.$this->keyname.'_max" size="3" maxlength="3" value="">';
                break;
            case MYSB_VALUE_TYPE_BOOL:
                $output .= '<input type="checkbox" name="'.$prefix.$this->keyname.'">';
                break;
            case MYSB_VALUE_TYPE_VARCHAR64:
                $output .= '<input type="text" name="'.$prefix.$this->keyname.'" size="16" maxlength="32" value="">';
                break;
            case MYSB_VALUE_TYPE_VARCHAR512:
                $output .= '<input type="text" name="'.$prefix.$this->keyname.'" size="16" maxlength="32" value="">';
                break;
            case MYSB_VALUE_TYPE_TEXT:
                $output .= '<input type="text" name="'.$prefix.$this->keyname.'" size="16" maxlength="32" value="">';
                break;
            case MYSB_VALUE_TYPE_VARCHAR64_SELECT:
                $req_seloptions = MySBDB::query("SELECT * from ".MySB_DBPREFIX."valueoptions ".
                    "WHERE value_keyname='".$this->grp."-".$this->keyname."' ".
                    "ORDER BY value0",
                    "MySBValue::htmlFormWhereClause($prefix)",
                    true, '', true);
                $form_str = '
<select name="'.$prefix.$this->keyname.'">
    <option value="">&nbsp;</option>
    <option value="_any">'._G('SBGT_select_any').'</option>';
                while($seloption = MySBDB::fetch_array($req_seloptions)) {
                    $form_str .= '
    <option value="'.$seloption['value0'].'">'._G($seloption['value1']).'</option>';
                }
                $form_str .= '
</select>';
                $output .= $form_str;
                break;
            case MYSB_VALUE_TYPE_TEL:
                $output .= '<input type="text" name="'.$prefix.$this->keyname.'" size="16" maxlength="32" value="">';
                break;
            case MYSB_VALUE_TYPE_URL:
                $output .= '<input type="text" name="'.$prefix.$this->keyname.'" size="16" maxlength="32" value="">';
                break;
        }
        $output .= ' ! <input type="checkbox" name="'.$prefix.$this->keyname.'_null">';
        return $output;
    }

    /**
     * Get the SQL 'where' part from the HTML input form to search matching values.
     * @param   string  $prefix             form name prefix
     * @return  string                      WHERE condition in SQL format.
     */
    public function htmlProcessWhereClause($prefix) {
        global $_POST;
        //if($_POST[$prefix.$this->id]=='') return null;
        if( isset($_POST[$prefix.$this->keyname.'_null']) and $_POST[$prefix.$this->keyname.'_null']=='on' )
            return '('.$this->keyname.'=\'\' or '.$this->keyname.' is null)';
        switch($this->type) {
            case MYSB_VALUE_TYPE_INT:
                $clause = '';
                $cflag = 0;
                if( isset($_POST[$prefix.$this->keyname.'_min']) and $_POST[$prefix.$this->keyname.'_min']!='' ) {
                    $clause .= $this->keyname.'>='.$_POST[$prefix.$this->keyname.'_min'];
                }
                if( isset($_POST[$prefix.$this->keyname.'_max']) and $_POST[$prefix.$this->keyname.'_max']!='' ) {
                    if ($clause!='') $clause .= ' and ';
                    $clause .= $this->keyname.'<='.$_POST[$prefix.$this->keyname.'_max'];
                }
                if ($clause!='') return '('.$clause.')';
                return null;
            case MYSB_VALUE_TYPE_BOOL:
                if( isset($_POST[$prefix.$this->keyname]) and $_POST[$prefix.$this->keyname]=='on' )
                    return $this->keyname.'=' . (string) 1;
                return null;
            case MYSB_VALUE_TYPE_VARCHAR64:
                if( !isset($_POST[$prefix.$this->keyname]) or $_POST[$prefix.$this->keyname]=='' ) return null;
                if( isset($_POST[$prefix.$this->keyname]) and $_POST[$prefix.$this->keyname]=='*' ) return $this->keyname."!=''";
                return $this->keyname." RLIKE '".
                    MySBUtil::str2whereclause((string) $_POST[$prefix.$this->keyname])."'";
            case MYSB_VALUE_TYPE_VARCHAR512:
                if( !isset($_POST[$prefix.$this->keyname]) or $_POST[$prefix.$this->keyname]=='' ) return null;
                if( isset($_POST[$prefix.$this->keyname]) and $_POST[$prefix.$this->keyname]=='*' ) return $this->keyname."!=''";
                return $this->keyname." RLIKE '".
                        MySBUtil::str2whereclause((string) $_POST[$prefix.$this->keyname])."'";
            case MYSB_VALUE_TYPE_TEXT:
                if( !isset($_POST[$prefix.$this->keyname]) or $_POST[$prefix.$this->keyname]=='' ) return null;
                if( isset($_POST[$prefix.$this->keyname]) and $_POST[$prefix.$this->keyname]=='*' ) return $this->keyname."!=''";
                return $this->keyname." RLIKE '".
                        MySBUtil::str2whereclause((string) $_POST[$prefix.$this->keyname])."'";
            case MYSB_VALUE_TYPE_VARCHAR64_SELECT:
                if( !isset($_POST[$prefix.$this->keyname]) or $_POST[$prefix.$this->keyname]=='' ) return null;
                if( isset($_POST[$prefix.$this->keyname]) and $_POST[$prefix.$this->keyname]=='_any' ) return $this->keyname."!=''";
                $req_seloption = MySBDB::query("SELECT * from ".MySB_DBPREFIX."valueoptions ".
                    "WHERE ( value_keyname='".$this->grp."-".$this->keyname."' ".
                    "AND value0='".$_POST[$prefix.$this->keyname]."' )",
                    "MySBValue::htmlProcessValue($prefix)");
                $seloption = MySBDB::fetch_array($req_seloption);
                return $this->keyname."='".MySBUtil::str2db($seloption['value1'])."'";
            case MYSB_VALUE_TYPE_TEL:
                if( !isset($_POST[$prefix.$this->keyname]) or $_POST[$prefix.$this->keyname]=='' ) return null;
                if( isset($_POST[$prefix.$this->keyname]) and $_POST[$prefix.$this->keyname]=='*' ) return $this->keyname."!=''";
                return $this->keyname." RLIKE '".
                    MySBUtil::str2whereclause((string) $_POST[$prefix.$this->keyname])."'";
            case MYSB_VALUE_TYPE_URL:
                if( !isset($_POST[$prefix.$this->keyname]) or $_POST[$prefix.$this->keyname]=='' ) return null;
                if( isset($_POST[$prefix.$this->keyname]) and $_POST[$prefix.$this->keyname]=='*' ) return $this->keyname."!=''";
                return $this->keyname." RLIKE '".
                    MySBUtil::str2whereclause((string) $_POST[$prefix.$this->keyname])."'";
        }
    }

    /**
     * Add a selection value option
     * @param   string  $option             option added
     */
    public function addSelectOption($option) {
        global $app;
        $req_lastnb = MySBDB::query("SELECT * from ".MySB_DBPREFIX."valueoptions ".
            "WHERE value_keyname='".$this->grp."-".$this->keyname."' ".
            "ORDER BY value0 DESC",
            "MySBValue::addSelectOption($option)");
        while($sel_option=MySBDB::fetch_array($req_lastnb)) {
            if($sel_option['value1']==MySBUtil::str2db($option))
                return;
        }
        MySBDB::data_seek($req_lastnb, 0);
        $last_option = MySBDB::fetch_array($req_lastnb);
        $new_nb = intval($last_option['value0']) + 1;
        if($new_nb<10) $str_nb = "00$new_nb";
        elseif($new_nb<100) $str_nb = "0$new_nb";
        else $str_nb = "$new_nb";
        MySBDB::query("INSERT into ".MySB_DBPREFIX."valueoptions ".
            "(value_keyname,value0,value1) VALUES ( ".
            "'".$this->grp."-".$this->keyname."',".
            "'".$str_nb."', ".
            "'".MySBUtil::str2db($option)."' )",
            "MySBValue::addSelectOption($option)");
    }

    /**
     * Delete a selection value option
     * @param   integer  $sel_id            Id of the option
     * @param   string  $option             option deleted
     */
    public function delSelectOption($sel_id,$option=null) {
        global $app;
        if($option==null) {
            $opt_where_clause = "value0='".$sel_id."'";
        } else {
            $opt_where_clause = "value1='".$option."'";
        }
        MySBDB::query("DELETE from ".MySB_DBPREFIX."valueoptions WHERE (".
            "value_keyname='".$this->grp."-".$this->keyname."' AND ".
            $opt_where_clause." )",
            "MySBValue::delSelectOption($option)");
    }

    /**
     * Modify a selection value option
     * @param   integer  $sel_id            Id of the option
     * @param   string  $option             option deleted
     */
    public function modSelectOption($sel_id,$option) {
        global $app;
        MySBDB::query("UPDATE ".MySB_DBPREFIX."valueoptions SET ".
            "value1='".MySBUtil::str2db($option)."' WHERE (".
            "value_keyname='".$this->grp."-".$this->keyname."' AND ".
            "value0='".$sel_id."' )",
            "MySBValue::modSelectOption($sel_id,$option)");
    }

    /**
     * Delete all value options
     */
    public function delValueOptions() {
        global $app;
        if($this->type==MYSB_VALUE_TYPE_VARCHAR64_SELECT)
            MySBDB::query("DELETE from ".MySB_DBPREFIX."valueoptions WHERE (".
                "value_keyname='".$this->grp."-".$this->keyname."' )",
                "MySBValue::delValueOptions()");
    }

}

?>

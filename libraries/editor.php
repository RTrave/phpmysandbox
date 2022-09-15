<?php
/**
 * phpMySandBox - Simple Database Framework in PHP
 *
 * Rich Text Editor handling library class and factory (TinyMCE editor)
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
 * Rich Text editor support class
 *
 * @package    phpMySandBox
 * @subpackage Libraries\Utils
 */
class MySBEditor {

    /**
     * @var     boolean     Is TinyMCE present ?
     */
    public $tmce_present = false;

    /**
     * @var     string      TinyMCE version
     */
    public $tmce_version = null;

    /**
     * @var     boolean     Is TinyMCE plugin jbimages present ?
     */
    public $tmce_jbimages = false;

    /**
     * @var     boolean     Is TinyMCE plugin moxie present ?
     */
    public $tmce_moxie = false;

    /**
     * Internal constructor
     */
    public function __construct() {
        if(is_file(MySB_ROOTPATH.'/vendor/tinymce/tinymce/tinymce.min.js')) {
            $this->tmce_present = true;
            $vlines = file(MySB_ROOTPATH.'/vendor/tinymce/tinymce/tinymce.min.js');
            $versionl = explode('// ',$vlines[0]);  //Version 4.*
            if(isset($versionl[1]))
              $this->tmce_version = $versionl[1];
            else {
              $versionl = explode('Version: ',$vlines[6]);  //Version 5.*
              if(isset($versionl[1]))
                $this->tmce_version = $versionl[1];
              else {
                $versionl = explode('version ',$vlines[1]);  //Version 6.*
                if(isset($versionl[1]))
                  $this->tmce_version = $versionl[1];
              }
            }
        }
        if( is_file(MySB_ROOTPATH.'/vendor/tinymce/tinymce/plugins/moxiemanager/plugin.min.js') )
            $this->tmce_moxie = true;
        elseif( is_file(MySB_ROOTPATH.'/vendor/tinymce/tinymce/plugins/jbimages/plugin.min.js') )
            $this->tmce_jbimages = true;

        echo '<script type="text/javascript" src="vendor/tinymce/tinymce/tinymce.min.js"></script>';

        // TODO: keep this until closing overlay remove all TinyMCE added code
        echo '
<script type="text/javascript">
tinymce.remove();
$(".mce-popover").remove();
$(".mce-tooltip").remove();
</script>';
    }

    /**
     * Init WYSIWYG editor support
     * @param   string  $selector_id ID of the selector
     * @param   string  $style      style used for the editor (normal(default),simple,custom)
     * @param   string  $menubar    menubar ('true'/'false')
     * @param   string  $plugins    plugins ('autolink lists link image charmap print preview hr anchor pagebreak searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking save table contextmenu directionality emoticons template paste textcolor')
     * @param   string  $toolbar1   toolbar1 ('insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image')
     * @param   string  $toolbar2   toolbar2 ('print preview media | forecolor backcolor code emoticons')
     * @return  string              return HTML code for Editor inclusion
     */
    public function init($selector_id, $style='normal',$menubar='true',$plugins='',$toolbar1='',$toolbar2='') {
        global $app;
        if( $this->tmce_present!=true ) return;
        $langconf = '';
        if( MySBLocales::getLanguage()!='C' and
            is_file(MySB_ROOTPATH.'/vendor/tinymce/tinymce/langs/'.MySBLocales::getLanguage().'.js') )
            $langconf = 'language : "'.MySBLocales::getLanguage().'",
    ';
        $code = '
<!-- TinyMCE Init: '.$style.' -->
';
        $jbimagescode = '';
        if( $this->tmce_jbimages ) {
            $jbimagescode = 'jbimages';
        }
        $moxiecode = '';
        if( $this->tmce_moxie ) {
            $code .= '
<script type="text/javascript" src="vendor/tinymce/tinymce/plugins/moxiemanager/plugin.min.js"></script>';
            $moxiecode = 'moxiemanager';
        }

        if($style=='simple')
            $code .= '
<script type="text/javascript">
tinymce.init({
    selector : "#'.$selector_id.'",
    '.$langconf.'
    menubar: false,
    toolbar_items_size: "small",
    plugins: "link image code '.$jbimagescode.' '.$moxiecode.'",
    toolbar: "bold italic forecolor | alignleft aligncenter alignright alignjustify | bullist numlist | code link image '.$jbimagescode.'",
    branding: false,
    forced_root_block: "none",
    promotion: false,
    content_css : "default",
    height: "300",
});
</script>';
        elseif($style=='normal')
            $code .= '
<script type="text/javascript">
tinymce.init({
    selector : "#'.$selector_id.'",
    '.$langconf.'
    toolbar_items_size: "small",
    plugins:
        "autolink lists link image charmap preview hr anchor pagebreak searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking save table directionality emoticons template '.$jbimagescode.' '.$moxiecode.'",
    toolbar: [
        "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent",
        "link image '.$jbimagescode.' | print preview media | forecolor backcolor code emoticons"
             ],
    branding: false,
    height: "300",
    forced_root_block: "none",
    promotion: false,
    content_css : "default",
});
</script>';
        elseif($style=='custom')
            $code .= '
<script type="text/javascript">
tinymce.init({
    selector : "#'.$selector_id.'",
    '.$langconf.'
    menubar: '.$menubar.',
    toolbar_items_size: "small",
    plugins: [
        "'.$plugins.' '.$jbimagescode.' '.$moxiecode.'"
    ],
    toolbar1: "'.$toolbar1.' '.$jbimagescode.'",
    toolbar2: "'.$toolbar2.'",
    branding: false,
    forced_root_block: "none",
    promotion: false,
    content_css : "default",
    height: "300",
});
</script>';
        $code .= '
<!-- /TinyMCE Init -->
';
        return $code;
    }

    /**
     * Activate WYSIWYG editor support
     * @param   string  $area_id        Id of the area to handle
     * @return  string                  return HTML code for Editor inclusion
     */
    public function active($area_id='Editor') { //DEPRECATED ?
        global $app;
        return '';  // TODO: keep this until inline mode is implemented
        if( $this->tmce_present!=true ) return;
        $code = '
<!-- TinyMCE Active -->
';
        $code .= '
<script type="text/javascript">
tinymce.execCommand("mceAddEditor", false, "'.$area_id.'");
</script>';
        $code .= '
<!-- /TinyMCE Active -->
';
        return $code;
    }
}

?>

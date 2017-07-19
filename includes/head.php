<?php
/***************************************************************************
 *
 *   phpMySandBox - TRoman<abadcafe@free.fr> - 2012
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License', or
 *   ('at your option) any later version.
 *
***************************************************************************/

// No direct access.
defined('_MySBEXEC') or die;

global $app;
?>

    <title><?php echo MySBConfigHelper::Value('website_name'); ?></title>
    <meta name="viewport" content="initial-scale=1.0, width=device-width">
    <link rel="stylesheet" type="text/css" href="mysb.css" media="all">
    <link rel="stylesheet" type="text/css" href="mysbhandheld.css" media="handheld,(max-width: 520px)">
    <link rel="stylesheet" type="text/css" href="mysbprint.css" media="print">
    <!--[if lte IE 8]>
        <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
        <link rel="stylesheet" type="text/css" href="mysb_ie678.css" media="all">
    <![endif]--> 
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <link rel="shortcut icon" href="images/favicon_32.png" type="image/x-icon" >
<?php //if($app->display_data['refresh_seconds']!=0) echo '    <meta http-equiv="refresh" content="'.$refresh_time.'; URL=index.php">';
?>

    <script src="jscripts/jquery-3.2.1.min.js" type="text/javascript"></script>
    <script src="jscripts/spin.min.js" type="text/javascript"></script>
    <script src="jscripts/mysb.js" type="text/javascript"></script>

<?php

$pluginsHeader = MySBPluginHelper::loadByType('Header');
foreach($pluginsHeader as $plugin) 
    $plugin->displayHeader();


if(file_exists(MySB_ROOTPATH.'/custom/core.css')) 
    echo '
    <link rel="stylesheet" type="text/css" href="custom/core.css" media="all">';
$modules = MySBModuleHelper::load();
foreach($modules as $module) {
    $mod_conf = MySBConfigHelper::get('mod_'.$module->name.'_enabled','modules');
    if($mod_conf!=null and $mod_conf->getValue()>=1) {
        //for all media
        if(file_exists(MySB_ROOTPATH.'/modules/'.$module->name.'/'.$module->name.'.css')) 
            echo '
    <link rel="stylesheet" type="text/css" href="modules/'.$module->name.'/'.$module->name.'.css" media="all">';
        if(file_exists(MySB_ROOTPATH.'/custom/'.$module->name.'.css')) 
            echo '
    <link rel="stylesheet" type="text/css" href="custom/'.$module->name.'.css" media="all">';
        //for handheld media
        if(file_exists(MySB_ROOTPATH.'/modules/'.$module->name.'/'.$module->name.'_handheld.css')) 
            echo '
    <link rel="stylesheet" type="text/css" href="modules/'.$module->name.'/'.$module->name.'_handheld.css" media="handheld,(max-width: 520px)">';
        if(file_exists(MySB_ROOTPATH.'/custom/'.$module->name.'_handheld.css')) 
            echo '
    <link rel="stylesheet" type="text/css" href="custom/'.$module->name.'_handheld.css" media="handheld,(max-width: 520px)">';
        //for printers media
        if(file_exists(MySB_ROOTPATH.'/modules/'.$module->name.'/'.$module->name.'_print.css')) 
            echo '
    <link rel="stylesheet" type="text/css" href="modules/'.$module->name.'/'.$module->name.'_print.css" media="print">';
        if(file_exists(MySB_ROOTPATH.'/custom/'.$module->name.'_print.css')) 
            echo '
    <link rel="stylesheet" type="text/css" href="custom/'.$module->name.'_print.css" media="print">';
    }
}

?>

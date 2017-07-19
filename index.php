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

// Set flag that this is a parent file.
define('_MySBEXEC', 1);


define('MySB_ROOTPATH', dirname(__FILE__));

if(file_exists(MySB_ROOTPATH.'/install/install_helper.php')) {
    include MySB_ROOTPATH.'/install/install_helper.php';
    MySBInstallHelper::isConfigInit();
}

include_once MySB_ROOTPATH.'/config.php';

define('MySB_DBPREFIX', $mysb_table_prefix);
if( !isset($_GET['mod']) ) $_GET['mod'] = '';
if( !isset($_GET['tpl']) ) $_GET['tpl'] = '';

require_once MySB_ROOTPATH.'/framework.php';

global $app;
$app = new MySBApplication;

if(file_exists(MySB_ROOTPATH.'/install/install_helper.php')) {
    if(!MySBInstallHelper::isInit())
        include MySB_ROOTPATH.'/install/dbinit.php';
}

$app->upgrade();
$app->upgrade_modules();

$pluginsInclude = MySBPluginHelper::loadByType('Include');
foreach($pluginsInclude as $plugin) 
    $plugin->includeFile();

$app->setlocale();
$app->authenticate();

$app->process();
$app->display();

$app->close();
?>

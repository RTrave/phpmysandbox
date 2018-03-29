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
define('_MySBSCRIPT', 1);

global $_GET;
parse_str(implode('&', array_slice($argv, 1)), $_GET);
$_GET['blanklay']=1;

define('MySB_ROOTPATH', dirname(__FILE__));

global $_SERVER;
$_SERVER = array();
$_SERVER['REMOTE_ADDR'] = 'script';
$_SERVER['REQUEST_URI'] = 'script.php';

include_once MySB_ROOTPATH.'/config.php';

define('MySB_DBPREFIX', $mysb_table_prefix);

require_once MySB_ROOTPATH.'/framework.php';

global $app;
$app = new MySBApplication;

$app->upgrade();
$app->upgrade_modules();

$pluginsInclude = MySBPluginHelper::loadByType('Include');
foreach($pluginsInclude as $plugin) 
  $plugin->includeFile();

$app->setlocale($mysb_locale,$mysb_timezone);
$app->authenticate();

$app->scriptCheck();
$app->auth_user = MySBUserHelper::getByID(1);

if( isset($_GET['tpl']) ) {
  $app->ctrl_route();
} else {
  $app->close();
  die("ERROR: no module nor template specified.\n");
}

$app->close();
?>

<?php 
/***************************************************************************
 *
 *   phpMySandBox/RSVP module - TRoman<abadcafe@free.fr> - 2012
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License', or
 *   ('at your option) any later version.
 *
***************************************************************************/

// No direct access.
defined('_MySBEXEC') or die;

global $app;

if(!MySBRoleHelper::checkAccess('admin')) return;

$app->pushMessage('This is a test tip!');

$file1 = new ExampleLib('templates/admin/example_ctrl.php');
//echo $file1->getCode();
$file2 = new ExampleLib('templates/admin/example.php');
//echo $file1->getCode();

include(_pathT('admin/example','example'));

?>

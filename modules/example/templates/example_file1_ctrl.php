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

if(!MySBRoleHelper::checkAccess('example_role')) die;

_incG("template1.php",'example','');
include(_pathT("template2",'example'));

$example_users = MySBUserHelper::searchBy('');

$file1 = new ExampleLib('templates/example_file1_ctrl.php');
$file2 = new ExampleLib('includes/item_ctrl.php');
$file3 = new ExampleLib('includes/adduo_ctrl.php');

include(_pathT('example_file1','example'));

?>


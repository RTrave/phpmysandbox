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

//global $app;
if(!MySBRoleHelper::checkAccess('example_role',false)) return;
?>

index_example.php loaded !!! <br>
<br>

<?php 
$file1 = new ExampleLib('__init.php');
echo $file1->getCode();
$file2 = new ExampleLib('framework.php');
echo $file2->getCode();
$file3 = new ExampleLib('libraries/example.php');
echo $file3->getCode();
$file4 = new ExampleLib('templates/index_example_ctrl.php');
echo $file4->getCode();
?>


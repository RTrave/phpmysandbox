<?php
/**
 * phpMySandBox - GitUpdate module
 * 
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License', or
 * ('at your option) any later version. 
 * (Roman Travé <roman.trave@abadcafe.org>, 2022)
 *
 * @package    phpMySandBox\GUpd
 * @license    http://opensource.org/licenses/gpl-license.php  GNU Public License
 * @author     Roman Travé <roman.trave@abadcafe.org>
 */


// No direct access.
defined('_MySBEXEC') or die;

global $app;

if(!MySBRoleHelper::checkAccess('admin')) return;

MySBUtil::mkdir('test');
MySBUtil::mkdir('test/test');
MySBUtil::delete('test/test1');
MySBUtil::recurseCopy('libraries','files/test/libs','libs1');
MySBUtil::rename('test/test','test1');
MySBUtil::delete('test');
MySBUtil::delete('test1');

include(_pathT("admin","gupd"));

?>


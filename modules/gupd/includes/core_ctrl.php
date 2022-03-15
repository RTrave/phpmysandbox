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


$mysb_core = new MySBCore();
$updater_core = new GUpdCore();

if(isset($_GET["gupd_coreupdate"])and$_GET["gupd_coreupdate"]==1) {
  $updater_core->update();
  echo '
<script>
  loadItem("gupd_core","index.php?mod=gupd&inc=core");
</script>';
  return;
}



echo '
<div class="content list">
  <div class="row">
    <div class="col-3 btn-primary-light">
      <p>File:</p>
    </div>
    <div class="col-9">
      <p>'.MySB_GUPDFiles.'</p>
    </div>
  </div>
  <div class="row">
    <div class="col-3 btn-primary-light">
      <p>Actual version:</p>
    </div>
    <div class="col-9">
      <p>rel'.$mysb_core->mysb_major_version.'.'.
      $mysb_core->mysb_minor_version.'</p>
    </div>
  </div>
  <div class="row">
    <div class="col-3 btn-primary-light">
      <p>Next version:</p>
    </div>
    <div class="col-7">
      <p>'.$updater_core->update_available().'</p>
    </div>
  <a class="hidelayed col-2 t-center btn-danger-light"
     href="index.php?mod=gupd&amp;inc=core&amp;gupd_coreupdate=1"
     title="Update Git API file">
    Update
  </a>
  </div>
</div>';

//$updater_core->display_json();


?>


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

if( isset($_GET["gupd_coreupdate"]) and
    $_GET["gupd_coreupdate"]==1 ) {
  $updater_core->update();
  echo '
<script>
  loadItem(
    "gupd_updater",
    "index.php?mod=gupd&inc=core&gupd_coreupdateok=1"
  );
</script>';
  return;
}

if( isset($_GET["gupd_coreprepare"]) and
    $_GET["gupd_coreprepare"]==1 ) {
  if( !$updater_core->prepare()) {
    $app->displayStopAlert("Error during preparation.");
  }
  $app->pushMessage('Zip of '.$updater_core->update_available().
                    ' version<br>ready');
  echo '
<script>
  loadItem("gupd_updater","index.php?mod=gupd&inc=core&gupd_coreprepareok=1");
</script>';
  return;
}

if( isset($_GET["gupd_coreupgrade"]) and
    $_GET["gupd_coreupgrade"]==1 ) {
  if( !$updater_core->upgrade()) {
    $app->displayStopAlert("Error during upgrade.");
  }
  $app->pushMessage('Core components of PHPMySandBox<br>'.
                    'updated on '.$updater_core->update_available().'.');
  echo '
<script>
  loadItem(
    "gupd_updater",
    "index.php?mod=gupd&inc=core&gupd_coreupgradeok=1"
  );
</script>';
  return;
}



echo '
<div class="content list">
  <h2 class="bg-primary">Core update</h2>
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
      <p>'.$updater_core->update_available().'<br>
      <span class="help">'.
        nl2br($updater_core->update_available_infos()).'
      </span></p>
    </div>
  <a class="hidelayed col-2 t-center btn-secondary-light"
     href="index.php?mod=gupd&amp;inc=core&amp;gupd_coreupdate=1"
     title="Update Git API file">
    <p>Update</p>
  </a>
  </div>';

if(isset($_GET["gupd_coreupdateok"])and$_GET["gupd_coreupdateok"]==1) {
  echo '
  <div class="row">
  <a class="hidelayed col-12 t-center btn-danger-light"
     href="index.php?mod=gupd&amp;inc=core&amp;gupd_coreprepare=1"
     title="Prepare upgrade">
    <p>Prepare Core components</p>
  </a>
  </div>
</div>';
}
if(isset($_GET["gupd_coreprepareok"])and$_GET["gupd_coreprepareok"]==1) {
  echo '
  <div class="row">
  <a class="hidelayed col-12 t-center btn-danger"
     href="index.php?mod=gupd&amp;inc=core&amp;gupd_coreupgrade=1"
     title="Apply upgrade">
    <p>Upgrade Core components</p>
  </a>
  </div>';
}
echo '
</div>';

?>


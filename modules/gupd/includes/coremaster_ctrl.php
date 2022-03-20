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
$updater_core = new GUpdCore(true);

if( isset($_GET["gupd_coremasterupdate"]) and
    $_GET["gupd_coremasterupdate"]==1 ) {
  //$updater_core->update(); PW check TODO
  echo '
<script>
  loadItem(
    "gupd_updater",
    "index.php?mod=gupd&inc=coremaster&gupd_coremasterupdateok=1"
  );
</script>';
  return;
}

if( isset($_GET["gupd_coremasterprepare"]) and
    $_GET["gupd_coremasterprepare"]==1 ) {
  if( !$updater_core->prepare()) {
    $app->displayStopAlert("Error during preparation.");
  }
  $app->pushMessage('Zip of HEAD master branch<br>ready');
  echo '
<script>
  loadItem(
    "gupd_updater",
    "index.php?mod=gupd&inc=coremaster&gupd_coremasterprepareok=1"
  );
</script>';
  return;
}

if( isset($_GET["gupd_coremasterupgrade"]) and
    $_GET["gupd_coremasterupgrade"]==1 ) {
  if( !$updater_core->upgrade()) {
    $app->displayStopAlert("Error during upgrade.");
  }
  $app->pushMessage('Core components of PHPMySandBox<br>'.
                    'updated on master branch.');
  echo '
<script>
  loadItem(
    "gupd_updater",
    "index.php?mod=gupd&inc=coremaster&gupd_coremasterupgradeok=1"
  );
</script>';
  return;
}



echo '
<div class="content list">
  <h2 class="bg-primary">Core update (master)</h2>
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
      <p>'.$updater_core->actual_version().'</p>
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
  <a class="col-2 t-center btn-secondary-light"
     href="https://github.com/RTrave/phpmysandbox/compare/'.$updater_core->actual_version().'...master"
     title="Compare master with current version" target="gitcompare">
    <p>Compare</p>
  </a>
  </div>';
if( !isset($_GET["gupd_coremasterupdateok"]) and 
    !isset($_GET["gupd_coremasterprepareok"]))
  echo '
<form action="index.php?mod=gupd&amp;inc=coremaster&amp;gupd_coremasterupdate=1"
      method="post" class="hidelayed">
  <div class="row label">
    <label class="col-3 btn-primary-danger" for="unlockpw">
      Password:
    </label>
    <div class="col-6">
      <input type="password" name="pw" id="unlockpw"
             value="">
    </div>
    <div class="col-3">
      <input type="submit" class="btn-primary"
             value="Unlock master upg">
    </div>
  </div>
</form>';

if(isset($_GET["gupd_coremasterupdateok"])and$_GET["gupd_coremasterupdateok"]==1) {
  echo '
  <div class="row">
  <a class="hidelayed col-12 t-center btn-danger-light"
     href="index.php?mod=gupd&amp;inc=coremaster&amp;gupd_coremasterprepare=1"
     title="Prepare upgrade">
    <p>Prepare Core components</p>
  </a>
  </div>
</div>';
}
if(isset($_GET["gupd_coremasterprepareok"])and$_GET["gupd_coremasterprepareok"]==1) {
  echo '
  <div class="row">
  <a class="hidelayed col-12 t-center btn-danger-light"
     href="index.php?mod=gupd&amp;inc=coremaster&amp;gupd_coremasterupgrade=1"
     title="Apply upgrade">
    <p>Upgrade Core components</p>
  </a>
  </div>';
}
echo '
</div>';

?>


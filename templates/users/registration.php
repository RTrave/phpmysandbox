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


if($empty_field==0) return;


if($empty_field>=1) {

    if( !isset($_POST['newlogin']) ) $_POST['newlogin'] = '';
    if( !isset($_POST['newlastname']) ) $_POST['newlastname'] = '';
    if( !isset($_POST['newfirstname']) ) $_POST['newfirstname'] = '';
    if( !isset($_POST['newmail']) ) $_POST['newmail'] = '';

?>

<div class="col-md-8">
<div class="content">

<form action="index.php?tpl=users/registration"
      method="post">

  <h1><?= _G('SBGT_h1_register') ?></h1>

  <h2><?= _G('SBGT_h2_accountinfos') ?></h2>

  <label class="row" for="newlogin">
    <p class="col-sm-4">
      <?= _G('SBGT_login') ?>
    </p>
    <div class="col-sm-8">
      <input type="text" name="newlogin" id="newlogin"
             maxlength="64"
             class="<?= $invalid_login ?>"
             value="<?= $_POST['newlogin'] ?>">
    </div>
  </label>

  <label class="row" for="newlastname">
    <p class="col-sm-4">
      <?= _G('SBGT_lastname') ?>
    </p>
    <div class="col-sm-8">
      <input type="text" name="newlastname" id="newlastname"
             maxlength="64"
             class="<?= $invalid_lastname ?>"
             value="<?= $_POST['newlastname'] ?>">
    </div>
  </label>

  <label class="row" for="newfirstname">
    <p class="col-sm-4">
      <?= _G('SBGT_firstname') ?>
    </p>
    <div class="col-sm-8">
      <input type="text" name="newfirstname" id="newfirstname"
             maxlength="64"
             class="<?= $invalid_firstname ?>"
             value="<?= $_POST['newfirstname'] ?>">
    </div>
  </label>

  <label class="row" for="newmail">
    <p class="col-sm-4">
      <?= _G('SBGT_mail') ?>
    </p>
    <div class="col-sm-8">
      <input type="email" name="newmail" id="newmail"
             maxlength="64"
             class="<?= $invalid_mail ?>"
             value="<?= $_POST['newmail'] ?>">
    </div>
  </label>


<?php
    $pluginsUserOption = MySBPluginHelper::loadByType('UserOption');
    if(count($pluginsUserOption)>=1) {
?>
  <h2 class="border-top"><?= _G('SBGT_user_options') ?></h2>
<?php
    }
    foreach($pluginsUserOption as $plugin) {
        if($plugin->module!='') {
            $module = MySBModuleHelper::getByName($plugin->module);
            if(!$module->isLoaded()) continue;
        }
        if($plugin->ivalue1!=1) continue;
        $pname = $plugin->value0;
?>
  <label class="row" for="<?= $plugin->formDisplayId() ?>">
    <p class="col-10">
      <?= _G($plugin->value1) ?>
    </p>
    <div class="col-2 t-right">
      <?= $plugin->formDisplay() ?>
    </div>
  </label>
<?php } ?>

  <div class="row border-top">
    <div class="col-sm-2"></div>
    <div class="col-sm-8">
      <input type="hidden" name="newuser_flag" value="-1">
      <input type="submit" value="<?= _G('SBGT_submit_infos') ?>">
    </div>
    <div class="col-sm-2"></div>
  </div>

</form>

</div>
</div>

<div class="f-right-md col-md-4">
<div class="content advert">
    <?= _G('SBGT_registration_infos') ?>
    <br><br>
    <b><?= _G('SBGT_hotmail_warning') ?></b>
</div>
</div>

<?php
}
?>

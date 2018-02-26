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
?>

<div class="col-lg-6">

<div class="content w-sm-100">

  <h1><?= _G('SBGT_h2_accountinfos') ?></h1>

<form action="index.php?tpl=users/profile" method="post"
      OnSubmit="return mysb_confirm('<?= _G('SBGT_profile_confirm') ?>')">

  <div class="row">
    <div class="col-4"><p><?= _G('SBGT_login') ?></p></div>
    <div class="col-8"><p><b><?= $app->auth_user->login ?></b></p></div>
  </div>

  <div class="row">
    <label for="user_lastname">
    <div class="col-sm-4">
      <p><?= _G('SBGT_lastname') ?></p>
    </div>
    <div class="col-sm-8">
      <input type="text" name="user_lastname" id="user_lastname" maxlength="32"
             value="<?= $app->auth_user->lastname ?>">
    </div>
    </label>
  </div>

  <div class="row">
    <label for="user_firstname">
    <div class="col-sm-4">
      <p><?= _G('SBGT_firstname') ?></p>
    </div>
    <div class="col-sm-8">
      <input type="text" name="user_firstname" id="user_firstname" maxlength="32"
             value="<?= $app->auth_user->firstname ?>">
    </div>
    </label>
  </div>

  <div class="row">
    <label for="user_mail">
    <div class="col-sm-4">
      <p><?= _G('SBGT_mail') ?></p>
    </div>
    <div class="col-sm-8">
      <input type="email" name="user_mail" id="user_mail" maxlength="32"
             value="<?= $app->auth_user->mail ?>">
    </div>
    </label>
  </div>

<?php
$pluginsUserOption = MySBPluginHelper::loadByType('UserOption');
if(count($pluginsUserOption)>=1)
    echo '
  <h2 class="border-top">
    '._G('SBGT_user_options').'
  </h2>';
foreach($pluginsUserOption as $plugin) {
    if($plugin->module!='') {
        $module = MySBModuleHelper::getByName($plugin->module);
        if(!$module->isLoaded()) continue;
    }
    if($plugin->ivalue1!=1) continue;
    $pname = $plugin->value0;
?>
  <div class="row">
    <label for="<?= $plugin->formDisplayId() ?>">
    <div class="col-10">
      <p><?= _G($plugin->value1) ?></p>
    </div>
    <div class="col-2 t-right">
      <?= $plugin->formDisplay() ?>
    </div>
    </label>
  </div>
<?php
}
?>

  <div class="row border-top" style="text-align: center;">
    <div class="col-sm-2"></div>
    <div class="col-sm-8">
      <input type="hidden" name="user_flag" value="1">
      <input type="submit" value="<?= _G('SBGT_update_datas') ?>">
    </div>
    <div class="col-sm-2"></div>
  </div>

</form>
</div>


</div>
<div class="col-lg-6">

<div class="content w-sm-100 border-top">
<form action="index.php?tpl=users/profile" method="post"
      OnSubmit="return mysb_confirm('<?= _G('SBGT_profile_confirmpassword') ?>')">

  <input type="text" name="login" style="display: none;"
         value="'.$app->auth_user->login.'">

  <h1><?= _G('SBGT_user_password') ?></h1>

  <div class="row">
    <label for="user_password">
    <div class="col-sm-8">
      <p><?= _G('SBGT_new_password') ?></p>
    </div>
    <div class="col-sm-4">
      <input type="password" name="user_password" id="user_password"
             value="" maxlength="32">
    </div>
    </label>
  </div>

  <div class="row">
    <label for="user_passwordconfirm">
    <div class="col-sm-8">
        <p><?= _G('SBGT_new_passwordconfirm') ?></p>
    </div>
    <div class="col-sm-4">
      <input type="password" name="user_passwordconfirm" id="user_passwordconfirm"
             value="" maxlength="32">
    </div>
    </label>
  </div>

  <div class="row border-top" style="text-align: center;">
    <div class="col-sm-2"></div>
    <div class="col-sm-8">
            <input type="hidden" name="password_flag" value="1">
            <input type="submit" value="<?= _G('SBGT_update_password') ?>">
    </div>
    <div class="col-sm-2"></div>
  </div>

</form>
</div>

<div class="content w-sm-100">
<form action="index.php?tpl=users/profile" method="post" 
      OnSubmit="return mysb_confirm('<?= _G('SBGT_profile_confirmdeluser') ?>')">

  <input type="text" name="userid" style="display: none;"
         value="'.$app->auth_user->id.'">

  <h1><?= _G('SBGT_user_deluser') ?></h1>

  <div class="row">
    <label for="userdel_password">
    <div class="right col-sm-8">
      <p><?= _G('SBGT_deluser_password') ?></p>
    </div>
    <div class="right col-sm-4">
      <input type="password" name="userdel_password" id="userdel_password"
             value="" maxlength="32">
    </div>
    </label>
  </div>

    <div class="row border-top" style="text-align: center;">
        <div class="col-sm-2"></div>
        <div class="col-sm-8">
            <input type="hidden" name="deluser_flag" value="1">
            <input class="danger" type="submit"
                   value="<?= _G('SBGT_user_deluser') ?>">
        </div>
        <div class="col-sm-2"></div>
    </div>

</form>
</div>

</div>


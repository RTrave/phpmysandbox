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

<h1><?= _G('SBGT_h2_accountinfos') ?></h1>

<div class="list_support">

<form action="index.php?tpl=users/profile" method="post" 
      OnSubmit="return mysb_confirm('<?= _G('SBGT_profile_confirm') ?>')">
<div class="boxed">

    <div class="title roundtop">
        <span><?= _G('SBGT_login') ?>: <b><?= $app->auth_user->login ?></b></span>
    </div>

    <div class="row">
        <div class="right">
            <input type="text" name="user_lastname" size="24" maxlength="32" value="<?= $app->auth_user->lastname ?>"></div>
        <span><?= _G('SBGT_lastname') ?></span>
    </div>

    <div class="row">
        <div class="right">
            <input type="text" name="user_firstname" size="24" maxlength="32" value="<?= $app->auth_user->firstname ?>"></div>
        <span><?= _G('SBGT_firstname') ?></span>
    </div>

    <div class="row">
        <div class="right">
            <input type="email" name="user_mail" size="24" maxlength="32" value="<?= $app->auth_user->mail ?>"></div>
        <span><?= _G('SBGT_mail') ?></span>
    </div>

<?php 
$pluginsUserOption = MySBPluginHelper::loadByType('UserOption');
if(count($pluginsUserOption)>=1) 
    echo '
    <div class="title">
        '._G('SBGT_user_options').'
    </div>';
foreach($pluginsUserOption as $plugin) {
    if($plugin->module!='') {
        $module = MySBModuleHelper::getByName($plugin->module);
        if(!$module->isLoaded()) continue;
    }
    if($plugin->ivalue1!=1) continue;
    $pname = $plugin->value0;
    echo '
    <div class="row">
        <div class="right">';
        echo $plugin->formDisplay();
        echo '</div>
        <span>'._G($plugin->value1).'</span>
    </div>';
}
?>

    <div class="row" style="text-align: center;">
        <span>
            <input type="hidden" name="user_flag" value="1">
            <input type="submit" value="<?= _G('SBGT_update_datas') ?>">
        </span>
    </div>

</div>
</form>

<form action="index.php?tpl=users/profile" method="post" 
      OnSubmit="return mysb_confirm('<?= _G('SBGT_profile_confirmpassword') ?>')">
<div class="boxed">

<input type="text" name="login" value="'.$app->auth_user->login.'" style="display: none;">

    <div class="title roundtop">
        <span><?= _G('SBGT_user_password') ?></span>
    </div>

    <div class="row">
        <div class="right"><input type="password" name="user_password" value="" size="16" maxlength="32"></div>
        <span><?= _G('SBGT_new_password') ?></span>
    </div>

    <div class="row">
        <div class="right"><input type="password" name="user_passwordconfirm" value="" size="16" maxlength="32"></div>
        <span><?= _G('SBGT_new_passwordconfirm') ?></span>
    </div>

    <div class="row" style="text-align: center;">
        <span>
            <input type="hidden" name="password_flag" value="1">
            <input type="submit" value="<?= _G('SBGT_update_password') ?>">
        </span>
    </div>

</div>
</form>

<form action="index.php?tpl=users/profile" method="post" 
      OnSubmit="return mysb_confirm('<?= _G('SBGT_profile_confirmdeluser') ?>')">
<div class="boxed">

<input type="text" name="userid" value="'.$app->auth_user->id.'" style="display: none;">

    <div class="title roundtop">
        <span><?= _G('SBGT_user_deluser') ?></span>
    </div>

    <div class="row">
        <div class="right"><input type="password" name="user_password" value="" size="16" maxlength="32"></div>
        <span><?= _G('SBGT_deluser_password') ?></span>
    </div>

    <div class="row" style="text-align: center;">
        <span>
            <input type="hidden" name="deluser_flag" value="1">
            <input type="submit" value="<?= _G('SBGT_user_deluser') ?>">
        </span>
    </div>

</div>
</form>

</div>

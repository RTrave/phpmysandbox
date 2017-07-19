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


if($app->empty_field>=1) {

    if( !isset($_POST['newlogin']) ) $_POST['newlogin'] = '';
    if( !isset($_POST['newlastname']) ) $_POST['newlastname'] = '';
    if( !isset($_POST['newfirstname']) ) $_POST['newfirstname'] = '';
    if( !isset($_POST['newmail']) ) $_POST['newmail'] = '';

    echo '
<h1>'._G('SBGT_h1_register').'</h1>

<div class="list_support">

<form   action="index.php?tpl=users/registration" 
        method="post" 
        OnSubmit="return validation()">
<div class="boxed" style="width: 380px;">

    <input type="hidden" name="newuser_flag" value="-1">

    <div class="title roundtop">
        <span><b>'._G('SBGT_h2_accountinfos').'</b></span>
    </div>

    <div class="row">
        <div class="right">
            <input type="text" name="newlogin" size="24" maxlength="64" value="'.$_POST['newlogin'].'">
        </div>
        <span>'._G('SBGT_login').'</span>';
    if($app->empty_field==2 and empty($_POST['newlogin'])) echo '
        <br><span style="color: red;">!!!'._G('SBGT_empty').'!!!</span>';
    echo '
    </div>

    <div class="row">
        <div class="right">
            <input type="text" name="newlastname" size="24" maxlength="64" value="'.$_POST['newlastname'].'">
        </div>
        <span>'._G('SBGT_lastname').'</span>';
    if($app->empty_field==2 and empty($_POST['newlastname'])) echo '
        <br><span style="color: red;">!!!'._G('SBGT_empty').'!!!</span>';
    echo '
    </div>

    <div class="row">
        <div class="right">
            <input type="text" name="newfirstname" size="24" maxlength="64" value="'.$_POST['newfirstname'].'">
        </div>
        <span>'._G('SBGT_firstname').'</span>';
    if($app->empty_field==2 and empty($_POST['newfirstname'])) echo '
        <br><span style="color: red;">!!!'._G('SBGT_empty').'!!!</span>';
    echo '
    </div>

    <div class="row">
        <div class="right">
            <input type="email" name="newmail" size="24" maxlength="128" value="'.$_POST['newmail'].'">
        </div>
        <span>'._G('SBGT_mail').'</span>';
    if($app->empty_field==2 and empty($_POST['newmail'])) echo '
        <br><span style="color: red;">!!!'._G('SBGT_empty').'!!!</span>';
    echo '
    </div>';

    $pluginsUserOption = MySBPluginHelper::loadByType('UserOption');
    if(count($pluginsUserOption)>=1) 
        echo '
    <div class="title">
        <span>'._G('SBGT_user_options').'</span>
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

    echo '

    <div class="row" style="text-align: center;">
        <span>
            <input type="submit" value="'._G('SBGT_submit_infos').'">
        </span>
    </div>

</div>

<div class="advert" style="width: 340px; max-width: 75%;">
    '._G('SBGT_registration_infos').'
    <br><br>
    <b>'._G('SBGT_hotmail_warning').'</b>
</div>

</form>

</div>';
}

?>

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
global $_GET;


echo '
<div class="content">';

$pluginsAuthLayer = MySBPluginHelper::loadByType('AuthLayer');

if(!isset($app->auth_user)) {

    echo '
<ul class="menu right">';
if($app->auth_user==null and MySBConfigHelper::Value('registration_auto')) 
    echo '
    <li>
    <div class="item"
         title="'._G('SBGT_topmenu_registrationinfos').'">
        <a  href="index.php?tpl=users/registration">'._G('SBGT_topmenu_registration').'</a>
    </div>
    </li> ';
echo '
    <li class="top last"
        onclick="toggle_slide150(\'logbox\');">
    <div class="item">
        <a><img src="images/icons/dialog-password.png"
                alt="'._G('SBGT_log_in').'"
                title="'._G('SBGT_log_in').'"></a>
    </div>
    </li>
</ul>

<div id="logbox" style="display: none; min-width: 200px;">
    '.$pluginsAuthLayer[0]->formAuthbox().'
</div>';

} else {

    echo '
<ul class="menu right">
    <li class="top last"
        onclick="toggle_slide150(\'logbox\');">
    <div class="item"
         title="'._G('SBGT_topmenu_profileinfos').'">
        <a><img src="images/icons/avatar-default.png"
             alt="'._G('SBGT_topmenu_profileinfos').'"></a>
    </div>
    </li>
</ul>

<div id="logbox" style="text-align: center; display: none; min-width: 200px;">
<div style="border-bottom: 2px solid #333333;">
    <b>'.$app->auth_user->firstname.'<br>'.$app->auth_user->lastname.'</b>
</div>
<ul class="menu left" style="width: 100%;">';
    if($app->auth_user and MySBRoleHelper::checkAccess('admin',false)) 
        echo '
    <li class="bottom first">
    <div class="item" 
         title="'._G('SBGT_topmenu_admin').'">
        <a  href="index.php?tpl=admin/admin">
            <img src="images/icons/preferences-system.png"
                 alt="'._G('SBGT_topmenu_admin').'"></a>
    </div>
    </li>
    <li>';
    else echo '
    <li class="bottom first">';
    if( $app->auth_user and MySBRoleHelper::checkAccess('change_profile',false) ) 
        echo '
    <div class="item">
        <a  href="index.php?tpl=users/profile" 
            title="'._G('SBGT_topmenu_profileinfos').'">
            <img src="images/icons/user-info.png"
                 alt="'._G('SBGT_topmenu_profileinfos').'"></a>
    </div>
    </li>
    <li style="float: right; border-right: 0px; border-left: 2px solid #333333;">';
    echo '
    <div class="item">
        <a  href="index.php?logout_flag=1" 
            title="'._G('SBGT_log_out').'">
            <img src="images/icons/system-shutdown.png"
                 alt="'._G('SBGT_log_out').'"></a>
    </div>
    </li>
</ul>
</div>';
}

$pluginsMenuItem = MySBPluginHelper::loadByType('MenuItem');
$pluginsMenuItem2 = array();
foreach($pluginsMenuItem as $plugin)
    if($plugin->displayA(2)!='') 
        $pluginsMenuItem2[] = $plugin;

echo '
<ul class="menu left">';
if( count($pluginsMenuItem2)!=0 )
    echo '
    <li class="top first"
        onclick="toggle_slide150(\'menu2box\');">
    <div class="item">
        <a title="Menu2"><img src="images/icons/view-list.png" alt="view-list"></a>
    </div>
    </li>
    <li>';
else
    echo '
    <li class="top first">';
echo '
    <div class="item" style="">
        <a  href="index.php" 
            title="'._G('SBGT_topmenu_homeinfos').'">'._G('SBGT_topmenu_home').'</a>
    </div>
    </li>';

foreach($pluginsMenuItem as $plugin)
    if($plugin->displayA(1)!='') echo '
    <li>
    <div class="item">
        '.$plugin->displayA(1).'
    </div>
    </li>';

echo '
</ul>

<div id="menu2box" style="display: none;">
<ul class="menu left">';
$menu_count = 0;
foreach($pluginsMenuItem2 as $plugin) {
    if( $menu_count++ < (count($pluginsMenuItem2)-1)) echo '
    <li>';
    else echo '
    <li class="bottom last" style="border: 0;">';
    echo '
    <div class="item">
        '.$plugin->displayA(2).'
    </div>
    </li>';
}
echo '
</ul>
</div>

</div>';

?>

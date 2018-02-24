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

$pluginsAuthLayer = MySBPluginHelper::loadByType('AuthLayer');
$pluginsMenuItem = MySBPluginHelper::loadByType('MenuItem');

?>

<div class="navbar" id="myTopnav">
<ul>
  <li class="no-collapse">
    <a href="index.php"
         title="<?= _G('SBGT_topmenu_homeinfos') ?>">
    <?= _G('SBGT_topmenu_home') ?></a>
  </li>
  <li class="icon-responsive">
    <a href="javascript:void(0);"
      onclick="responsiveToggle('myTopnav','navbar')"><img src="images/icons/view-list.png" alt="view-list">&nbsp;</a>
  </li>

<?php
foreach($pluginsMenuItem as $plugin)
    if($plugin->displayMenuItem(1)!='') echo '
  <li>
    '.$plugin->displayMenuItem(1).'
  </li>';
$pluginsMenuItem2 = array();
foreach($pluginsMenuItem as $plugin)
    if($plugin->displayMenuItem(2)!='')
        $pluginsMenuItem2[] = $plugin;
if( count($pluginsMenuItem2)!=0 ) {
    echo '
  <li class="dropdown" id="Menu2DropDown">
    <a href="javascript:void(0)" class="dropbtn secondary"
       onclick="dropdownToggle(\'Menu2DropDown\',\'dropdown\')">
      <img src="images/icons/view-list.png" alt="view-list">More</a>
    <div class="dropdown-content">';
    foreach($pluginsMenuItem2 as $plugin)
        echo '
      <div class="dropdown-item">
      '.$plugin->displayMenuItem(2).'
      </div>';
    echo '
    </div>
  </li>';
}
?>

<?php
if(!isset($app->auth_user)) {
    echo '
  <li class="right dropdown" id="LoginDropDown">
    <a href="javascript:void(0)" class="dropbtn"
       onclick="dropdownToggle(\'LoginDropDown\',\'right dropdown\')">
      <img src="images/icons/seahorse.png" alt="view-list">'._G('SBGT_log_in').'</a>
    <div class="dropdown-content">
      <div class="dropdown-item">
      '.$pluginsAuthLayer[0]->formAuthbox().'
      </div>
    </div>
  </li>';
  if($app->auth_user==null and MySBConfigHelper::Value('registration_auto'))
    echo '
  <li class="right">
    <a  href="index.php?tpl=users/registration" class="success"
        title="'._G('SBGT_topmenu_registrationinfos').'">'._G('SBGT_topmenu_registration').'</a>
  </li>';
} else {
    echo '
  <li class="right dropdown" id="LoggedDropDown">
    <a href="javascript:void(0)" class="dropbtn"
       onclick="dropdownToggle(\'LoggedDropDown\',\'right dropdown\')">
      <img src="images/icons/avatar-default.png" alt="'._G('SBGT_topmenu_profileinfos').'">'._G('SBGT_topmenu_profile').'</a>
    <div class="dropdown-content">
      <div class="dropdown-item">
      <span style="text-align: center;"><b>'.$app->auth_user->firstname.'<br>
        '.$app->auth_user->lastname.'</b></span>
      </div>';
    if($app->auth_user and MySBRoleHelper::checkAccess('admin',false))
        echo '
      <div class="dropdown-item">
      <a href="index.php?tpl=admin/admin" class="dropdown-item"
           title="'._G('SBGT_topmenu_admin').'">
           <img src="images/icons/preferences-system.png"
                 alt="'._G('SBGT_topmenu_admininfos').'">'._G('SBGT_topmenu_admin').'</a>
      </div>';
    if( $app->auth_user and MySBRoleHelper::checkAccess('change_profile',false) )
        echo '
      <div class="dropdown-item">
      <a href="index.php?tpl=users/profile" class="dropdown-item"
           title="'._G('SBGT_topmenu_profileinfos').'">
           <img src="images/icons/user-info.png"
                 alt="'._G('SBGT_topmenu_profileinfos').'">'._G('SBGT_topmenu_profile').'</a>
      </div>';
    echo '
      <div class="dropdown-item">
      <a href="index.php?logout_flag=1" class="dropdown-item danger"
            title="'._G('SBGT_log_out').'">
            <img src="images/icons/system-shutdown.png"
                 alt="'._G('SBGT_log_out').'">'._G('SBGT_log_out').'
        </a>
      </div>
    </div>
  </li>';
}
?>

</ul>
</div>

<script>
function dropdownToggle(navDIV,baseClass) {
    var x = document.getElementById(navDIV);
    if (x.className === baseClass) {
        x.className += " dropped";
    } else {
        x.className = baseClass;
    }
}
</script>



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

if(!MySBRoleHelper::checkAccess('admin')) return;

global $_GET;
function isActive($tpl_code) {
  if( $_GET['tpl']==$tpl_code )
    return 'no-collapse';
  else return '';
}
?>

<div class="col-lg-3">

<div class="navbar" id="NavBarColumn">
<ul>
  <li class="<?= isActive('admin/admin') ?>">
    <a href="index.php?tpl=admin/admin"
       title="phpMySandbox">
      phpMySandbox</a>
  </li><li class="icon-responsive">
    <a href="javascript:void(0);"
            onclick="responsiveToggle('NavBarColumn','navbar')">
      <img src="images/icons/view-list.png" alt="view-list">
    </a>
  </li><li class="<?= isActive('admin/users') ?>">
    <a href="index.php?tpl=admin/users"
       title="Title">
    <?= _G('SBGT_adminusers') ?></a>
  </li><li class="<?= isActive('admin/groups') ?>">
    <a href="index.php?tpl=admin/groups"
       title="Title">
    <?= _G('SBGT_admingroups') ?></a>
  </li><li class="<?= isActive('admin/plugins') ?>">
    <a href="index.php?tpl=admin/plugins"
       title="Title">
    <?= _G('SBGT_adminplugins') ?></a>
  </li><li class="dropdown right" id="ModulesDropDown">
    <a href="javascript:void(0)" class="dropbtn secondary"
       onclick="dropdownToggle('ModulesDropDown','dropdown right')">
      <img src="images/icons/view-list.png" alt="view-list">Modules</a>
    <div class="dropdown-content">
<?php
$pluginsMenuItem = MySBPluginHelper::loadByType('MenuItem');
foreach($pluginsMenuItem as $plugin)
    if( $plugin->displayA(3)!='' ) echo '
      <div class="dropdown-item">
        '.$plugin->displayA(3).'
      </div>';
?>
    </div>
  </li>
</ul></div>

</div>


<?php
/*
echo '
<script>
function toggle_arrows(vDIV){
    if($("#"+vDIV).css("display")=="block") 
        $("#"+vDIV).fadeOut(0);
    else
        $("#"+vDIV).fadeIn(300);
}
</script>
<div id="mysbMenuLevelSwitch" class="cell_show" style="text-align: center;">
    <div id="mysbMenuSwitch" class="roundtop button" onClick="toggle_slide(\'mysbMenuLevel\'); toggle_arrows(\'arr1\'); toggle_arrows(\'arr2\'); toggle_arrows(\'arr3\'); toggle_arrows(\'arr4\');">
        <img id="arr1" src="images/icons/go-down.png" style="float: left;" alt="go-down">
        <img id="arr2" src="images/icons/go-down.png" style="float: right;" alt="go-down">
        <img id="arr3" src="images/icons/go-up.png" style="float: left; display: none;" alt="go-up">
        <img id="arr4" src="images/icons/go-up.png" style="float: right; display: none;" alt="go-up">
        Toggle<br>Menu
    </div>
</div>
<div id="mysbMenuLevel" class="cell_hide">
<ul>
    <li class="first last"><a href="index.php?tpl=admin/admin">phpMySandbox</a></li>
    <li class="first"><a href="index.php?tpl=admin/users">'._G('SBGT_adminusers').'</a></li>
    <li class=""><a href="index.php?tpl=admin/groups">'._G('SBGT_admingroups').'</a></li>
    <li class="last"><a href="index.php?tpl=admin/plugins">'._G('SBGT_adminplugins').'</a></li>';

$pluginsMenuItem = MySBPluginHelper::loadByType('MenuItem');
foreach($pluginsMenuItem as $plugin)
    if( $plugin->displayA(3)!='' ) echo '
    <li class="first last">'.$plugin->displayA(3).'</li>';

echo '
</ul>
</div>';
*/
?>

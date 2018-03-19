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

?>

<div class="navbar expanded" id="NavBarColumn">
<ul>
  <li class="<?= isActive('main') ?>">
    <a href="index.php?tpl=admin/admin&page=main"
       title="phpMySandbox">
      phpMySandbox</a>
  </li><li class="icon-responsive">
    <a href="javascript:void(0);"
            onclick="responsiveToggle('NavBarColumn','navbar')">
      <img src="images/icons/view-list.png" alt="view-list">
    </a>
  </li><li class="<?= isActive('users') ?>">
    <a href="index.php?tpl=admin/admin&page=users"
       title="Title">
    <?= _G('SBGT_adminusers') ?></a>
  </li><li class="<?= isActive('groups') ?>">
    <a href="index.php?tpl=admin/admin&page=groups"
       title="Title">
    <?= _G('SBGT_admingroups') ?></a>
  </li><li class="<?= isActive('plugins') ?>">
    <a href="index.php?tpl=admin/admin&page=plugins"
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
</ul>
</div>

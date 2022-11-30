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

$appv = new MySBCore();
$version = $appv->mysb_major_version.'.'.$appv->mysb_minor_version;
?>

<div class="navbar no-collapse">
<ul>
  <li class="right">
    <span>
      <a href="<?= MySBConfigHelper::Value('technical_contact') ?>">Contact</a>
    </span>
  </li>
</ul>
</div>
<div class="navbar no-collapse">
<ul style="min-height: 90px; font-size: 80%;">
  <li class="right">
    <span>
      <a  href="https://github.com/RTrave/phpmysandbox"
          title="PhpMySandBox OpenSource Project on GitHub"
          target="_blank">PhpMySandBox <?= $version ?></a> by 
      <a  href="https://github.com/RTrave"
          title="RTrave on GitHub"
          target="_blank">RTrave</a>
    </span>
    <span>
<?php
$modules = MySBModuleHelper::loadLoaded();
if(count($modules)!=0) {
    echo ' ( mod';
    foreach($modules as $module) {
        $cmod = $module->module_helper;
        if( isset($cmod->homelink) and  isset($cmod->lname)  and  isset($cmod->release_version))
            echo '
      <a href="'.$cmod->homelink.'"
         target="_blank"
         title="release:'.$cmod->release_version.' sql_version:'.$cmod->version.'">'.$module->name.'</a>';
        else
            echo ' '.$module->name;
    }
    echo ' )';
}
?>
    </span>
  </li>
</ul>
</div>

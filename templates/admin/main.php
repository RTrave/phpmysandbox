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

include(MySB_ROOTPATH.'/config.php');
$httpbase = 'index.php?tpl=admin/admin&amp;page=main';
?>

<div class="content">

  <h1>System informations</h1>

  <h2>PHP</h2>
<?php foreach($infos_php as $info) { ?>
  <div class="row">
    <p class="col-4">
      <?= $info[0] ?>
    </p>
    <p class="col-8">
      <?= $info[1] ?>
    </p>
  </div>
<?php } ?>
  <div class="row">
    <div class="col-md-4"><p>test mail on:</p></div>
    <div class="col-md-8">
      <form action="<?= $httpbase ?>" method="post">
      <input type="hidden" name="test_mail" value="1">
      <input type="submit" class="btn-primary"
             value="<?= $app->auth_user->mail ?>">
      </form>
    </div>
  </div>
  <h2 class="border-top">DataBase</h2>
<?php foreach($infos_db as $info) { ?>
  <div class="row">
    <p class="col-4">
      <?= $info[0] ?>
    </p>
    <p class="col-8">
      <?= $info[1] ?>
    </p>
  </div>
<?php } ?>
</div>

<div class="content">

  <h1>Configurations</h1>

<form action="index.php?inc=admin/conf_display"
        class="hidelayed"
        method="post">

  <div id="app_config">
<?php include(_pathI('admin/conf_display_ctrl')); ?>
  </div>
  <div class="row" style="text-align: center;">
    <div class="col-md-3"></div>
    <div class="col-md-6">
      <input type="hidden" name="config_modif" value="1">
      <input type="submit" class="btn-primary" value="Modify">
    </div>
    <div class="col-md-3"></div>
  </div>

</form>
</div>

<?php
$modules = MySBModuleHelper::load();
foreach($modules as $module) {
    $mod_conf = MySBConfigHelper::get('mod_'.$module->name.'_enabled','modules');
    echo '
<div class="content">
  <h1 id="mod_'.$module->name.'">Module: '.$module->name.'</h1>
  <div class="row">
    <p class="col-12">Version: '.$module->module_helper->version.' <br>
    <i>Required: '.admin_getrequired($module).'</i></p>
  </div>';
    if($mod_conf==null) {
      echo '
  <form action="'.$httpbase.'#mod_'.$module->name.'" method="post">
  <div class="row">
    <div class="col-md-6">
      <span>module <b>disabled</b>:</span>
    </div>
    <div class="col-md-6">
      <input type="hidden" name="set_mod" value="'.$module->name.'">
      <input type="submit" class="btn-success"
             value="Set '.$module->name.'">
    </div>
  </div>
  </form>
</div>';
    } else {

      if($mod_conf->getValue()>=1) {
        echo '
  <div class="row">
  <form action="'.$httpbase.'#mod_'.$module->name.'" method="post"
        OnSubmit="return mysb_confirm(\'Unset module '.$module->name.'?\')">
    <div class="col-md-6">
      <span>Module <b>enabled</b>:</span>
    </div>
    <div class="col-md-6">
      <input type="hidden" name="unset_mod" value="'.$module->name.'">
      <input type="submit" class="btn-danger"
             value="Unset '.$module->name.'">
    </div>
  </form>
  </div>';
      } elseif($mod_conf->getValue()==-1) {
        echo '
  <div class="row">
<p class="col-12">
module <b>disabled</b>:
</p>
<form action="'.$httpbase.'#mod_'.$module->name.'" method="post">
<p class="col-6">
    <input type="hidden" name="reinit_mod" value="'.$module->name.'">
    <input type="submit" class="btn-success"
           value="Reinit '.$module->name.'">
</p>
</form>
<form action="'.$httpbase.'#mod_'.$module->name.'" method="post" OnSubmit="return mysb_confirm(\'Delete tables in '.$module->name.'?\')">
<p class="col-6">
    <input type="hidden" name="delete_mod" value="'.$module->name.'">
    <input type="submit"  class="btn-danger"
           value="Delete '.$module->name.' tables">
</p>
</form>
  </div>';
      }
      echo '

  <h2 class="border-top">Configurations</h2>';
      $configs = MySBConfigHelper::loadByGrp($module->name);
      if(count($configs)==0) {
        echo '
  <div class="row">
    <div class="col">
      <p>No config values</p>
    </div>
  </div>';
      } else {
        echo '
<form action="'.$httpbase.'#mod_'.$module->name.'" method="post">';
        foreach($configs as $config) {
          echo '
  <div class="row label">
    '.$config->innerRow($module->name.'config_',$config->value, false,
                        _G($config->comments), $config->keyname).'
  </div>';
        }
        echo '
  <div class="row">
    <div class="col-md-3"></div>
    <div class="col-md-6">
        <input type="hidden" name="moduleconfig_mod" value="'.$module->name.'">
        <input type="submit" class="btn-primary"
               value="Update '.$module->name.' configs">
    </div>
    <div class="col-md-3"></div>
  </div>
</form>';
      }
      echo '
  <h2 class="border-top">Plugins</h2>';
      $plugins = MySBPluginHelper::loadByModule($module->name);
      if(count($plugins)==0) echo '
  <div class="row">
      <p class="col">No plugin values</p>
  </div>';
      else {
        echo '
  <div class="row">
    <div class="col"><ul>';
        foreach($plugins as $plugin) {
          echo '
      <li>'.$plugin->name.' <i>('.$plugin->type.')</i></li>';
        }
        echo '
    </ul></div>
  </div>';
      }
      echo '
</div>';
    }

}
?>

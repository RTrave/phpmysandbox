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

include( _pathI('admin/menu') );

include(MySB_ROOTPATH.'/config.php');
?>

<h1>PHPMySandBox administration</h1>

<h2>System informations</h2>

<div class="boxed">
    <div class="title"><b>PHP</b></div>
<?php foreach($infos_php as $info) { ?>
    <div class="row">
        <div class="right"><?= $info[1] ?></div>
        <?= $info[0] ?>
    </div>
<?php } ?>
    <div class="row" style="text-align: center;">
        <form action="index.php?tpl=admin/admin" method="post">
        <input type="hidden" name="test_mail" value="1">
        <input type="submit" value="test mail on <?= $app->auth_user->mail ?>">
        </form>
    </div>
</div>

<div class="boxed">
    <div class="title"><b>DataBase</b></div>
<?php foreach($infos_db as $info) { ?>
    <div class="row">
        <div class="right"><?= $info[1] ?></div>
        <?= $info[0] ?>
    </div>
<?php } ?>
</div>

<h2>Configurations</h2>

<form action="index.php?inc=admin/conf_display" 
        class="hidelayed"
        method="post">
<div class="boxed">
<div id="app_config">
<?php include(_pathI('admin/conf_display_ctrl')); ?>
</div>
    <div class="row" style="text-align: center;">
        <input type="hidden" name="config_modif" value="1">
        <input type="submit" value="Modify">
    </div>
</div>
</form>

<h2>Modules</h2>

<?php
$modules = MySBModuleHelper::load();
foreach($modules as $module) {
    $mod_conf = MySBConfigHelper::get('mod_'.$module->name.'_enabled','modules');
    echo '
<h3 id="mod_'.$module->name.'">'.$module->name.'</h3>';
echo '
<p>Version: '.$module->module_helper->version.' <br>
<i>Required: '.admin_getrequired($module).'</i></p>';
    if($mod_conf==null) {
        echo '
<form action="index.php?tpl=admin/admin#mod_'.$module->name.'" method="post">
<p>
    module <b>disabled</b>: 
    <input type="hidden" name="set_mod" value="'.$module->name.'">
    <input type="submit" value="Set '.$module->name.'">
</p>
</form>';
    } else {

    if($mod_conf->getValue()>=1) {
        echo '
<form action="index.php?tpl=admin/admin#mod_'.$module->name.'" method="post" 
      OnSubmit="return mysb_confirm(\'Unset module '.$module->name.'?\')">
<p>
    Module <b>enabled</b>: 
    <input type="hidden" name="unset_mod" value="'.$module->name.'">
    <input type="submit" value="Unset '.$module->name.'">
</p>
</form>';
    } elseif($mod_conf->getValue()==-1) {
        echo '
<form action="index.php?tpl=admin/admin#mod_'.$module->name.'" method="post">
<p>
module <b>disabled</b>: 
    <input type="hidden" name="reinit_mod" value="'.$module->name.'">
    <input type="submit" value="Reinit '.$module->name.'">
</p>
</form>
<form action="index.php?tpl=admin/admin#mod_'.$module->name.'" method="post" OnSubmit="return mysb_confirm(\'Delete tables in '.$module->name.'?\')">
<p>
    <input type="hidden" name="delete_mod" value="'.$module->name.'">
    <input type="submit" value="Delete '.$module->name.' tables">
</p>
</form>';
    }
    echo '

<div class="boxed">
<div class="title"><b>Configurations</b></div>';
$configs = MySBConfigHelper::loadByGrp($module->name);
if(count($configs)==0) echo "\n<i>No config values</i></p>";
else {
        echo '
<form action="index.php?tpl=admin/admin#mod_'.$module->name.'" method="post">';
        foreach($configs as $config) {
        if( $config->getType()!='text' ) {
            echo '
    <div class="row">
        <div class="right">'.$config->htmlForm($module->name.'config_',$config->value).'</div>
        '._G($config->comments).'<br>
        <span class="help">'.$config->keyname."</span>
    </div>";
        } else {
            echo '
    <div class="row" style="text-align: right;">
        <div style="float: left; text-align: left;">'._G($config->comments).'<br>
        <span class="help">'.$config->keyname.'</span></div>
        <div style="display: inline-block; margin: 0px 0px 0px auto;">'.$config->htmlForm('config_',$config->value).'</div>
    </div>';
        }
        }
        echo '
    <div class="row" style="text-align: center;">
        <input type="hidden" name="moduleconfig_mod" value="'.$module->name.'">
        <input type="submit" value="Update '.$module->name.' configs">
    </div>
</form>';
}
echo '
</div>

<div class="boxed">
<div class="title"><b>Plugins</b></div>';
    $plugins = MySBPluginHelper::loadByModule($module->name);
    if(count($plugins)==0) echo '
<div class="row" style="text-align: center;"><i>No plugin</i></div>';
    else {
        echo '
<div class="row"><ul>';
        foreach($plugins as $plugin) {
            echo '
    <li>'.$plugin->name.' <i>('.$plugin->type.')</i></li>';
        }
        echo '
</ul></div>';
    }
    echo '
</div>';
}

}
?>

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


?>

<div class="row">

<?php include( _pathI('admin/menu') );


echo '
<div class="col-lg-9">
<div class="content">
<h1>'._G('SBGT_adminplugins').'</h1>';



if( isset($_GET['plugin_id']) ) {
    $current_plugin = MySBPluginHelper::getByID($_GET['plugin_id']);
    $roles = MySBRoleHelper::load();
    echo '
<form action="index.php?tpl=admin/plugins&amp;plugin_id='.$current_plugin->id.'" method="post">
<div class="boxed">
    <h2>'.$current_plugin->id.': '.$current_plugin->name.'</h2>
    <div class="row">
        <div class="right">'.$current_plugin->type.'</div>
        '._G('SBGT_plugin_type').'
    </div>
    <div class="row">
        <div class="right">'.$current_plugin->module.'</div>
        '._G('SBGT_plugin_module').'
    </div>
    <div class="row">
        <div class="right">'.$current_plugin->childclass.'</div>
        '._G('SBGT_plugin_childclass').'
    </div>
    <div class="row">
        <div class="right"><input type="text" size="24" maxlength="64" name="plg_name" value="'.$current_plugin->name.'"></div>
        '._G('SBGT_plugin_name').'
    </div>
    <div class="row">
        <div class="right">
        <select name="plg_role">
            <option value=""></option>';
            foreach($roles as $role) {
                echo '
            <option value="'.$role->name.'" '.MySBUtil::form_isselected($role->name,$current_plugin->role).'>'.$role->comments.'</option>';
            }
        echo '
        </select>
        </div>
        '._G('SBGT_plugin_role').'
    </div>
    <div class="row">
        <div class="right"><input type="text" size="1" maxlength="1" name="plg_priority" value="'.$current_plugin->priority.'"></div>
        '._G('SBGT_plugin_priority').'
    </div>
    '.$current_plugin->html_valueform().'
    <div class="row" style="text-align: center;">
        <input type="hidden" name="plugin_edit_process" value="1">
        <input type="submit" value="'._G('SBGT_editplugin_submit').'">
    </div>
</div>
</form>
</div>
</div>
</div>';
    return;
}


$plugins = MySBPluginHelper::load();
$current_type = '';

foreach($plugins as $plugin) {
    if( $current_type!=$plugin->type ) {
        if( $current_type!='' ) 
            echo '
    <div class="row" style="text-align: center;">
    <div class="col-sm-6"></div>
    <div class="col-sm-6">
        <input type="hidden" name="plugin_modif" value="'.$current_type.'">
        <input type="submit" value="'._G('SBGT_adminplugins_prioritysubmit').'">
    </div>
    </div>
</div>
</form>';
        $current_type = $plugin->type;
        echo '
<form action="index.php?tpl=admin/plugins" method="post">
<div class="content list">
  <h2 class="border-top">'.$current_type.'</h2>';
    }
    echo '
  <div class="row">
    <a class="col-sm-10 btn" href="index.php?tpl=admin/plugins&amp;plugin_id='.$plugin->id.'">
        <p><i>'.$plugin->id.'</i> '.$plugin->name.'<br>
        <small><i>'._G('SBGT_adminplugins_frommodule').': ';
    if( $plugin->module!='' )  echo $plugin->module;
    else echo 'core';
    echo ' </i></small></p>
    </a>
    <div class="col-sm-1 t-right">
      '._G('SBGT_adminplugins_priority').':
    </div>
    <div class="col-sm-1">
      <input type="text" size="1" maxlength="1" name="plg_prio'.$plugin->id.'" value="'.$plugin->priority.'">
    </div>
    </div>
';
}
echo '
    <div class="row">
    <div class="col-sm-6"></div>
    <div class="col-sm-6">
        <input type="hidden" name="plugin_modif" value="'.$current_type.'">
        <input type="submit" value="'._G('SBGT_adminplugins_prioritysubmit').'">
    </div>
    </div>
</div>
</form>
</div>';

echo '
<div class="content">
<h1>'._G('SBGT_adminuo_add').'</h1>
<form action="index.php?tpl=admin/plugins" method="post">

  <h2>'._G('SBGT_adminuo_new').'</h2>

  <label class="row" for="option_name">
    <p class="col-sm-6">
      '._G('SBGT_uo_keyname').'
    </p>
    <div class="col-sm-6">
      <input type="text" name="option_name" id="option_name">
    </div>
  </label>

  <label class="row" for="option_text">
    <p class="col-md-4">
      '._G('SBGT_uo_text').'
    </p>
    <div class="col-md-8">
      <input type="text" name="option_text" id="option_text">
    </div>
  </label>

  <label class="row" for="option_type">
    <p class="col-sm-6">
      '._G('SBGT_uo_type').'
    </p>
    <div class="col-sm-6">
      <select name="option_type" id="option_type">
        <option value="'.MYSB_VALUE_TYPE_INT.'">INT</option>
        <option value="'.MYSB_VALUE_TYPE_BOOL.'">BOOL</option>
        <option value="'.MYSB_VALUE_TYPE_VARCHAR64.'">VARCHAR64</option>
        <option value="'.MYSB_VALUE_TYPE_VARCHAR512.'">VARCHAR512</option>
        <option value="'.MYSB_VALUE_TYPE_TEXT.'">TEXT</option>
        <option value="'.MYSB_VALUE_TYPE_VARCHAR64_SELECT.'">VARCHAR64_SELECT</option>
      </select>
    </div>
  </label>

  <label class="row" for="option_mail">
    <p class="col-sm-6">
      '._G('SBGT_uo_mailnotify').'
    </p>
    <div class="col-sm-6">
      <input type="email" name="option_mail" id="option_mail">
    </div>
  </label>

  <label class="row" for="option_useredit">
    <p class="col-10">
        '._G('SBGT_uo_useredition').'
    </p>
    <div class="col-2 t-right">
        <input type="checkbox" name="option_useredit" id="option_useredit">
    </div>
  </label>

  <div class="row" style="text-align: center;">
    <div class="col-sm-3"></div>
    <div class="col-sm-6">
        <input type="hidden" name="option_add" value="1">
        <input type="submit" value="'._G('SBGT_adminuo_newsubmit').'">
    </div>
    <div class="col-sm-3"></div>
  </div>

</form>
</div>';

$pluginsUserOption = MySBPluginHelper::loadByType('UserOption');
echo '
<div class="content">
<h1>'._G('SBGT_exportuo').'</h1>
<form action="index.php?tpl=admin/plugins" method="post">

  <h2>'._G('SBGT_exportuo_selection').'</h2>';
foreach($pluginsUserOption as $plugin) {
    $pname = $plugin->value0;
    echo '
  <label class="row" for="uo_'.$plugin->id.'">
    <p class="col-10">
        '.$pname.'<br>
        <span class="help">'._G($plugin->value1).'</span>
    </p>
    <div class="col-2 t-right">';
    if($plugin->module!='') {
        $module = MySBModuleHelper::getByName($plugin->module);
        if(!$module->isLoaded()) 
            echo '<i>(module '.$plugin->module.' not loaded)</i>';
        else 
            echo '<input type="checkbox" name="uo_'.$plugin->id.'" id="uo_'.$plugin->id.'">';
    } else 
        echo '<input type="checkbox" name="uo_'.$plugin->id.'" id="uo_'.$plugin->id.'">';
    echo '
    </div>
  </label>';
}
echo '
  <div class="row" style="text-align: center;">
    <div class="col-sm-3"></div>
    <div class="col-sm-6">
        <input type="hidden" name="option_export" value="1">
        <input type="submit" value="'._G('SBGT_exportuo_submit').'">
    </div>
    <div class="col-sm-3"></div>
  </div>

</form>
</div>
</div>
</div>';

?>

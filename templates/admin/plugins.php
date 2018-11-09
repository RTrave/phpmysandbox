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

$httpbase = 'index.php?tpl=admin/admin&amp;page=plugins';

?>

<?php
echo '
<div class="content">';

if( isset($_GET['plugin_id']) ) {
    $current_plugin = MySBPluginHelper::getByID($_GET['plugin_id']);
    $roles = MySBRoleHelper::load();
    echo '
<h1>'.$current_plugin->id.': '.$current_plugin->name.'</h1>
<form action="'.$httpbase.'" method="post">

  <div class="row">
    <p class="col-4">
      '._G('SBGT_plugin_type').'
    </p>
    <p class="col-8">
      '.$current_plugin->type.'
    </p>
  </div>

  <div class="row">
    <p class="col-4">
      '._G('SBGT_plugin_module').'
    </p>
    <p class="col-8">
      '.$current_plugin->module.'
    </p>
  </div>

    <div class="row">
    <p class="col-4">
      '._G('SBGT_plugin_childclass').'
    </p>
    <p class="col-8">
      '.$current_plugin->childclass.'
    </p>
  </div>

  <div class="row label">
    <label class="col-sm-4" for="plg_name">
      '._G('SBGT_uo_keyname').'
    </label>
    <p class="col-sm-8">
      <input type="hidden" name="plg_name" id="plg_name"
             value="'.$current_plugin->name.'">'.$current_plugin->name.'
    </p>
  </div>

  <div class="row label">
    <label class="col-sm-4" for="plg_role">
      '._G('SBGT_plugin_role').'
    </label>
    <div class="col-sm-8">
      <select name="plg_role" id="plg_role">
        <option value=""></option>';
            foreach($roles as $role) {
                echo '
        <option value="'.$role->name.'" '.MySBUtil::form_isselected($role->name,$current_plugin->role).'>'.$role->comments.'</option>';
            }
        echo '
      </select>
    </div>
  </div>

  <div class="row label">
    <label class="col-sm-4" for="plg_priority">
      '._G('SBGT_plugin_priority').'
    </label>
    <div class="col-sm-8">
      <input type="text" maxlength="1"
             name="plg_priority" id="plg_priority"
             value="'.$current_plugin->priority.'">
    </div>
  </div>

    '.$current_plugin->html_valueform().'

  <div class="row" style="text-align: center;">
    <div class="col-sm-2"></div>
    <div class="col-sm-8">
      <input type="hidden" name="plugin_edit_process" value="'.$current_plugin->id.'">
      <input type="submit" class="btn-primary"
             value="'._G('SBGT_editplugin_submit').'">
    </div>
    <div class="col-sm-2"></div>
  </div>

</form>
</div>
</div>
</div>';
    return;
}

echo '
  <h1>'._G('SBGT_adminplugins').'</h1>';

$plugins = MySBPluginHelper::load();
$current_type = '';

foreach($plugins as $plugin) {
    if( $current_type!=$plugin->type ) {
        if( $current_type!='' )
            echo '
    <div class="row border-bottom">
    <div class="col-sm-6"></div>
    <div class="col-sm-6">
        <input type="hidden" name="plugin_modif" value="'.$current_type.'">
        <input type="submit" class="btn-primary"
               value="'._G('SBGT_adminplugins_prioritysubmit').'">
    </div>
    </div>
</div>
</form>';
        $current_type = $plugin->type;
        echo '
<form action="'.$httpbase.'" method="post">
<div class="content list">
  <h2 class="">'.$current_type.'</h2>';
    }
    echo '
  <div class="row">
    <a class="col-sm-10 btn-primary-light"
       href="'.$httpbase.'&amp;plugin_id='.$plugin->id.'">
        <p><i>'.$plugin->id.'</i> '.$plugin->name.'<br>
        <small><i>'._G('SBGT_adminplugins_frommodule').': ';
    if( $plugin->module!='' )  echo $plugin->module;
    else echo 'core';
    echo ' </i></small></p>
    </a>
    <label class="col-1 t-right" for="plg_prio'.$plugin->id.'">
      '._G('SBGT_adminplugins_priority').':
    </label>
    <div class="col-1">
      <input type="text" maxlength="1" class="t-right"
             name="plg_prio'.$plugin->id.'" id="plg_prio'.$plugin->id.'"
             value="'.$plugin->priority.'">
    </div>
    </div>
';
}
echo '
    <div class="row">
    <div class="col-sm-6"></div>
    <div class="col-sm-6">
        <input type="hidden" name="plugin_modif" value="'.$current_type.'">
        <input type="submit" class="btn-primary"
               value="'._G('SBGT_adminplugins_prioritysubmit').'">
    </div>
    </div>
</div>
</form>
</div>';

echo '
<div class="content">
<h1>'._G('SBGT_adminuo_add').'</h1>
<form action="'.$httpbase.'" method="post">

  <h2>'._G('SBGT_adminuo_new').'</h2>
  <div class="row label">
    <label class="col-sm-6" for="option_name">
      '._G('SBGT_plugin_name').'
    </label>
    <div class="col-sm-6">
      <input type="text" name="option_name" id="option_name">
    </div>
  </div>
  <div class="row label">
    <label class="col-md-4" for="option_text">
      '._G('SBGT_uo_text').'
    </label>
    <div class="col-md-8">
      <input type="text" name="option_text" id="option_text">
    </div>
  </div>

  <div class="row label">
    <label class="col-sm-6" for="option_type">
      '._G('SBGT_uo_type').'
    </label>
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
  </div>

  <div class="row label">
    <label class="col-sm-6" for="option_mail">
      '._G('SBGT_uo_mailnotify').'
    </label>
    <div class="col-sm-6">
      <input type="email" name="option_mail" id="option_mail">
    </div>
  </div>

  <div class="row label">
    <label class="col-12" for="option_useredit">
      <input type="checkbox" class="mysbValue-checkbox"
             name="option_useredit" id="option_useredit">
      '._G('SBGT_uo_useredition').'
    </label>
  </div>

  <div class="row">
    <div class="col-sm-3"></div>
    <div class="col-sm-6">
        <input type="hidden" name="option_add" value="1">
        <input type="submit" class="btn-danger"
               value="'._G('SBGT_adminuo_newsubmit').'">
    </div>
    <div class="col-sm-3"></div>
  </div>

</form>
</div>';

$pluginsUserOption = MySBPluginHelper::loadByType('UserOption');
echo '
<div class="content">
<h1>'._G('SBGT_exportuo').'</h1>
<form action="'.$httpbase.'" method="post">

  <h2>'._G('SBGT_exportuo_selection').'</h2>';
foreach($pluginsUserOption as $plugin) {
    $pname = $plugin->value0;
    echo '
  <div class="row label">
    <label class="col-12" for="uo_'.$plugin->id.'">';
    if($plugin->module!='') {
        $module = MySBModuleHelper::getByName($plugin->module);
        if(!$module->isLoaded())
            echo '
      <div class="mysbValue-checkbox">
        <i>(module '.$plugin->module.' not loaded)</i>
      <div>';
        else
            echo '
      <input type="checkbox" class="mysbValue-checkbox"
             name="uo_'.$plugin->id.'" id="uo_'.$plugin->id.'">';
    } else
        echo '
      <input type="checkbox" class="mysbValue-checkbox"
             name="uo_'.$plugin->id.'" id="uo_'.$plugin->id.'">';
    echo '
      '.$pname.'<br>
      <span class="help">'._G($plugin->value1).'</span>
    </label>
  </div>';
}
echo '
  <div class="row">
    <div class="col-sm-3"></div>
    <div class="col-sm-6">
        <input type="hidden" name="option_export" value="1">
        <input type="submit" class="btn-primary"
               value="'._G('SBGT_exportuo_submit').'">
    </div>
    <div class="col-sm-3"></div>
  </div>

</form>
</div>';

?>

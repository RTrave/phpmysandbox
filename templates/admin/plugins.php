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


_incI('admin/menu');


echo '
<h1>'._G('SBGT_adminplugins').'</h1>';



if( isset($_GET['plugin_id']) ) {
    $current_plugin = MySBPluginHelper::getByID($_GET['plugin_id']);
    $roles = MySBRoleHelper::load();
    echo '
<form action="index.php?tpl=admin/plugins&amp;plugin_id='.$current_plugin->id.'" method="post">
<div class="boxed">
    <div class="title roundtop"><b>'.$current_plugin->id.': '.$current_plugin->name.'</b></div>
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
</form>';
    return;
}


$plugins = MySBPluginHelper::load();
$current_type = '';

foreach($plugins as $plugin) {
    if( $current_type!=$plugin->type ) {
        if( $current_type!='' ) 
            echo '
    <div class="row" style="text-align: center;">
        <input type="hidden" name="plugin_modif" value="'.$current_type.'">
        <input type="submit" value="'._G('SBGT_adminplugins_prioritysubmit').'">
    </div>
</div>
</form>';
        $current_type = $plugin->type;
        echo '
<form action="index.php?tpl=admin/plugins" method="post">
<div class="boxed">
    <div class="title roundtop"><b>'.$current_type.'</b></div>';
    }
    echo '
    <div class="row">
        <div class="right">'._G('SBGT_adminplugins_priority').':<input type="text" size="1" maxlength="1" name="plg_prio'.$plugin->id.'" value="'.$plugin->priority.'"></div>
        <div style="float: left;">
        <a  class="overlayedA"
            href="index.php?tpl=admin/plugins&amp;plugin_id='.$plugin->id.'">
            <img    src="images/icons/text-editor.png" 
                    alt="'._G('SBGT_edit').' '.$plugin->name.'" 
                    title="'._G('SBGT_edit').' '.$plugin->name.'"></a></div>
        <i>'.$plugin->id.'</i> '.$plugin->name.'<br>
        <small><i>'._G('SBGT_adminplugins_frommodule').': ';
    if( $plugin->module!='' )  echo $plugin->module;
    else echo 'core';
    echo ' </i></small>
    </div>';
}
echo '
    <div class="row" style="text-align: center;">
        <input type="hidden" name="plugin_modif" value="'.$current_type.'">
        <input type="submit" value="'._G('SBGT_adminplugins_prioritysubmit').'">
    </div>
</div>
</form>';

echo '
<h2>'._G('SBGT_adminuo_add').'</h2>
<div class="boxed">
<form action="index.php?tpl=admin/plugins" method="post">

    <div class="title roundtop"><b>'._G('SBGT_adminuo_new').'</b></div>
    <div class="row">
        <div class="right"><input type="text" name="option_name"></div>
        '._G('SBGT_uo_keyname').'
    </div>
    <div class="row">
        <div class="right"><input type="text" name="option_text"></div>
        '._G('SBGT_uo_text').'
    </div>
    <div class="row">
        <div class="right">
            <select name="option_type">
	            <option value="'.MYSB_VALUE_TYPE_INT.'">INT</option>
	            <option value="'.MYSB_VALUE_TYPE_BOOL.'">BOOL</option>
	            <option value="'.MYSB_VALUE_TYPE_VARCHAR64.'">VARCHAR64</option>
	            <option value="'.MYSB_VALUE_TYPE_VARCHAR512.'">VARCHAR512</option>
	            <option value="'.MYSB_VALUE_TYPE_TEXT.'">TEXT</option>
	            <option value="'.MYSB_VALUE_TYPE_VARCHAR64_SELECT.'">VARCHAR64_SELECT</option>
	        </select>
	    </div>
        '._G('SBGT_uo_type').'
    </div>
    <div class="row">
        <div class="right"><input type="text" name="option_mail"></div>
        '._G('SBGT_uo_mailnotify').'
    </div>
    <div class="row">
        <div class="right"><input type="checkbox" name="option_useredit"></div>
        '._G('SBGT_uo_useredition').'
    </div>
    <div class="row" style="text-align: center;">
        <input type="hidden" name="option_add" value="1">
        <input type="submit" value="'._G('SBGT_adminuo_newsubmit').'">
    </div>

</form>
</div>';

$pluginsUserOption = MySBPluginHelper::loadByType('UserOption');
echo '
<h2>'._G('SBGT_exportuo').'</h2>
<div class="boxed">
<form action="index.php?tpl=admin/plugins" method="post">

    <div class="title roundtop"><b>'._G('SBGT_exportuo_selection').'</b></div>';
foreach($pluginsUserOption as $plugin) {
    $pname = $plugin->value0;
    echo '
    <div class="row">
        <div class="right">';
    if($plugin->module!='') {
        $module = MySBModuleHelper::getByName($plugin->module);
        if(!$module->isLoaded()) 
            echo '<i>(module '.$plugin->module.' not loaded)</i>';
        else 
            echo '<input type="checkbox" name="uo_'.$plugin->id.'">';
    } else 
        echo '<input type="checkbox" name="uo_'.$plugin->id.'">';
    echo '</div>
        '.$pname.'<br>
        <span class="help">'._G($plugin->value1).'</span>
    </div>';
}
echo '
    <div class="row" style="text-align: center;">
        <input type="hidden" name="option_export" value="1">
        <input type="submit" value="'._G('SBGT_exportuo_submit').'">
    </div>

</form>
</div>';

?>

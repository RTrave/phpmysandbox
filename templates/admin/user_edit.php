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

if(!MySBRoleHelper::checkAccess('admin')) return;


if( !isset($_GET['user_id']) )
    exit;

//$user = new MySBUser($_GET['user_id']);
$user = $app->data['user'];
$groups = MySBGroupHelper::load();

if( isset($_POST['user_delete']) and $_POST['user_delete']==1 ) {
    echo '
<script>
hide("user'.$_GET['user_id'].'");
desactiveOverlay();
</script>';
    return;
}
if( isset($_POST['user_edition']) and $_POST['user_edition']==1 ) {
    echo '
<script>
loadItem("user'.$user->id.'","index.php?inc=admin/user_display&user_id='.$user->id.'");
</script>';
    //return;
}

echo '
<div class="overlaySize" 
    data-overheight=""
    data-overwidth="460"></div>

<div class="overHead">';
if( $user->id!=0 and $user->id!=1 )
    echo '
        <form action="index.php?tpl=admin/user_edit&amp;user_id='.$user->id.'" 
              method="post"
              class="hidelayed"
              data-overconfirm="'.MySBUtil::str2strict(_G('SBGT_adminuser_confirm_delete')).'">
    <div class="action first">
            <input type="hidden" name="user_delete" value="1">
            <input src="images/icons/user-trash.png"
                   type="image"
                   alt="'._G('SBGT_adminusers_delete').' '.$user->lastname.' '.$user->firstname.'"
                   title="'._G('SBGT_adminusers_delete').' '.$user->lastname.' '.$user->firstname.'">
    </div>
            </form>
        <form action="index.php?tpl=admin/user_edit&amp;user_id='.$user->id.'" 
              method="post"
              class="overlayed"
              data-overconfirm="'.MySBUtil::str2strict(_G('SBGT_adminuser_confirm_newpasswd')).'">
    <div class="action first">
            <input type="hidden" name="user_newpasswd" value="1">
            <input src="images/icons/dialog-password.png"
                   type="image"
                   alt="'._G('SBGT_adminusers_newpasswd').' '.$user->lastname.' '.$user->firstname.'"
                   title="'._G('SBGT_adminusers_newpasswd').' '.$user->lastname.' '.$user->firstname.'">
    </div>
            </form>
';
echo '
    '.$user->lastname.' '.$user->firstname.'<br>
    <small><i>'.$user->login.'</i></small>
</div>

<form   action="index.php?tpl=admin/user_edit&amp;user_id='.$user->id.'" 
        method="post"
        class="overlayed">

<div class="overBody">
<div class="list_support">

<div class="row">
    <div class="right"><input type="text" name="lastname" value="'.$user->lastname.'"></div>
    '._G('SBGT_lastname').'
</div>
<div class="row">
    <div class="right"><input type="text" name="firstname" value="'.$user->firstname.'"></div>
    '._G('SBGT_firstname').'
</div>
<div class="row">
    <div class="right"><input type="email" name="mail" value="'.$user->mail.'"></div>
    '._G('SBGT_mail').'
</div>
<div class="row">
'._G('SBGT_adminusers_groups').'<br>';
foreach( $groups as $group ) {
    if( $user->login=='admin' and $group->id==0 ) {
        echo '
    <div style="display: inline-block; background: #dddddd; margin: 2px 3px; padding: 1px 3px;">
        <input type="hidden" name="isingroup_0" value="on"><input type="checkbox" disabled="disabled" checked="checked"> <i>'.$group->comments.'</i>
    </div>';
    } else {
        $group_nid = 'g'.$group->id;
        echo '
    <div style="display: inline-block; background: #dddddd; margin: 2px 3px; padding: 1px 3px;">
        <input type="checkbox" name="isingroup_'.$group->id.'" '.MySBUtil::form_ischecked($user->$group_nid,1).'> <i>'.$group->comments.'</i>
    </div>';
    }
}
echo '    
</div>';

$pluginsUserOption = MySBPluginHelper::loadByType('UserOption');
if(count($pluginsUserOption)>=1) {
        foreach($pluginsUserOption as $plugin) {
                $pname = $plugin->value0;
                echo '<div class="row"><div class="right">';
                if($plugin->module!='') {
                    $module = MySBModuleHelper::getByName($plugin->module);
                    if(!$module->isLoaded()) {
                        echo $user->$pname.' <i>(module '.$plugin->module.' not loaded)</i>';
                    } else echo $plugin->formDisplay($user);
                } else echo $plugin->formDisplay($user);
                echo '</div><b>'.$pname.'</b><br>'._G($plugin->value1).'</div>';
        }
}
echo '

</div>
</div>

<div class="overFoot">
    <input type="hidden" name="user_edition" value="1">
    <input type="submit" value="'._G('SBGT_adminusers_submit').'"
        class="action" style="width: 100%;">
</div>

</form>';

?>

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


if( isset($_POST['group_delete']) ) {
    $group = MySBGroupHelper::getByID( $_POST['group_id'] );
    $app->pushMessage( _G('SBGT_admingroups_deletemsg').':<br>'.$group->name );
    MySBGroupHelper::delete( $group->name );
    echo '
<script>
hide("group'.$_POST['group_id'].'");
</script>';
    return;
}

include( _pathI('admin/menu') );

echo '
<h1>'._G('SBGT_admingroups').'</h1>

<div class="list_support">';

$groups = MySBGroupHelper::load();
$roles = MySBRoleHelper::load();

foreach( $groups as $group ) {
    echo '
<div class="boxed" id="group'.$group->id.'">
    <div class="title roundtop" style="text-align: left;">
        <div style="float: right; cursor: pointer;">
            <img src="images/icons/go-down.png" alt="go-down"
                 onClick="toggle_slide(\'group_edit_'.$group->id.'\');"></div>';
    if( $group->id!=0 )
        echo '
    <div style="float: right; margin-right: 12px;">
        <form action="index.php?tpl=admin/groups" 
              method="post"
              class="hidelayed"
              data-overconfirm="'.MySBUtil::str2strict(_G('SBGT_admingroups_confirm_delete')).': '.$group->name.'">
            <input type="hidden" name="group_delete" value="1">
            <input type="hidden" name="group_id" value="'.$group->id.'">
            <input src="images/icons/user-trash.png"
                   type="image"
                   alt="'._G('SBGT_admingroups_delete').' '.$group->name.'"
                   title="'._G('SBGT_admingroups_delete').' '.$group->name.'">
        </form>
    </div>';
    echo '
        <b>'.$group->name.'</b> <i>("'.$group->comments.'", id='.$group->id.')</i>
    </div>
    <div id="group_edit_'.$group->id.'" style="display: none; width: 100%; height: 100%;">
    <form action="index.php?tpl=admin/groups" method="post">
    <div class="row">
        <div class="right"><input type="text" name="g'.$group->id.'_name" value="'.$group->name.'"></div>
        '._G('SBGT_admingroups_name').'
    </div>
    <div class="row">
        <div class="right"><input type="text" name="g'.$group->id.'_comments" value="'.$group->comments.'"></div>
        '._G('SBGT_admingroups_comments').'
    </div>
    <div class="row">
        '._G('SBGT_admingroups_roles').'<br>';
    foreach( $roles as $role ) {
        if( $role->isAssignToGroup($group) ) $checked = 'checked="checked"';
        else $checked = '';
        echo '
        <div style="display: inline-block; background: #dddddd; margin: 2px 3px; padding: 1px 3px;">
            <input type="checkbox" name="r'.$role->id.'_isassignto_g'.$group->id.'" '.$checked.'> <i>'.$role->comments.'</i>
        </div>';
    }
    echo '        
    </div>
    <div class="row" style="text-align: center;">
        <input type="hidden" name="group_edit" value="1">
        <input type="hidden" name="group_id" value="'.$group->id.'">
        <input type="submit" value="'._G('SBGT_admingroups_submit').': '.$group->name.'">
    </div>
    </form>
    </div>';
    echo '
</div>';
}

echo '

<br>
<br>

<form action="index.php?tpl=admin/groups" method="post">
<div class="boxed">
    <div class="title roundtop"><b>'._G('SBGT_admingroups_new').'</b></div>
    <div class="row">
        <div class="right"><input type="text" name="group_name" value=""></div>
        '._G('SBGT_admingroups_name').'
    </div>
    <div class="row">
        <div class="right"><input type="text" name="group_comments" value=""></div>
        '._G('SBGT_admingroups_comments').'
    </div>
    <div class="row" style="text-align: center;">
        <input type="hidden" name="group_add" value="1">
        <input type="submit" value="'._G('SBGT_admingroups_newsubmit').'">
    </div>
</div>
</form>

</div>';

?>

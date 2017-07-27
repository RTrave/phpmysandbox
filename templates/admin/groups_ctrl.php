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


if( isset($_POST['group_add']) ) {
    $group = MySBGroupHelper::create($_POST['group_name'], $_POST['group_comments']); // need $is_default
    $app->pushMessage( _G('SBGT_admingroups_newmsg').':<br>'.$group->name );
}

if( isset($_POST['group_edit']) ) {
    $group = MySBGroupHelper::getByID( $_POST['group_id'] );
    $group->update( array(
        'name' => $_POST['g'.$group->id.'_name'],
        'comments' => $_POST['g'.$group->id.'_comments'] ) );
    $roles = MySBRoleHelper::load();
    foreach( $roles as $role ) {
        if( isset($_POST['r'.$role->id.'_isassignto_g'.$group->id]) and $_POST['r'.$role->id.'_isassignto_g'.$group->id]=='on' ) 
            $role->assignToGroup( $group->name, true );
        else $role->assignToGroup( $group->name, false );
    }
}

include( _pathT('admin/groups') );

?>

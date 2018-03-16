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

$httpbase = 'index.php?tpl=admin/admin&amp;page=groups';

if( isset($_GET['group_delete']) ) {
    $group = MySBGroupHelper::getByID( $_GET['group_delete'] );
    $app->pushMessage( _G('SBGT_admingroups_deletemsg').':<br>'.$group->name );
    MySBGroupHelper::delete( $group->name );
    echo '
<script>
hide("group'.$_GET['group_delete'].'");
</script>';
    return;
}

echo '
<div class="content">

<h1>'._G('SBGT_admingroups').'</h1>
';

$groups = MySBGroupHelper::load();
$roles = MySBRoleHelper::load();

foreach( $groups as $group ) {
    echo '
<div id="group'.$group->id.'">

  <div class="content list">
  <div class="row">
    <a class="col-auto btn-primary-light" href="javascript:void(0)"
       onClick="slide_toggle(\'group_edit_'.$group->id.'\');">
      <p><img src="images/icons/go-down.png" alt="go-down"
              style="position: absolute; right: 0;">
        <b>'.$group->name.'</b><br>
        <span class="help">'.$group->comments.', id='.$group->id.'</span>
      </p>
    </a>';
    if( $group->id!=0 )
        echo '
  <a class="hidelayed col-1 t-center btn-danger-light"
     href="'.$httpbase.'&amp;group_delete='.$group->id.'"
     data-overconfirm="'.MySBUtil::str2strict(_G('SBGT_admingroups_confirm_delete')).': '.$group->name.'"
     title="'._G('SBGT_admingroups_delete').' '.$group->name.'">
    <img src="images/icons/user-trash.png" alt="user-trash">
  </a>';
    echo '
  </div>
  </div>

  <div id="group_edit_'.$group->id.'" class="slide"
       style="width: 100%;">
  <form action="'.$httpbase.'" method="post">

  <div class="row label">
    <label class="col-sm-4" for="g'.$group->id.'_name">
      '._G('SBGT_admingroups_name').'
    </label>
    <div class="col-sm-8">
      <input type="text" name="g'.$group->id.'_name" id="g'.$group->id.'_name"
             value="'.$group->name.'">
    </div>
  </div>

  <div class="row label">
    <label class="col-sm-4" for="g'.$group->id.'_comments">
      '._G('SBGT_admingroups_comments').'
    </label>
    <div class="col-sm-8">
      <input type="text" name="g'.$group->id.'_comments" id="g'.$group->id.'_comments"
             value="'.$group->comments.'">
    </div>
  </div>

  <h2>'._G('SBGT_admingroups_roles').'</h2>
  <div class="row checkbox-list">';
    foreach( $roles as $role ) {
        if( $role->isAssignToGroup($group) ) $checked = 'checked="checked"';
        else $checked = '';
        echo '
    <label for="r'.$role->id.'_isassignto_g'.$group->id.'">
      <input type="checkbox" name="r'.$role->id.'_isassignto_g'.$group->id.'"
                   '.$checked.' id="r'.$role->id.'_isassignto_g'.$group->id.'">
      <i>'.$role->comments.'</i>
    </label>';
    }
    echo '
  </div>
  <div class="row">
    <div class="col-sm-6"></div>
    <div class="col-sm-6">
      <input type="hidden" name="group_edit" value="1">
      <input type="hidden" name="group_id" value="'.$group->id.'">
      <input type="submit" class="btn-primary"
             value="'._G('SBGT_admingroups_submit').': '.$group->name.'">
    </div>
  </div>
  </form>
  </div>';
    echo '
</div>';
}

echo '
</div>

<div class="content">
<form action="'.$httpbase.'" method="post">
  <h1>'._G('SBGT_admingroups_new').'</h1>
  <div class="row label">
    <label class="col-sm-4" for="group_name">
      '._G('SBGT_admingroups_name').'
    </label>
    <div class="col-sm-8">
      <input type="text" name="group_name" id="group_name" value="">
    </div>
  </div>

  <div class="row label">
    <label class="col-sm-4" for="group_comments">
        '._G('SBGT_admingroups_comments').'
    </label>
    <div class="col-sm-8">
        <input type="text" name="group_comments" id="group_comments" value="">
    </div>
  </div>

  <div class="row label">
    <div class="col-sm-3"></div>
    <div class="col-sm-6">
        <input type="hidden" name="group_add" value="1">
        <input type="submit" class="btn-primary"
               value="'._G('SBGT_admingroups_newsubmit').'">
    </div>
    <div class="col-sm-3"></div>
  </div>
</form>

</div>';

?>

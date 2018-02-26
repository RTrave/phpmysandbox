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
?>

<div class="row">

<?php include( _pathI('admin/menu') );

echo '

<div class="col-lg-9">

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
    <a class="col-auto btn" href="javascript:void(0)"
       onClick="toggle_slide(\'group_edit_'.$group->id.'\');">
      <p><img src="images/icons/go-down.png" alt="go-down"
              style="position: absolute; right: 0;">
        <b>'.$group->name.'</b><br>
        <span class="help">'.$group->comments.', id='.$group->id.'</span>
      </p>
    </a>';
    if( $group->id!=0 )
        echo '
  <a class="hidelayed col-1 t-center btn danger"
     href="index.php?tpl=admin/groups&amp;group_delete='.$group->id.'"
     data-overconfirm="'.MySBUtil::str2strict(_G('SBGT_admingroups_confirm_delete')).': '.$group->name.'">
    <img src="images/icons/user-trash.png"
         alt="'._G('SBGT_admingroups_delete').' '.$group->name.'"
         title="'._G('SBGT_admingroups_delete').' '.$group->name.'">
  </a>';
    echo '
  </div>
  </div>

  <div id="group_edit_'.$group->id.'" style="display: none; width: 100%; height: 100%;">
  <form action="index.php?tpl=admin/groups" method="post">

  <label class="row" for="g'.$group->id.'_name">
    <p class="col-sm-4">
        '._G('SBGT_admingroups_name').'
    </p>
    <div class="col-sm-8">
      <input type="text" name="g'.$group->id.'_name" id="g'.$group->id.'_name"
             value="'.$group->name.'">
    </div>
  </label>

  <label class="row" for="g'.$group->id.'_comments">
    <p class="col-sm-4">
        '._G('SBGT_admingroups_comments').'
    </p>
    <div class="col-sm-8">
      <input type="text" name="g'.$group->id.'_comments" id="g'.$group->id.'_comments"
             value="'.$group->comments.'">
    </div>
  </label>

  <div class="row checkbox-list">
    <p>'._G('SBGT_admingroups_roles').'
    </p>';
    foreach( $roles as $role ) {
        if( $role->isAssignToGroup($group) ) $checked = 'checked="checked"';
        else $checked = '';
        echo '
    <label for="r'.$role->id.'_isassignto_g'.$group->id.'">
      <p><i>'.$role->comments.'</i></p>
      <input type="checkbox" name="r'.$role->id.'_isassignto_g'.$group->id.'"
                   '.$checked.' id="r'.$role->id.'_isassignto_g'.$group->id.'">
    </label>';
    }
    echo '
  </div>
  <div class="row">
    <div class="col-sm-6"></div>
    <div class="col-sm-6">
      <input type="hidden" name="group_edit" value="1">
      <input type="hidden" name="group_id" value="'.$group->id.'">
      <input type="submit" value="'._G('SBGT_admingroups_submit').': '.$group->name.'">
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
<form action="index.php?tpl=admin/groups" method="post">
  <h1>'._G('SBGT_admingroups_new').'</h1>
  <label class="row" for="group_name">
    <p class="col-sm-4">
      '._G('SBGT_admingroups_name').'
    </p>
    <div class="col-sm-8">
      <input type="text" name="group_name" id="group_name" value="">
    </div>
  </label>

  <label class="row" for="group_comments">
    <p class="col-sm-4">
        '._G('SBGT_admingroups_comments').'
    </p>
    <div class="col-sm-8">
        <input type="text" name="group_comments" id="group_comments" value="">
    </div>
  </label>

  <div class="row">
    <div class="col-sm-3"></div>
    <div class="col-sm-6">
        <input type="hidden" name="group_add" value="1">
        <input type="submit" value="'._G('SBGT_admingroups_newsubmit').'">
    </div>
    <div class="col-sm-3"></div>
  </div>
</form>

</div>
</div>
</div>';

?>

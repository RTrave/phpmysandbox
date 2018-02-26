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

if( isset($_GET['user_delete']) and $_GET['user_delete']==1 ) {
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
?>

<div class="overlaySize1"
    data-overheight=""
    data-overwidth="460"></div>

<form   action="index.php?tpl=admin/user_edit&amp;user_id=<?= $user->id ?>"
        method="post"
        class="overlayed">

<div class="modalContent">

<div class="modalTitle">

<?php if( $user->id!=0 and $user->id!=1 ) { ?>
  <a class="hidelayed col-1 t-center btn danger"
     href="index.php?tpl=admin/user_edit&amp;user_id=<?= $user->id ?>&amp;user_delete=1"
     data-overconfirm="<?= MySBUtil::str2strict(_G('SBGT_adminuser_confirm_delete')) ?>">
    <img src="images/icons/user-trash.png"
         alt="<?= _G('SBGT_adminusers_delete') ?> <?= $user->lastname ?> <?= $user->firstname ?>"
         title="<?= _G('SBGT_adminusers_delete') ?> <?= $user->lastname ?> <?= $user->firstname ?>"
         style="width1: 24px">
  </a>
<?php } ?>
  <a class="hidelayed col-1 t-center btn"
     href="index.php?tpl=admin/user_edit&amp;user_id=<?= $user->id ?>&amp;user_newpasswd=1"
     title="<?= _G('SBGT_adminusers_newpasswd') ?> <?= $user->lastname ?> <?= $user->firstname ?>"
     data-overconfirm="<?= MySBUtil::str2strict(_G('SBGT_adminuser_confirm_newpasswd')) ?>">
    <img src="images/icons/dialog-password.png"
         alt="<?= _G('SBGT_adminusers_newpasswd') ?> <?= $user->lastname ?> <?= $user->firstname ?>">
  </a>
  <div class="col-auto">
    <p><b><?= $user->lastname ?></b> <?= $user->firstname ?><br>
      <i><?= $user->login ?> <small>(ID:<?= $user->id ?>)</small></i></p>
  </div>

</div>

<div class="modalBody">

<div class="row">
  <label for="lastname">
  <p class="col-sm-4">
    <?= _G('SBGT_lastname') ?>
  </p>
  <div class="col-sm-8">
    <input type="text" name="lastname" id="lastname"
           value="<?= $user->lastname ?>">
  </div>
  </label>
</div>
<div class="row">
  <label for="firstname">
  <p class="col-sm-4">
    <?= _G('SBGT_firstname') ?>
  </p>
  <div class="col-sm-8">
    <input type="text" name="firstname" id="firstname"
           value="<?= $user->firstname ?>">
  </div>
  </label>
</div>
<div class="row">
  <label for="mail">
  <p class="col-sm-4">
    <?= _G('SBGT_mail') ?>
  </p>
  <div class="col-sm-8">
    <input type="email" name="mail" id="mail"
           value="<?= $user->mail ?>">
  </div>
  </label>
</div>

<?php
$pluginsUserOption = MySBPluginHelper::loadByType('UserOption');
if(count($pluginsUserOption)>=1) {
  echo '
  <h2 class="border-top">
    '._G('SBGT_user_options').'
  </h2>';
        foreach($pluginsUserOption as $plugin) {
                $pname = $plugin->value0;
                echo '
<label class="row" for="'.$plugin->formDisplayId().'">
  <p class="col-sm-8">
                <b>'.$pname.'</b><br>'._G($plugin->value1).'
  </p>
  <div class="col-sm-4">
';
                echo '';
                if($plugin->module!='') {
                    $module = MySBModuleHelper::getByName($plugin->module);
                    if(!$module->isLoaded()) {
                        echo '<p>'.$user->$pname.' <i>(module '.$plugin->module.' not loaded)</i></p>';
                    } else echo $plugin->formDisplay($user);
                } else echo $plugin->formDisplay($user);
                echo '
  </div>
</label>';
        }
}
?>


<?php
echo '
<h2 class="border-top">'._G('SBGT_adminusers_groups').'<h2>
<div class="row checkbox-list">';
foreach( $groups as $group ) {
    if( $user->login=='admin' and $group->id==0 ) {
        echo '
  <label style1="display: inline-block; background: #dddddd; margin: 2px 3px; padding: 1px 3px;">
    <p><i>'.$group->comments.'</i></p>
    <input type="hidden" name="isingroup_0" value="on">
    <input type="checkbox" disabled="disabled" checked="checked">
  </label>';
    } else {
        $group_nid = 'g'.$group->id;
        echo '
  <label style1="display: inline-block; background: #dddddd; margin: 2px 3px; padding: 1px 3px;"
         for="isingroup_'.$group->id.'">
    <p><i>'.$group->comments.'</i></p>
    <input type="checkbox" name="isingroup_'.$group->id.'" id="isingroup_'.$group->id.'"
           '.MySBUtil::form_ischecked($user->$group_nid,1).'>
  </label>';
    }
}
echo '
</div>';


echo '

</div>

</div>

<div class="modalFoot">
  <div class="overlayed col-12 t-center btn"
     href="index.php?tpl=admin/user_edit&amp;=<?= $user->id ?>">
    <input type="hidden" name="user_edition" value="1">
    <input type="submit" value="'._G('SBGT_adminusers_submit').'"
        class="action" style="width: 100%;">
  </div>
</div>

</form>';

?>

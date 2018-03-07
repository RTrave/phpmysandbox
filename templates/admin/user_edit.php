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
</script>';
    return;
}
if( isset($_GET['user_newpasswd']) and $_GET['user_newpasswd']==1 ) {
    return;
}
if( isset($_POST['user_edition']) and $_POST['user_edition']==1 ) {
    echo '
<script>
loadItem("user'.$user->id.'","index.php?inc=admin/user_display&user_id='.$user->id.'");
</script>';
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
  <a class="hidelayed col-1 t-center btn-danger-light"
     href="index.php?tpl=admin/user_edit&amp;user_id=<?= $user->id ?>&amp;user_delete=1"
     data-overconfirm="<?= MySBUtil::str2strict(_G('SBGT_adminuser_confirm_delete')) ?>"
     title="<?= _G('SBGT_adminusers_delete') ?> <?= $user->lastname ?> <?= $user->firstname ?>">
    <img src="images/icons/user-trash.png"
         alt="">
  </a>
<?php } ?>
  <a class="hidelayed col-1 t-center btn-primary-light"
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

<div class="row label">
  <label class="col-sm-4" for="lastname">
    <b><?= _G('SBGT_lastname') ?></b>
  </label>
  <div class="col-sm-8">
    <input type="text" name="lastname" id="lastname"
           value="<?= $user->lastname ?>">
  </div>
</div>
<div class="row label">
  <label class="col-sm-4" for="firstname">
    <b><?= _G('SBGT_firstname') ?></b>
  </label>
  <div class="col-sm-8">
    <input type="text" name="firstname" id="firstname"
           value="<?= $user->firstname ?>">
  </div>
</div>
<div class="row label">
  <label class="col-sm-4" for="mail">
    <b><?= _G('SBGT_mail') ?></b>
  </label>
  <div class="col-sm-8">
    <input type="email" name="mail" id="mail"
           value="<?= $user->mail ?>">
  </div>
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
<div class="row label">
  <label class="col-6" for="'.$plugin->formDisplayId().'">
    <b>'._G($plugin->value1).'</b><br>
    <span class="help">'.$pname.'</span>
  </label>
  <div class="col-6">
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
</div>';
        }
}
?>


<?php
echo '
<h2 class="border-top">'._G('SBGT_adminusers_groups').'</h2>
<div class="row checkbox-list">';
foreach( $groups as $group ) {
    if( $user->login=='admin' and $group->id==0 ) {
        echo '
  <label>
    <i>'.$group->comments.'</i>
    <input type="hidden" name="isingroup_0" value="on">
    <input type="checkbox" disabled="disabled" checked="checked">
  </label>';
    } else {
        $group_nid = 'g'.$group->id;
        echo '
  <label for="isingroup_'.$group->id.'">
    <i>'.$group->comments.'</i>
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
  <div class="col-12 t-center">
    <input type="hidden" name="user_edition" value="1">
    <input type="submit" value="'._G('SBGT_adminusers_submit').'"
        class="btn-primary">
  </div>
</div>

</form>';

?>

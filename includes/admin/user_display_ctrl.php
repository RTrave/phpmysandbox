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

if( isset($_GET['user_id']) )
  $user = MySBUserHelper::getByID($_GET['user_id']);
?>

<!--
  <a class="overlayed col-1 t-center btn"
     href="index.php?tpl=admin/user_edit&amp;user_id=<?= $user->id ?>">
    <img src="images/icons/text-editor.png"
         alt="<?= _G('SBGT_edit') ?> <?= $user->lastname ?> <?= $user->firstname ?>"
         title="<?= _G('SBGT_edit') ?> <?= $user->lastname ?> <?= $user->firstname ?>"
         style="width1: 24px">
  </a>
-->
  <a class="overlayed col-auto btn"
     href="index.php?tpl=admin/user_edit&amp;user_id=<?= $user->id ?>">
  <div class="row">
    <div class="col-auto">
      <p><b><?= $user->lastname ?></b> <?= $user->firstname ?><br>
      <i><?= $user->login ?> <small>(ID:<?= $user->id ?>)</small></i></p>
    </div>

    <div class="col-3 d-show-md t-center"
         style="right: 0; position: absolute;">
<?php
$lastlogin = new MySBDateTime($user->last_login);
if( $user->last_login!='' )
  echo '<p><span class="help">'.$lastlogin->strEBY_l_whm().'</span></p>';
else
  echo '<p>-</p>';
?>
    </div>
  </div>
  </a>
<?php if( $user->mail!='' ) { ?>
  <a class="col-1 t-center btn" href="mailto:<?= $user->mail ?>">
    <img src="images/icons/mail-unread.png"
         alt="<?= _G('SBGT_mailto') ?> <?= $user->lastname ?> <?= $user->firstname ?>"
         title="<?= _G('SBGT_mailto') ?> <?= $user->lastname ?> <?= $user->firstname ?>">
  </a>
<?php } else { ?>
  <div class="col-1" style="min-width: 50px;">
    <img    src="images/blank.png"
            style="width: 32px;"
            alt="No mail for <?= $user->lastname ?> <?= $user->firstname ?>"
            title="No mail for  <?= $user->lastname ?> <?= $user->firstname ?>">
  </div>
<?php } ?>

<?php
echo '
<script>
show("user'.$user->id.'");
</script>';
?>



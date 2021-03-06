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

  <a class="overlayed col-auto btn-primary-light"
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
  <a class="col-1 t-center btn-primary-light" href="mailto:<?= $user->mail ?>"
     title="<?= _G('SBGT_mailto') ?> <?= $user->lastname ?> <?= $user->firstname ?>">
    <img src="images/icons/mail-unread.png" alt="<?= _G('SBGT_mailto') ?> <?= $user->lastname ?> <?= $user->firstname ?>">
  </a>
<?php } else { ?>
  <a class="col-1 t-center inactive" href="javascript:void(0)"
     title="blank">
    <img src="images/blank.png" alt="blank">
  </a>
<?php } ?>

<?php
echo '
<script>
slide_show("user'.$user->id.'");
</script>';
?>



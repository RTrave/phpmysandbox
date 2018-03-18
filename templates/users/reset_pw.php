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

//global $app;
?>

<div class="col-md-8 col-unique">
<div class="content">

  <h1><?= _G('SBGT_h1_resetpassword') ?></h1>

<?php if( $passwd_reset!=1 ) { ?>

<form action="index.php?tpl=users/reset_pw" method="post">

  <div class="row">
    <div class="col"><?= _G('SBGT_p_mail') ?></div>
  </div>

  <div class="row">
    <div class="col-sm-2"></div>
    <label class="col-sm-2" for="user_mail">
      mail:
    </label>
    <div class="col-sm-6">
      <input type="email" name="user_mail" id="user_mail" value="" maxlength="128">
    </div>
    <div class="col-sm-2"></div>
  </div>

  <div class="row border-top" style="text-align: center;">
    <div class="col-sm-2"></div>
    <div class="col-sm-8">
      <input type="submit" class="btn-primary"
             value="<?= _G('SBGT_submit_mail') ?>">
    </div>
    <div class="col-sm-2"></div>
  </div>

</form>
</div>
</div>

<?php } else { ?>
<p><?= _G('SBGT_mail_send') ?>: <b><i><?= $_POST['user_mail'] ?></i></b></p>';
<?php } ?>

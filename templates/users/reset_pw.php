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

<h1><?= _G('SBGT_h1_resetpassword') ?></h1>

<?php if( $passwd_reset!=1 ) { ?>

<h2><?= _G('SBGT_h2_mail') ?></h2>

<div class="list_support">

<form action="index.php?tpl=users/reset_pw" method="post">
<p><?= _G('SBGT_p_mail') ?><br>
<input type="email" name="user_mail" value="" size="32" maxlength="128"><br><br>
<input type="submit" value="<?= _G('SBGT_submit_mail') ?>">
<br>
</p>
</form>
</div>';

<?php } else { ?>
<p><?= _G('SBGT_mail_send') ?>: <b><i><?= $_POST['user_mail'] ?></i></b></p>';
<?php } ?>

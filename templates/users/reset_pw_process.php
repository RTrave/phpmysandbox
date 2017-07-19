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

$app->passwd_reset = 0;
if( isset($_POST['user_mail']) and $_POST['user_mail']!='' ) {
    if( !MySBUtil::strverif($_POST['user_mail']) )
        $app->displayStopAlert(_G('SBGT_entry_badvalues'),3);
    $req_user = MySBDB::query("SELECT * FROM ".MySB_DBPREFIX."users ".
        "WHERE mail='".$_POST['user_mail']."'",
        'reset_pw.php:');
    if(MySBDB::num_rows($req_user)!=0) {
        while($data_user = MySBDB::fetch_array($req_user)) {
            $user = new MySBUser($data_user['id']);
            if( !$user->checkMailattempt() ) return;
            $new_pw=rand(10000,99999);
            $user->resetPassword(MySBUtil::str2db($new_pw));
            $pwmail = new MySBMail('reset_pw');
            $pwmail->addTO($_POST['user_mail'],$data_user['firstname'].' '.$data_user['lastname']);
            $pwmail->data['login'] = $data_user['login'];
            $pwmail->data['password'] = $new_pw;
            $pwmail->data['geckos'] = $data_user['firstname'].' '.$data_user['lastname'];
            $pwmail->send();
            $app->pushMessage(_G('SBGT_mail_send').': '.$_POST['user_mail']);
            $app->passwd_reset = 1;
        }
    } else {
        $app->pushMessage(_G('SBGT_mail_notfound').': '.$_POST['user_mail']);
    }
}

?>

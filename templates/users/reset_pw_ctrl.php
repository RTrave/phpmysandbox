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

$passwd_reset = 0;
if( isset($_POST['user_mail']) and $_POST['user_mail']!='' ) {

    if( !MySBUtil::strverif($_POST['user_mail']) )
        $app->displayStopAlert(_G('SBGT_entry_badvalues'),3);

    $users = MySBUserHelper::getByMail($_POST['user_mail']);
    if( count($users)!=0 ) {
        foreach( $users as $user ) {
            if( !$user->checkMailattempt() ) return;
            $new_pw=rand(10000,99999);
            $user->resetPassword(MySBUtil::str2db($new_pw));
            $pwmail = new MySBMail('reset_pw');
            $pwmail->addTO($_POST['user_mail'],$user->firstname.' '.$user->lastname);
            $pwmail->data['login'] = $user->login;
            $pwmail->data['password'] = $new_pw;
            $pwmail->data['geckos'] = $user->firstname.' '.$user->lastname;
            $pwmail->send();
            //$app->pushMessage(_G('SBGT_mail_send').': '.$_POST['user_mail']);
            $passwd_reset = 1;
        }
    } else {
        $app->pushAlert(_G('SBGT_mail_notfound').': '.$_POST['user_mail']);
    }
}

include (_pathT('users/reset_pw'));

?>

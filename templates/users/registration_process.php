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

if(MySBConfigHelper::Value('registration_auto')!=true) {
    $app->pushAlert(_G('SBGT_no_autoreg'));
    return;
}

if($app->auth_user!=null) {
    $app->displayStopAlert(_G('SBGT_no_reg_byuser').
        '<br><a href="index.php">'._G('SBGT_return_home').'</a>',3);
    return;
}

$app->empty_field = 1;
if( isset($_POST['newuser_flag']) and $_POST['newuser_flag']==-1 ) {

if(!empty($_POST['newlogin']) and !empty($_POST['newlastname']) and !empty($_POST['newfirstname']) and !empty($_POST['newmail']))  {

    if( !MySBUtil::strverif($_POST['newlogin']) or 
        !MySBUtil::strverif($_POST['newlastname']) or 
        !MySBUtil::strverif($_POST['newfirstname']) or 
        !MySBUtil::strverif($_POST['newmail']) )
        $app->displayStopAlert(_G('SBGT_entry_badvalues'),3);

    $req_mail = MySBDB::query("SELECT * FROM ".MySB_DBPREFIX."users ".
        "WHERE mail='".$_POST['newmail']."'",
        'registration.php');
    if( MySBDB::num_rows($req_mail)!=0 and 
        (   MySBConfigHelper::Value('login_vs_mail')=='SBGT_loginvsmail_unique' or 
            MySBConfigHelper::Value('login_vs_mail')=='SBGT_loginvsmail_mail') ) {
        $app->pushMessage(_G('SBGT_mail_exists'));
        return;
    }
    $req_user = MySBDB::query("SELECT * FROM ".MySB_DBPREFIX."users ".
        "WHERE login='".$_POST['newlogin']."'",
        'registration.php');
    if(MySBDB::num_rows($req_user)==0) {
        $new_user = MySBUserHelper::create($_POST['newlogin'],$_POST['newlastname'],$_POST['newfirstname'],$_POST['newmail']);
        $newpassword = rand(10000,99999);
        $new_user->resetPassword($newpassword);
        $pluginsUserOption = MySBPluginHelper::loadByType('UserOption');
        foreach($pluginsUserOption as $plugin) {
            if($plugin->module!='') {
                $module = MySBModuleHelper::getByName($plugin->module);
                if(!$module->isLoaded()) continue;
            }
            if($plugin->ivalue1!=1) continue;
            $plugin->formProcess($new_user);
        }

        $numail = new MySBMail('register');
        $numail->addTO($_POST['newmail'],$_POST['newfirstname'].' '.$_POST['newlastname']);
        $numail->data['login'] = $_POST['newlogin'];
        $numail->data['password'] = $newpassword;
        $numail->data['geckos'] = $_POST['newfirstname'].' '.$_POST['newlastname'];
        $numail->send();
    
        if(MySBConfigHelper::Value('registration_notify')==true) {
            $req_getadmin = MySBDB::query('SELECT * from '.MySB_DBPREFIX."users ".
                "WHERE login='admin'",
                'registration.php');
            $data_getadmin = MySBDB::fetch_array($req_getadmin);
            $admmail = new MySBMail('register_notif');
            $admmail->addTO($data_getadmin['mail'],'Admin');
            $admmail->data['login'] = $_POST['newlogin'];
            $admmail->data['userinfos'] = $_POST['newfirstname'].' '.$_POST['newlastname'];
            $admmail->data['mail'] = $_POST['newmail'];
            $admmail->send();
        }
        $app->pushMessage(_G('SBGT_registration_ok').'<br>'._G('SBGT_hotmail_warning'));
        $app->pushAlert(_G('SBGT_registration_ok'));
        $app->empty_field = 0;

    } else {

        $app->pushMessage(_G('SBGT_login_exists'));

    }

} else {
    $app->pushMessage(_G('SBGT_registration_emptyfields'));
    $app->empty_field = 2;
}

}

?>

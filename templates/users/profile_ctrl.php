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

if( !MySBRoleHelper::checkAccess('change_profile') ) return;

if( isset($_POST['user_flag']) ) {
    if( !MySBUtil::strverif($_POST['user_lastname']) or 
        !MySBUtil::strverif($_POST['user_firstname']) or 
        !MySBUtil::strverif($_POST['user_mail']) )
        $app->displayStopAlert(_G('SBGT_entry_badvalues'),3);
    $udata = array(
        'login' => $app->auth_user->login,
         'lastname' => $_POST['user_lastname'], 
         'firstname' => $_POST['user_firstname'], 
         'mail' => $_POST['user_mail'] );
    $app->auth_user->update( $udata );
    $app->pushMessage(_G('SBGT_profile_updated'));
    $pluginsUserOption = MySBPluginHelper::loadByType('UserOption');
    foreach($pluginsUserOption as $plugin) {
        if( $plugin->module!='' ) {
            $module = MySBModuleHelper::getByName($plugin->module);
            if( !$module->isLoaded() ) continue;
        }
        if( $plugin->ivalue1==1 ) $plugin->formProcess();
    }
}

if( isset($_POST['password_flag']) ) {
    if( isset($_POST['user_password']) and $_POST['user_password']!='' ) {
        if( !MySBUtil::strverif($_POST['user_password'],false) )
            $app->displayStopAlert(_G('SBGT_entry_badvaluessql'),3);
        if( $_POST['user_password']==$_POST['user_passwordconfirm'] ) {
            $app->auth_user->resetPassword( MySBUtil::str2db($_POST['user_password']) );
            $app->pushMessage(_G('SBGT_profile_passwordupdated'));
        } else {
            $app->pushMessage(_G('SBGT_profile_passwordconfirm_nomatch'));
        }
    }
}

if( isset($_POST['deluser_flag']) ) {
    if( isset($_POST['userdel_password']) and $_POST['userdel_password']!='' ) {
        if( !MySBUtil::strverif($_POST['userdel_password'],false) )
            $app->displayStopAlert(_G('SBGT_entry_badvaluessql'),3);
        //if( MySBPluginAuthLayer::checkPassword($_POST['userdel_password']) ) {
        $user_deleted = 0;
        $pluginsAuthLayer = MySBPluginHelper::loadByType('AuthLayer');
        foreach($pluginsAuthLayer as $plugin) {
            if($plugin->checkPassword($_POST['userdel_password'])) {
                MySBUserHelper::delete($app->auth_user->id);
                $user_deleted++;
            }
        }
        if($user_deleted)
            $app->displayStopAlert('('.$user_deleted.')'._G('SBGT_profile_userdeleted'),5,false);
        else
            $app->pushMessage(_G('SBGT_profile_passwordconfirm_nomatch'));

    } else {
        $app->pushMessage(_G('SBGT_profile_passwordconfirm_nomatch'));
    }
}

include( _pathT('users/profile') );

?>

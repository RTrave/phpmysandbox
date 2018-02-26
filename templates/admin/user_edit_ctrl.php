<?php
/**
 * phpMySandBox - Simple Database Framework in PHP
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License', or
 * ('at your option) any later version. 
 * (Roman Travé <roman.trave@gmail.com>, 2012)
 */


// No direct access.
defined('_MySBEXEC') or die;

global $app;

if(!MySBRoleHelper::checkAccess('admin')) return;


if( !isset($_GET['user_id']) )
    exit;

//$user = new MySBUser($_GET['user_id']);
$user = MySBUserHelper::getByID($_GET['user_id']);
$app->data['user'] = $user;

if( isset($_GET['user_delete']) and $_GET['user_delete']==1 ) {
    $app->pushMessage( _G('SBGT_adminuser_delete').':<br>'.$user->lastname." (".$user->login.")" );
    MySBUserHelper::delete($_GET['user_id']);
    //return;
}

if( isset($_GET['user_newpasswd']) AND $_GET['user_newpasswd']==1) {
    $new_pw=rand(10000,99999);
    $user->resetPassword($new_pw);
    if( $user->mail!='' ) {
        $numail = new MySBMail('register');
        $numail->addTO($user->mail,$user->firstname.' '.$user->lastname);
        $numail->data['login'] = $user->login;
        $numail->data['password'] = $new_pw;
        $numail->data['geckos'] = $user->firstname.' '.$user->lastname;
        $numail->send();
    } else {
        $app->pushMessage( "PASSWORD:".$new_pw );
    }

}

$groups = MySBGroupHelper::load();

if( isset($_POST['user_edition']) and $_POST['user_edition']==1 ) {
    $uarray = array( 
        'login' => $user->login,
        'lastname' => $_POST['lastname'], 
        'firstname' => $_POST['firstname'], 
        'mail' => $_POST['mail'] );
    $user->update($uarray);
    $pluginsUserOption = MySBPluginHelper::loadByType('UserOption');
    foreach($pluginsUserOption as $plugin) {
        if($plugin->module!='') {
            $module = MySBModuleHelper::getByName($plugin->module);
            if(!$module->isLoaded()) continue;
        }
        $plugin->formProcess($user);
    }
    $groupmod = array();
    foreach($groups as $group) {
        if( isset($_POST['isingroup_'.$group->id]) and $_POST['isingroup_'.$group->id]=='on' )
            $res=true;
        else $res=false;
        $groupmod[] = array($group,$res);
    }
    //print_r($groupmod_values);
    $user->assignToGroups($groupmod);
    $app->pushMessage( _G('SBGT_adminuser_modif').':<br>'.$user->login );
}

include( _pathT('admin/user_edit') );

?>

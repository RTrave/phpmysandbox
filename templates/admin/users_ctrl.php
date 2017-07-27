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
global $groups_a;

if(!MySBRoleHelper::checkAccess('admin')) return;

if( isset($_POST['user_add']) AND isset($_POST['user_login']) AND isset($_POST['user_mail'])) {
    $new_user = MySBUserHelper::create($_POST['user_login'],
        $_POST['user_lastname'], $_POST['user_firstname'], $_POST['user_mail']);
    $new_pw=rand(10000,99999);
    $new_user->resetPassword($new_pw);
    if( $_POST['user_mail']!='' ) {
        $numail = new MySBMail('register');
        $numail->addTO($_POST['user_mail'],$_POST['user_firstname'].' '.$_POST['user_lastname']);
        $numail->data['login'] = $_POST['user_login'];
        $numail->data['password'] = $new_pw;
        $numail->data['geckos'] = $_POST['user_firstname'].' '.$_POST['user_lastname'];
        $numail->send();
    } else {
        $app->pushMessage( "PASSWORD:".$new_pw );
    }
    $_POST['bylogin'] = $_POST['user_login'];
    $app->pushMessage( _G('SBGT_adminusers_newmsg').':<br>'.$new_user->login.' &lt;'.$new_user->mail.'&gt;' );
}

$bylogin = '';
$bylastname = '';
$bymail = '';
if( isset($_POST['users_search']) ) {
    $users_whereclause = '';
    if( $_POST['bylogin']!='' ) {
        $found_users = MySBUserHelper::searchBy($_POST['bylogin']);
        $bylogin = $_POST['bylogin'];
    } elseif( $_POST['bylastname']!='' ) {
        $found_users = MySBUserHelper::searchBy($_POST['bylastname'],'lastname');
        $bylastname = $_POST['bylastname'];
    } elseif( $_POST['bymail']!='' ) {
        $found_users = MySBUserHelper::searchBy($_POST['bymail'],'mail');
        $bymail = $_POST['bymail'];
    } else
        $found_users = MySBUserHelper::searchBy('');
}

include( _pathT('admin/users') );

?>

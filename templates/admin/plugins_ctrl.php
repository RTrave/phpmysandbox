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


if( isset($_POST['plugin_edit_process']) ) {
    $plugin = MySBPluginHelper::getByID($_POST['plugin_edit_process']);
    $plugin->update(array(
        'name' =>$_POST['plg_name'] ,
        'role' =>$_POST['plg_role'] ,
        'priority' => $_POST['plg_priority'] ));
    $plugin->html_valueprocess();
}


if(isset($_POST['plugin_modif'])) {
    $plugins = MySBPluginHelper::loadByType($_POST['plugin_modif']);
    foreach($plugins as $plugin) {
        //echo $plugin->id.':'.$_POST['plg_prio'.$plugin->id].'<br>';
        $plugin->update(array('priority'=>$_POST['plg_prio'.$plugin->id]));
    }

}

if( isset($_POST['option_add']) ) {
    if( isset($_POST['option_useredit']) and
        $_POST['option_useredit']=='on' )
        $useredit = 1;
    else
        $useredit = 0;
    $new_id = MySBDB::lastID('plugins')+1;
    //$_POST['option_name'] = str_replace( ' ', '_', $_POST['option_name'] );
    $option_name = 'UserOption'.$new_id;
    MySBPluginHelper::create($option_name,'UserOption',
            array( $_POST['option_name'],
                   MySBUtil::str2db($_POST['option_text']),
                   $_POST['option_mail'],
                   ''),
            array( $_POST['option_type'],$useredit,0,0),
            5,"",'');
    $app->pushMessage( _G('SBGT_adminuo_msg').':<br>'.$option_name );
}

if( isset($_POST['option_export']) and $_POST['option_export']==1 ) {

    $export_req = 'SELECT * FROM '.MySB_DBPREFIX.'users';
    $export_reqwhere = '';
    $pluginsUserOption = MySBPluginHelper::loadByType('UserOption');
    foreach($pluginsUserOption as $plugin) {
        $pname = $plugin->value0;
        if( isset($_POST['uo_'.$plugin->id.'']) and $_POST['uo_'.$plugin->id.'']!='' ) {
            if( $export_reqwhere!='' ) $export_reqwhere .= ' or ';
            $export_reqwhere .= $plugin->value0."!=''";
        }
    }
    if( $export_reqwhere!='' ) $export_req .= ' WHERE ('.$export_reqwhere.')';
    $req_export = MySBDB::query( $export_req.' ORDER BY id',
                                 "admin/users_process.php", true );
    $expcount = 0;
    $expbody = 'Mails count: '.MySBDB::num_rows($req_export).'<br><br>'."\n";
    while($data = MySBDB::fetch_array($req_export)) {
        $expcount++;
        if( $expcount>=50 ) {
            $expbody .= '<br><br>'."\n";
            $expcount = 0;
        }
        if( $data['mail']!='' )
            $expbody .= $data['mail'].', ';
    }
    $uomail = new MySBMail('blank');
    $uomail->addTO($app->auth_user->mail,$app->auth_user->firstname.' '.$app->auth_user->lastname);
    $uomail->data['subject'] = _G('SBGT_exportuo');
    $uomail->data['body'] = $expbody.'<br><br>'."\n";
    $uomail->send();
    $app->pushMessage( _G('SBGT_exportuo_msg').' '.$app->auth_user->mail );
}

include( _pathT('admin/plugins') );

?>
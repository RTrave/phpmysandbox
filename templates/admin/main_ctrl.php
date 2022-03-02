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

use PHPMailer\PHPMailer\PHPMailer;
//require (MySB_ROOTPATH.'/vendor/autoload.php');
//require (MySB_ROOTPATH.'/vendor/phpmailer/phpmailer/src/PHPMailer.php');
require (MySB_ROOTPATH.'/vendor/phpmailer/phpmailer/src/SMTP.php');
require (MySB_ROOTPATH.'/vendor/phpmailer/phpmailer/src/Exception.php');

if(!MySBRoleHelper::checkAccess('admin')) return;


if(isset($_POST['config_modif'])) {
    $configs = MySBConfigHelper::loadByGrp('');
    foreach($configs as $config){
        $getvalue = $config->htmlProcessValue('config_');
        if($config->updateOnEmpty() || !empty($getvalue)) $config->setValue($getvalue);
    }
}

if(isset($_POST['set_mod'])) {
    $module = MySBModuleHelper::getByName($_POST['set_mod']);
    $module->init();
}
if(isset($_POST['reinit_mod'])) {
    $module = MySBModuleHelper::getByName($_POST['reinit_mod']);
    $module->reinit();
}
if(isset($_POST['delete_mod'])) {
    $module = MySBModuleHelper::getByName($_POST['delete_mod']);
    $module->delete();
}
if(isset($_POST['unset_mod'])) {
    $module = MySBModuleHelper::getByName($_POST['unset_mod']);
    $module->uninit();
}

if(isset($_POST['moduleconfig_mod'])) {
    $module = MySBModuleHelper::getByName($_POST['moduleconfig_mod']);
    $configs = MySBConfigHelper::loadByGrp($module->name);
    foreach($configs as $config){
        $getvalue = $config->htmlProcessValue($module->name.'config_');
        if($config->updateOnEmpty() || !empty($getvalue)) $config->setValue($getvalue);
    }
}

if(isset($_POST['test_mail'])) {
    $testmail = new MySBMail('blank');
    $testmail->addTO($app->auth_user->mail,$app->auth_user->firstname.' '.$app->auth_user->lastname);
    $testmail->data['subject'] = 'Test mail';
    $testmail->data['body'] = 'Body for test';
    $testmail->send();
    $app->pushMessage( _G('SBGT_admin_testmail').':<br>'.$app->auth_user->login.' &lt;'.$app->auth_user->mail.'&gt;' );
}

include(MySB_ROOTPATH.'/config.php');
if( $mysb_ext_mail=='PHPMailer' and
    file_exists(MySB_ROOTPATH.'/phpmailer.conf.php') )
    include(MySB_ROOTPATH.'/phpmailer.conf.php');

$infos_php = array();
$infos_php[] = array('PHP version',phpversion());
if(function_exists('imap_open'))
    $infos_php[] = array( 'IMAP support','present');
else $infos_php[] = array( 'IMAP support','not present');
if(class_exists('MySBPDF')) {
    $mypdf = new MySBPDF();
    $infos_php[] = array( 'TCPDF class',$mypdf->mytcpdf_version.' present' );
} else  $infos_php[] = array( 'TCPDF class','not present' );
if(file_exists(MySB_ROOTPATH.'/vendor/mk-j/php_xlsxwriter/xlsxwriter.class.php')) {
    $infos_php[] = array( 'XLSXWriter class', 'present');
} else {
    $infos_php[] = array( 'XLSXWriter class',' not present');
}
if(class_exists('PHPMailer') or class_exists('PHPMailer\PHPMailer\PHPMailer')) {
    $mailerobj = new PHPMailer();
    //echo $mailerobj::VERSION;
    if( $mysb_ext_mail=="PHPMailer" )
         if($mailerobj::VERSION)
            $infos_php[] = array( 
                'PHPMailer class','v:'.$mailerobj::VERSION.'<br><small>('.
                $mysb_mail.' on '.$phpmailer_Host.')</small>');
    else
        $infos_php[] = array( 'PHPMailer class','not used');
} else if(file_exists(MySB_ROOTPATH.'/vendor/phpmailer/phpmailer/class.phpmailer.php')) {
    $infos_php[] = array( 'PHPMailer class','present but not configured<br>(see config.php and phpmailer.conf.php)');
} else $infos_php[] = array( 'PHPMailer class','not present');
$editor = new MySBEditor();
if( $editor->tmce_present )
    $infos_php[] = array( 'TinyMCE',$editor->tmce_version.' present');
else $infos_php[] = array( 'TinyMCE','not present');

$infos_db = array();
$infos_db[] = array( 'db layer<br><span class="help">(from config.php)</span>',$mysb_dblayer );
$infos_db[] = array( 'db<br><span class="help">(from config.php)</span>',$mysb_dbname.'@'.$mysb_dbhost );
$infos_db[] = array( 'dbuser<br><span class="help">(from config.php)</span>',$mysb_dbuser );
$infos_db[] = array( 'table_prefix<br><span class="help">(from config.php)</span>',$mysb_table_prefix );
$infos_db[] = array( 'tables version<br><span class="help">(from __init.php)</span>',MySBConfigHelper::Value('core_version','modules') );

function admin_getrequired($module) {
    $reqtext = '';
    if( !isset($module->module_helper->require) )
        return 'No dependencies informations';
    foreach($module->module_helper->require as $modname=>$modvers) {
        if( $modname=='core' )
            if( $modvers!=MySBConfigHelper::Value('core_version','modules') ) {
                $reqtext .= $modname.'(<span style="color: red;"><b>v:'.$modvers.'</b></span>) ';
                continue;
            } else {
                $reqtext .= $modname.'(v:'.$modvers.') ';
                continue;
            }
        $mod = MySBModuleHelper::getByName($modname);
        if( $mod==null ) {
            $reqtext .= $modname.'(<span style="color: red;"><b>not found</b></span>) ';
            continue;
        }
        if( $mod->module_helper->version!=$modvers )
            $reqtext .= $modname.'(<span style="color: red;"><b>v:'.$modvers.'</b></span>) ';
        else
            $reqtext .= $modname.'(v:'.$modvers.') ';
    }
    return $reqtext;
}

include( _pathT('admin/main') );

?>

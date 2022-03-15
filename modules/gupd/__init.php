<?php
/**
 * phpMySandBox - GitUpdate module
 * 
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License', or
 * ('at your option) any later version. 
 * (Roman Travé <roman.trave@abadcafe.org>, 2022)
 *
 * @package    phpMySandBox\GUpd
 * @license    http://opensource.org/licenses/gpl-license.php  GNU Public License
 * @author     Roman Travé <roman.trave@abadcafe.org>
 */

// No direct access.
defined('_MySBEXEC') or die;

class MySBModule_gupd {

    public $lname = 'gitupdate';
    public $version = 1;
    public $release_version = '1a';
    public $homelink = 'http://phpmysandbox.abadcafe.org';
    public $require = array(
        'core' => 7
        );

    public function create() {
        global $app;
    }

    public function delete() {
        global $app;
/*
        $req = MySBDB::query('ALTER TABLE '.MySB_DBPREFIX.'dbmfblockrefs DROP COLUMN autosubs',
            "__init.php",
            false, "dbmf3_asub");
        $req = MySBDB::query('ALTER TABLE '.MySB_DBPREFIX.'dbmfblockrefs DROP COLUMN autosubs_date',
            "__init.php",
            false, "dbmf3_asub");
*/
    }

    public function init1() {
        global $app;

        MySBPluginHelper::create('admingupd_menutext','MenuItem',
            array('Releases', "admin", 'Releases upgrades',''),
            array(3,0,0,0),
            5,"admin",'gupd');

        mkdir(MySB_GUPDFiles);

/*
        $req = MySBDB::query('ALTER TABLE '.MySB_DBPREFIX.'dbmfblockrefs '.
            'ADD autosubs int',
            "__init.php",
            false, "dbmf3_asub");
        $req = MySBDB::query('ALTER TABLE '.MySB_DBPREFIX.'dbmfblockrefs '.
            'ADD autosubs_date date',
            "__init.php",
            false, "dbmf3_asub");

        $autosubsrole = MySBRoleHelper::create('dbmf_autosubs','Auto-subscribe process');

        MySBConfigHelper::create('dbmf_autosubs_blockref','',MYSB_VALUE_TYPE_VARCHAR512,
            'BlockRef filled with 1 when autosubs', 'dbmf3_asub');
        MySBConfigHelper::create('dbmf_autosubs_blockreflock','',MYSB_VALUE_TYPE_VARCHAR512,
            'Contact locked if this BlockRef is filled with 1', 'dbmf3_asub');
        MySBConfigHelper::create('dbmf_autosubs_mailconfirm','1',MYSB_VALUE_TYPE_BOOL,
            'Send a confirmation mail', 'dbmf3_asub');
        MySBConfigHelper::create('dbmf_autosubs_mailaddress','',MYSB_VALUE_TYPE_VARCHAR512,
            'Mail copy to', 'dbmf3_asub');
        MySBConfigHelper::create('dbmf_autosubs_anonaccess','',MYSB_VALUE_TYPE_BOOL,
            'Anonymous access to autosubs', 'dbmf3_asub');

        MySBPluginHelper::create('autosubs_menutext','MenuItem',
            array('DBMF_topmenu_autosubs', "step1", 'DBMF_topmenu_autosubsinfos',''),
            array(1,0,0,0),
            6,"dbmf_autosubs",'dbmf3_asub');
        MySBPluginHelper::create('adminasub_menutext','MenuItem',
            array('DBMF_adminmenu_asub', "admin", 'DBMF_adminmenu_asubinfos',''),
            array(3,0,0,0),
            5,"admin",'dbmf3_asub');
*/
    }


    public function uninit() {
        global $app;

        MySBPluginHelper::delete('admingupd_menutext','gupd');
/*
        MySBRoleHelper::delete('dbmf_autosubs');
        MySBConfigHelper::delete('dbmf_autosubs_datebr','dbmf3_asub');
        MySBConfigHelper::delete('dbmf_autosubs_blockref','dbmf3_asub');
        MySBConfigHelper::delete('dbmf_autosubs_blockreflock','dbmf3_asub');
        MySBConfigHelper::delete('dbmf_autosubs_mailconfirm','dbmf3_asub');
        MySBConfigHelper::delete('dbmf_autosubs_mailaddress','dbmf3_asub');
        MySBConfigHelper::delete('dbmf_autosubs_anonaccess','dbmf3_asub');
        MySBPluginHelper::delete('autosubs_menutext','dbmf3_asub');
        MySBPluginHelper::delete('adminasub_menutext','dbmf3_asub');
*/
    }

}
?>


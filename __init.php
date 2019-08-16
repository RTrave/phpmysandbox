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

class MySBCore {

    public $version = 7;
    public $mysb_major_version = '0';
    public $mysb_minor_version = '9f';

    public function init1() {
        global $app;

        // New install => skip stages merged (7)
        $app->init_skip = 1;
    }

    public function init2() {
        global $app;

        if($app->init_skip) return;
        MySBDB::query('
CREATE TABLE '.MySB_DBPREFIX.'valueoptions (
value_keyname varchar(64),
value0 varchar(1024),
value1 varchar(1024),
value2 varchar(1024))
DEFAULT CHARSET=utf8',
"__init.php");
    }

    public function init3() {
        global $app;

        if($app->init_skip) return;
        MySBDB::query('
ALTER TABLE '.MySB_DBPREFIX.'plugins ADD COLUMN childclass varchar(64)',
"__init.php");
        MySBPluginHelper::create('native_authlayer','AuthLayer',
            array("Native", 'Native auth layer', '',''),
            array(0,0,0,0),
            5,"",'');

    }

    public function init4() {
        global $app;

        if($app->init_skip) return;
        MySBDB::query(  'ALTER TABLE '.MySB_DBPREFIX.'users '.
                        'ADD COLUMN logattempt_nb integer',
                        "__init.php");
        MySBDB::query(  'ALTER TABLE '.MySB_DBPREFIX.'users '.
                        'ADD COLUMN logattempt_date datetime',
                        "__init.php");
        $selconf = MySBConfigHelper::create(   'login_vs_mail','SBGT_loginvsmail_unique',MYSB_VALUE_TYPE_VARCHAR64_SELECT,
                                    'Login against mail', '');
        $selconf->addSelectOption( 'SBGT_loginvsmail_unique' );
        $selconf->addSelectOption( 'SBGT_loginvsmail_mail' );
    }


    public function init5() {
        global $app;

        if($app->init_skip) return;
        $scriptpass = MySBConfigHelper::create(   'script_passwd','',MYSB_VALUE_TYPE_VARCHAR64,
                                    'SBGT_scriptpassword', '');
        $scriptattempts = MySBConfigHelper::create(   'script_attempts','',MYSB_VALUE_TYPE_INT,
                                    'SBGT_scriptattempts', 'scripts');
    }

    public function init6() {
        global $app;

        if($app->init_skip) return;
        MySBDB::query(  'ALTER TABLE '.MySB_DBPREFIX.'users '.
                        'ADD COLUMN mailattempt_date datetime',
                        "__init.php");
    }

    public function init7() {
        global $app;

        if($app->init_skip) return;
        MySBDB::query(  'ALTER TABLE '.MySB_DBPREFIX.'valueoptions '.
                        'ADD COLUMN id int primary key AUTO_INCREMENT',
                        "__init.php");
    }

    public function uninit() {
        global $app;
        //configs
    }
}
?>

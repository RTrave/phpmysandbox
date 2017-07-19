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

class MySBModule_example {

    public $version = 2;

    public function create() {
        global $app;
        
        //tables
        $req = MySBDB::query('CREATE TABLE '.MySB_DBPREFIX.'example ( '.
            'id int unique key ) ',
            "MySBModule_example::create()",
            false, 'example');

        //plugins using tables
        MySBPluginHelper::create('example_useroptionbool','UserOption',
            array("exbool", "Example_useroption_text1", '',''),
            array(MYSB_VALUE_TYPE_BOOL,0,0,0),
            6,"",'example');
        MySBPluginHelper::create('example_useroptionvarchar','UserOption',
            array("exvarchar", "Example user string", '',''),
            array(MYSB_VALUE_TYPE_VARCHAR64,0,0,0),
            4,"",'example');

    }

    public function delete() {
        global $app;

        //configs
        MySBConfigHelper::delete('example_bool','example');
        MySBConfigHelper::delete('example_int', 'example');
        MySBConfigHelper::delete('example_varchar', 'example');
        MySBConfigHelper::delete('example_text','example');
        MySBConfigHelper::delete('example_bool2','example');
        MySBConfigHelper::delete('example_select','example');

        //plugins using tables
        MySBPluginHelper::delete('example_useroptionbool','example');
        MySBPluginHelper::delete('example_useroptionvarchar','example');

        //tables
        $req = MySBDB::query('DROP TABLE '.MySB_DBPREFIX.'example',
            "MySBModule_example::delete()",
            false, 'example');
    }

    public function init1() {
        global $app;
        
        //configs
        MySBConfigHelper::create('example_bool','1',MYSB_VALUE_TYPE_BOOL,
            'Example config bool', 'example');
        MySBConfigHelper::create('example_int','123',MYSB_VALUE_TYPE_INT,
            'Example config int', 'example');
        MySBConfigHelper::create('example_varchar','text example',MYSB_VALUE_TYPE_VARCHAR64,
            'Example config varchar64', 'example');
        MySBConfigHelper::create('example_text',"text example\nwith lines",MYSB_VALUE_TYPE_TEXT,
            'Example config text', 'example');
        $selconf = MySBConfigHelper::create('example_select',"selected text2",
            MYSB_VALUE_TYPE_VARCHAR64_SELECT,
            'Example config select', 'example');
        $selconf->addSelectOption( "selected text1" );
        $selconf->addSelectOption( "selected text2" );

        //users, groups and roles
        $exgroup = MySBGroupHelper::create('example_group','Example group ..',true);
        $exrole = MySBRoleHelper::create('example_role','Role for Example');
        $exrole->assignToGroup('example_group',true);


        //plugins
        MySBPluginHelper::create('example1_menutext','MenuItem',
            array("Example_topmenu_ex1", "example_file1", 'Example_topmenu_ex1infos',''),
            array(1,0,0,0),
            1,"example_role",'example');
        MySBPluginHelper::create('example2_menutext','MenuItem',
            array("Example_topmenu_ex2", "example_file2", 'Example_topmenu_ex2infos',''),
            array(2,0,0,0),
            2,"example_role",'example');
        MySBPluginHelper::create('adminexample_menutext','MenuItem',
            array("Example_admin_ex", "admin_example", 'Example_admin_exinfos',''),
            array(3,0,0,0),
            2,"admin",'example');

        MySBPluginHelper::create('example_css','Header',
            array('CSS', "example.css", '',''),
            array(0,0,0,0),
            9,"example_role",'example');
        
        MySBPluginHelper::create('example_inc','Include',
            array("libraries/example_inc.php", '', '',''),
            array(0,0,0,0),
            5,"example_role",'example');
        
        MySBPluginHelper::create('example_frontpage','FrontPage',
            array("index_example", '', '',''),
            array(0,0,0,0),
            5,"example_role",'example');

        //mydbsb_LOG("__init.php: extension ".$set_ext." setted!",$set_ext);
    }

    public function init2() {
        global $app;
        //configs
        MySBConfigHelper::create('example_bool2','1',MYSB_VALUE_TYPE_BOOL,'Example2 config value', 'example');
    }

    public function init3() {
        global $app;

        //plugins
        MySBPluginHelper::create('example1_menutext','MenuItem',
            array("Example_topmenu_ex1", "example_file1", 'Example_topmenu_ex1infos',''),
            array(1,0,0,0),
            1,"example_role",'example');
        MySBPluginHelper::create('example2_menutext','MenuItem',
            array("Example_topmenu_ex2", "example_file2", 'Example_topmenu_ex2infos',''),
            array(2,0,0,0),
            2,"example_role",'example');
        MySBPluginHelper::create('adminexample_menutext','MenuItem',
            array("Example_admin_ex", "admin_example", 'Example_admin_exinfos',''),
            array(3,0,0,0),
            2,"admin",'example');
    }

    public function uninit() {
        global $app;

        //plugins
        MySBPluginHelper::delete('example1_menutext','example');
        MySBPluginHelper::delete('example2_menutext','example');
        MySBPluginHelper::delete('adminexample_menutext','example');
        MySBPluginHelper::delete('example_css','example');
        MySBPluginHelper::delete('example_inc','example');
        MySBPluginHelper::delete('example_frontpage','example');

        //groups&roles
        MySBGroupHelper::delete('example_group');
        MySBRoleHelper::delete('example_role');
    }

}
?>

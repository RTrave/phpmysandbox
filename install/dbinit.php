<?php
/**
 * phpMySandBox - Simple Database Framework in PHP 
 *
 * DB install template.
 * 
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License', or
 * ('at your option) any later version.
 * (Roman Travé <roman.trave@gmail.com>, 2012)
 *
 * @package    phpMySandBox
 * @subpackage Install
 * @license    http://opensource.org/licenses/gpl-license.php  GNU Public License
 * @author     Roman Travé <roman.trave@gmail.com>
 */


// Set flag that this is a parent file.
defined('_MySBEXEC') or die;

global $app, $_POST;

?>
<!DOCTYPE html>
<html>
<head>
    <title>PHPMySandBox - first launch</title>
    <link rel="stylesheet" type="text/css" href="mysb.css">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
</head>

<?php
$app->LOG('open log !','');
?>

<body>

<div id="mysbMiddle" class="roundtop roundbottom"><div class="content" style="text-align: left;">
<?php

if(MySBDB::table_exists('users')) {
    echo '
! database yet installed !<br>
<a href="index.php">Home page</a>';
    $app->close();
    echo '
</div></div>
</body>
</html>';
    die;
}

$pathtestfile = MySB_ROOTPATH.'/log/testfile';
unlink($pathtestfile);
$testfile = fopen($pathtestfile, "w");
if(!$testfile) {
  echo '
<div class="content">
  <h1 style="color: red;">!!! PERMISSIONS ALERT !!!</h1>
  <div class="row">
    <div class="col-sm-12">
        The folder <b>log/</b> is read-only. <br>
        Please fix this.
    </div>
  </div>
</div>';
}
fclose($testfile);
unlink($pathtestfile);

?>


<h2>Initialisation</h2>
<h3>Base informations</h3>
<ul>
    <li>db (from config.php): '<?php echo ($mysb_dbname); ?>'</li>
    <li>dbuser (from config.php): '<?php echo ($mysb_dbuser); ?>'</li>
    <li>table_prefix (from config.php): '<?php echo ($mysb_table_prefix); ?>'</li>
</ul>

<?php

if( isset($_POST['dbinit']) and $_POST['dbinit']==1) {

    MySBDB::query('
CREATE TABLE '.MySB_DBPREFIX.'users (
id int primary key, 
login varchar(64),
lastname varchar(128),
firstname varchar(128),
mail varchar(128),
passwd varchar(256),
active int,
auth_rand varchar(256),
last_login datetime,
logattempt_nb integer,
logattempt_date datetime,
mailattempt_date datetime)
DEFAULT CHARSET=utf8',
"dbinit.php");

    MySBDB::query('
CREATE TABLE '.MySB_DBPREFIX.'groups (
id int primary key, 
name varchar(64) unique key,
comments varchar(256) ) 
DEFAULT CHARSET=utf8',
"dbinit.php");

    MySBDB::query('
CREATE TABLE '.MySB_DBPREFIX.'roles (
id int primary key, 
name varchar(64) unique key,
comments varchar(256)) 
DEFAULT CHARSET=utf8',
"dbinit.php");

    MySBDB::query('
CREATE TABLE '.MySB_DBPREFIX.'config ( 
id int primary key, 
keyname varchar(32), 
value varchar(512), 
type int,
comments varchar(512),
grp varchar(32)) 
DEFAULT CHARSET=utf8',
"dbinit.php");

    MySBDB::query('
CREATE TABLE '.MySB_DBPREFIX.'plugins ( 
id int primary key, 
name varchar(32) unique key, 
type varchar(32),
value0 varchar(1024), 
value1 varchar(1024),
value2 varchar(1024),
value3 varchar(1024),
ivalue0 int,
ivalue1 int,
ivalue2 int,
ivalue3 int,
priority int,
childclass varchar(64),
role varchar(32),
module varchar(32)) 
DEFAULT CHARSET=utf8',
"dbinit.php");

    MySBDB::query('
CREATE TABLE '.MySB_DBPREFIX.'valueoptions ( 
id int primary key AUTO_INCREMENT,
value_keyname varchar(64),
value0 varchar(1024), 
value1 varchar(1024),
value2 varchar(1024)) 
DEFAULT CHARSET=utf8',
"dbinit.php");

    echo '<br><br>Tables created !<br>';

    MySBPluginHelper::create('native_authlayer','AuthLayer',
        array("Native", 'Native auth layer', '',''),
        array(0,0,0,0),
        5,"",'');

    $admin_group = MySBGroupHelper::create('admin','Administrators');
    $users_group = MySBGroupHelper::create('users','Registred users',true);

    MySBDB::query('INSERT INTO '.MySB_DBPREFIX.'users '.
        '(id,login,lastname,active,g0,g1) '.
        "VALUES (0,'default_user','DEFAULT USER',0,0,1)",
        "dbinit.php");
    $admin_user = MySBUserHelper::create('admin','Administrator','',$_POST['firstmail']);
    $admin_user->resetPassword($_POST['firstpw']);

    $admin_role = MySBRoleHelper::create('admin', 'Administration');
    $profil_role = MySBRoleHelper::create('change_profile', 'Can change profile datas');

    $admin_user->activate();
    $admin_user->assignToGroup('admin');
    $admin_role->assignToGroup('admin');
    $profil_role->assignToGroup('users');

    MySBConfigHelper::create('website_name','PHPMySandBox',MYSB_VALUE_TYPE_VARCHAR64,'Name of the website',  '');
    MySBConfigHelper::create('technical_contact','mailto:'.$_POST['firstmail'],MYSB_VALUE_TYPE_VARCHAR64,'Technical contact',  '');
    MySBConfigHelper::create('mail_visible','0',MYSB_VALUE_TYPE_BOOL,'Visibility of users mail', '');
    MySBConfigHelper::create('registration_auto','1',MYSB_VALUE_TYPE_BOOL,'Users creates login themselves', '');
    MySBConfigHelper::create('registration_notify','1',MYSB_VALUE_TYPE_BOOL,'Admin notified for new users', '');
    MySBConfigHelper::create('core_version','0',MYSB_VALUE_TYPE_INT,'Core version', 'modules');

    $selconf = MySBConfigHelper::create('login_vs_mail','SBGT_loginvsmail_unique',MYSB_VALUE_TYPE_VARCHAR64_SELECT,'Login against mail', '');
    $selconf->addSelectOption( 'SBGT_loginvsmail_unique' );
    $selconf->addSelectOption( 'SBGT_loginvsmail_mail' );

    $scriptpass = MySBConfigHelper::create('script_passwd','',MYSB_VALUE_TYPE_VARCHAR64,'SBGT_scriptpassword', '');
    $scriptattempts = MySBConfigHelper::create('script_attempts','',MYSB_VALUE_TYPE_INT,'SBGT_scriptattempts', 'scripts');


    echo 'Tables populated !<br>';

    echo '<a href="index.php">Return to home page</a>
        </div></div></body></html>';
    $app->close();
    die;
}

?>

<br>
<form action="index.php" method="post">
<h3>Admin first values</h3>
<ul>
    <li>password: <input type="password" name="firstpw" value="secret" size="16" maxlength="16"></li>
    <li>mail: <input type="text" name="firstmail" value="webmaster@localhost" size="32" maxlength="64"></li>
</ul>
<input type="hidden" name="dbinit" value="1">
<input type="hidden" name="first_launch" value="1">
<input type="submit" value="Init database" style="margin: 20px auto 10px 10px;">
</form>

</div></div>
</body>
</html>

<?php
$app->close();
die;
?>

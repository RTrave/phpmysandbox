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

_incI('admin/menu');

include(MySB_ROOTPATH.'/config.php');
?>

<h1>PHPMySandBox administration</h1>

<h2>System informations</h2>

<h3>PHP</h3>

<p>
<?php
echo '
PHP version: '.phpversion().'<br>
DateTime->diff(): ';
if(method_exists('DateTime','diff')) echo 'present<br>';
else echo 'not present<br>';
if( $mysb_ext_mail=='PHPMailer' and 
    file_exists(MySB_ROOTPATH.'/phpmailer.conf.php') ) {
    include(MySB_ROOTPATH.'/phpmailer.conf.php');
    require_once (MySB_ROOTPATH.'/'.$phpmailer_path.'/class.phpmailer.php');
}
echo '
TCPDF class: ';
if(class_exists('MySBPDF')) {
    $mypdf = new MySBPDF();
    echo $mypdf->mytcpdf_version.' present<br>';
} else echo 'not present<br>';
echo '
PHPMailer class: ';
if(class_exists('PHPMailer')) {
    $mailerobj = new PHPMailer();
    echo $mailerobj->Version;
    if( $mysb_ext_mail=="PHPMailer" ) 
        echo ' ('.$phpmailer_Mail.' on '.$phpmailer_Host.')<br>';
    else
        echo ' (not used)<br>';
} else echo 'not present<br>';
echo '
IMAP support: ';
if(function_exists('imap_open')) echo 'present<br>';
else echo 'not present<br>';
echo 'TinyMCE: ';
$editor = new MySBEditor();
if( $editor->tmce_present ) echo $editor->tmce_version.' present<br>';
else echo 'not present<br>';
echo '
</p>
<form action="index.php?tpl=admin/admin" method="post">
<div style="text-align: center;"><input type="hidden" name="test_mail" value="1">
<input type="submit" value="test mail on '.$app->auth_user->mail.'">
</div>
</form>
<br>
';
?>


<h3>DataBase</h3>

<p>
db layer (from config.php): '<?php echo ($mysb_dblayer); ?>'<br>
db (from config.php): '<?php echo ($mysb_dbname); ?>'@'<?php echo ($mysb_dbhost); ?>'<br>
dbuser (from config.php): '<?php echo ($mysb_dbuser); ?>'<br>
table_prefix (from config.php): '<?php echo ($mysb_table_prefix); ?>'<br>
tables version: <?php echo (MySBConfigHelper::Value('core_version','modules')); ?><br>
</p>

<h2>Configurations</h2>
<form action="index.php?tpl=admin/admin" method="post">
<div class="boxed">
<?php
$app_config = MySBConfigHelper::loadByGrp('');
foreach($app_config as $cur_config) {
    echo '
    <div class="row">
        <div class="right">'.$cur_config->htmlForm('config_',$cur_config->value).'</div>
        '._G($cur_config->comments).'<br>
        <span class="help">'.$cur_config->keyname."</span>
    </div>";
}

?>
    <div class="row" style="text-align: center;">
        <input type="hidden" name="config_modif" value="1">
        <input type="submit" value="Modify">
    </div>
</div>
</form>

<h2>Modules</h2>

<?php
$modules = MySBModuleHelper::load();
foreach($modules as $module) {
    $mod_conf = MySBConfigHelper::get('mod_'.$module->name.'_enabled','modules');
    echo '
<h3>'.$module->name.'</h3>';

    if($mod_conf==null) {
        echo '
<form action="index.php?tpl=admin/admin" method="post">
<p>
    module <b>disabled</b>: 
    <input type="hidden" name="set_mod" value="'.$module->name.'">
    <input type="submit" value="Set '.$module->name.'">
</p>
</form>';
    } else {

    if($mod_conf->getValue()>=1) {
        echo '
<form action="index.php?tpl=admin/admin" method="post" 
      OnSubmit="return mysb_confirm(\'Unset module '.$module->name.'?\')">
<p>
    Version: '.$module->module_helper->version.'<br>
    Module <b>enabled</b>: 
    <input type="hidden" name="unset_mod" value="'.$module->name.'">
    <input type="submit" value="Unset '.$module->name.'">
</p>
</form>';
    } elseif($mod_conf->getValue()==-1) {
        echo '
<form action="index.php?tpl=admin/admin" method="post">
<p>
module <b>disabled</b>: 
    <input type="hidden" name="reinit_mod" value="'.$module->name.'">
    <input type="submit" value="Reinit '.$module->name.'">
</p>
</form>
<form action="index.php?tpl=admin/admin" method="post" OnSubmit="return mysb_confirm(\'Delete tables in '.$module->name.'?\')">
<p>
    <input type="hidden" name="delete_mod" value="'.$module->name.'">
    <input type="submit" value="Delete '.$module->name.' tables">
</p>
</form>';
    }
    echo '

<h4>Configurations</h4>
<div class="boxed">';
$configs = MySBConfigHelper::loadByGrp($module->name);
if(count($configs)==0) echo "\n<i>No config values</i></p>";
else {
        echo '
<form action="index.php?tpl=admin/admin" method="post">';
        foreach($configs as $config) {
            echo '
    <div class="row">
        <div class="right">'.$config->htmlForm($module->name.'config_',$config->value).'</div>
        '._G($config->comments).'<br>
        <span class="help">'.$config->keyname."</span>";
        if( $config->getType()=='text' ) echo '<div class="cell_hide"><br><br></div>';
        echo '
    </div>';
        }
        echo '
    <div class="row" style="text-align: center;">
        <input type="hidden" name="moduleconfig_mod" value="'.$module->name.'">
        <input type="submit" value="Update '.$module->name.' configs">
    </div>
</form>';
}
echo '
</div>
<h4>Plugins</h4>';
    $plugins = MySBPluginHelper::loadByModule($module->name);
    if(count($plugins)==0) echo "
<p><i>No plugin</i></p>";
    else {
        echo '
<ul>';
        foreach($plugins as $plugin) {
            echo '
    <li>'.$plugin->name.' <i>('.$plugin->type.')</i></li>';
        }
        echo '
</ul>';
    }
}

}
?>

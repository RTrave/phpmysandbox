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

if(!MySBRoleHelper::checkAccess('admin')) return;

if(isset($_POST['config_modif'])) {
    $configs = MySBConfigHelper::loadByGrp('');
    foreach($configs as $config) 
        $config->setValue($config->htmlProcessValue('config_'));
    echo '
<script>
loadItem("app_config","index.php?inc=admin/conf_display");
</script>';
    return;
}



$app_config = MySBConfigHelper::loadByGrp('');
foreach($app_config as $cur_config) {
    if( $cur_config->getType()!='text' )
        echo '
    <div class="row">
        <div class="right">'.$cur_config->htmlForm('config_',$cur_config->value).'</div>
        '._G($cur_config->comments).'<br>
        <span class="help">'.$cur_config->keyname."</span>
    </div>";
    else
        echo '
    <div class="row" style="text-align: right;">
        <div style="float: left; text-align: left;">'._G($cur_config->comments).'<br>
        <span class="help">'.$cur_config->keyname.'</span></div>
        <div style="display: inline-block; margin: 0px 0px 0px auto;">'.$cur_config->htmlForm('config_',$cur_config->value).'</div>        
    </div>';
}


?>

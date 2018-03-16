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
  echo '
<div class="row label">
'.$cur_config->innerRow('config_',$cur_config->value,true,
                        _G($cur_config->comments),
                        $cur_config->keyname).'
</div>';
}


?>

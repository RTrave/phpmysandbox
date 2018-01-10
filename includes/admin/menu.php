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

echo '
<script>
function toggle_arrows(vDIV){
    if($("#"+vDIV).css("display")=="block") 
        $("#"+vDIV).fadeOut(0);
    else
        $("#"+vDIV).fadeIn(300);
}
</script>
<div id="mysbMenuLevelSwitch" class="cell_show" style="text-align: center;">
    <div id="mysbMenuSwitch" class="roundtop button" onClick="toggle_slide(\'mysbMenuLevel\'); toggle_arrows(\'arr1\'); toggle_arrows(\'arr2\'); toggle_arrows(\'arr3\'); toggle_arrows(\'arr4\');">
        <img id="arr1" src="images/icons/go-down.png" style="float: left;" alt="go-down">
        <img id="arr2" src="images/icons/go-down.png" style="float: right;" alt="go-down">
        <img id="arr3" src="images/icons/go-up.png" style="float: left; display: none;" alt="go-up">
        <img id="arr4" src="images/icons/go-up.png" style="float: right; display: none;" alt="go-up">
        Toggle<br>Menu
    </div>
</div>
<div id="mysbMenuLevel" class="cell_hide">
<ul>
    <li class="first last"><a href="index.php?tpl=admin/admin">phpMySandbox</a></li>
    <li class="first"><a href="index.php?tpl=admin/users">'._G('SBGT_adminusers').'</a></li>
    <li class=""><a href="index.php?tpl=admin/groups">'._G('SBGT_admingroups').'</a></li>
    <li class="last"><a href="index.php?tpl=admin/plugins">'._G('SBGT_adminplugins').'</a></li>';

$pluginsMenuItem = MySBPluginHelper::loadByType('MenuItem');
foreach($pluginsMenuItem as $plugin)
    if( $plugin->displayA(3)!='' ) echo '
    <li class="first last">'.$plugin->displayA(3).'</li>';

echo '
</ul>
</div>';

?>

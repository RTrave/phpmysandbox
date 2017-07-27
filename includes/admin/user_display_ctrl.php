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


if( isset($_GET['user_id']) )
    $user = MySBUserHelper::getByID($_GET['user_id']);

echo '
<div class="row">
<div style="float: left;">
    <a  class="overlayed"
        href="index.php?tpl=admin/user_edit&amp;user_id='.$user->id.'">
        <img    src="images/icons/text-editor.png" 
                alt="'._G('SBGT_edit').' '.$user->lastname.' '.$user->firstname.'" 
                title="'._G('SBGT_edit').' '.$user->lastname.' '.$user->firstname.'"
                style="width1: 24px"></a>';

if( $user->mail!='' )
    echo '
    <a href="mailto:'.$user->mail.'">
        <img src="images/icons/mail-unread.png" 
             alt="'._G('SBGT_mailto').' '.$user->lastname.' '.$user->firstname.'" 
             title="'._G('SBGT_mailto').' '.$user->lastname.' '.$user->firstname.'"></a>';
else 
    echo '
    <img    src="images/blank.png" 
            style="width: 32px;"
            alt="No mail for '.$user->lastname.' '.$user->firstname.'" 
            title="No mail for  '.$user->lastname.' '.$user->firstname.'">';

echo '
</div>
<div style="float: right;"><small>';

$lastlogin = new MySBDateTime($user->last_login);
if( $user->last_login!='' ) {
    //echo strftime("%A %e %B %Y à %H:%M",strtotime($user->last_login));
    echo $lastlogin->strEBY_l_whm();
} else {
    echo '-';
}

echo '
</small></div>
<div class="label" style="min-width: 200px;">
<b>'.$user->lastname.'</b> '.$user->firstname.'<br>
<i>'.$user->login.' <small>(ID:'.$user->id.')</small></i>
</div>
</div>
<script>
show("user'.$user->id.'");
</script>
';


?>

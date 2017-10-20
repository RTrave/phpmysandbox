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
global $groups_a;

include( _pathI('admin/menu') );

echo '
<h1>'._G('SBGT_adminusers').'</h1>
<div class="list_support">';

if( isset($_POST['users_search']) ) {
    foreach( $found_users as $user ) {
        echo '
<div id="user'.$user->id.'" style="display: inline-block; width: 600px; max-width: 90%;">';
        include( _pathI('admin/user_display_ctrl') );
        echo '
</div>';
    }
}

echo '
<form action="index.php?tpl=admin/users" method="post">

<div class="boxed">
    <div class="title roundtop"><b>'._G('SBGT_adminusers_search').'</b></div>
    <div class="row">
        <div class="right"><input type="text" name="bylogin" value="'.$bylogin.'"></div>
        '._G('SBGT_adminusers_searchbylogin').'
    </div>
    <div class="row">
        <div class="right"><input type="text" name="bylastname" value="'.$bylastname.'"></div>
        '._G('SBGT_adminusers_searchbylastname').'
    </div>
    <div class="row">
        <div class="right"><input type="text" name="bymail" value="'.$bymail.'"></div>
        '._G('SBGT_adminusers_searchbymail').'
    </div>
    <div class="row" style="text-align: center;">
        <input type="hidden" name="users_search" value="1">
        <input type="submit" value="'._G('SBGT_search').'">
    </div>
</div>

</form>
</div>';

echo '
<div class="boxed">
<form action="index.php?tpl=admin/users" method="post">

    <div class="title roundtop"><b>'._G('SBGT_adminusers_new').'</b></div>
    <div class="row">
        <div class="right"><input type="text" name="user_login"></div>
        '._G('SBGT_login').'
    </div>
    <div class="row">
        <div class="right"><input type="text" name="user_lastname"></div>
        '._G('SBGT_lastname').'
    </div>
    <div class="row">
        <div class="right"><input type="text" name="user_firstname"></div>
        '._G('SBGT_firstname').'
    </div>
    <div class="row">
        <div class="right"><input type="email" name="user_mail"></div>
        '._G('SBGT_mail').'
    </div>
    <div class="row" style="text-align: center;">
        <input type="hidden" name="users_search" value="1">
        <input type="hidden" name="user_add" value="1">
        <input type="submit" value="'._G('SBGT_adminusers_newsubmit').'">
    </div>

</form>
</div>';

?>

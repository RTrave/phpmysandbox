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

if(MySBRoleHelper::checkAccess('example_role')) {
    _incG("template1.php",'example','');
    _incT("template2",'example');
}
echo 'example_file1.php loaded !!! <br>';

echo '

Test overlay<br>
<a  href="index.php?mod=example&amp;tpl=example_file2" 
    style="text-decoration:none"
    class="overlayed">
        <img    src="images/icons/text-editor.png" 
                alt="Edition" 
                style="width: 24px"></a>

</a>
<br>';
$example_code1 = '
<div class="boxed">
    <div class="row">
        <div class="right">Text</div>
        Text
    </div>
</div>';

echo '
<code>'.MySBUtil::str2html(htmlentities($example_code1)).'</code>
<div class="boxed">';

//$example_users = MySBUserHelper::load();
$req_example_users = MySBDB::query('SELECT * FROM '.MySB_DBPREFIX."users");

while( $user_data=MySBDB::fetch_array($req_example_users) ) {
    //$user = MySBUserHelper::getByID($user_data['id']);
    $user = new MySBUser(-1, $user_data);
    $app->tpl_example_user = $user;
    echo '
<div id="user'.$user->id.'" class="row">';
    _incI("item","example");
    echo '
</div>';
}

echo '
</div>';

?>


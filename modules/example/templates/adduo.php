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


$user = MySBUserHelper::getByID($_POST['example_user']);

//$app->pushMessage("Example user ID: ".$user->id);
    echo "
<script>
    loadItem('user".$user->id."','index.php?mod=example&inc=item&userid=".$user->id."')";
    echo "
</script>";

?>

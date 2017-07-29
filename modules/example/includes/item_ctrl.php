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


if( isset($app->tpl_example_user) )
    $user = $app->tpl_example_user;
else
    $user = MySBUserHelper::getByID($_GET['userid']);

?>

    <div class="right" style="width: auto; text-align: right;">
        <form action="index.php?mod=example&amp;inc=adduo"
              method="post" 
              class="hidelayed"
              style="display: inline-block;">
            <input type="hidden" name="example_user" value="<?= $user->id ?>">
            <input type="hidden" name="reset" value="1">
            <input src="images/icons/list-remove.png"
                   type="image"
                   alt="ALT text"
                   title="TITLE text">
        </form>
        <form action="index.php?mod=example&amp;inc=adduo"
              method="post" 
              class="hidelayed"
              style="display: inline-block;">
            <input type="hidden" name="example_user" value="<?= $user->id ?>">
            <input src="images/icons/list-add.png"
                   type="image"
                   alt="ALT text"
                   title="TITLE text">
        </form>
    </div>
    <b><?= $user->lastname ?></b> <?= $user->firstname ?> (exvarchar: <?= $user->exvarchar ?>)

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


if( isset($example_user) )
    $user = $example_user;
else
    $user = MySBUserHelper::getByID($_GET['userid']);

?>

    <div class="col-8">
    <b><?= $user->lastname ?></b> <?= $user->firstname ?><br>
    <span class="help">(exvarchar: <?= $user->exvarchar ?>)</span>
    </div>
    <div class="col-4 t-right" style="text-align: right;">
        <form action="index.php?mod=example&amp;inc=adduo"
              method="post" 
              class="hidelayed"
              style="display: inline-block; float: right; width: auto;">
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
              style="display: inline-block; float: right; width: auto;">
            <input type="hidden" name="example_user" value="<?= $user->id ?>">
            <input src="images/icons/list-add.png"
                   type="image"
                   alt="ALT text"
                   title="TITLE text">
        </form>
    </div>


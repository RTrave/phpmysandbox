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

if(!MySBRoleHelper::checkAccess('example_role')) die;
?>

Test overlay<br>
<a  href="index.php?mod=example&amp;tpl=overlay_foot" 
    style="text-decoration:none"
    class="overlayed">
        <img    src="images/icons/text-editor.png" 
                alt="ALT text" 
                title="TITLE text"
                style="width: 24px">
</a>
<br>

<div class="boxed">

<?php foreach($example_users as $example_user) { ?>
    <div id="user<?= $example_user->id ?>" class="row">
    <?php include(_pathI("item_ctrl","example")) ?>
    </div>
<?php } ?>

</div>

<?php 
echo $file1->getCode();
echo $file2->getCode();
echo $file3->getCode();
?>


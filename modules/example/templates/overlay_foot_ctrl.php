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

if(!MySBRoleHelper::checkAccess('example_role')) return;

echo '
<div class="overlaySize" 
    data-overheight=""
    data-overwidth="350"></div>

<div class="overHead">OVERLAY with footer</div>

<div class="overBody">

<div class="list_support" style="text-align: justify;">
    <div class="row">overlay_foot loaded !!! <br>
        <a  href="index.php?mod=example&amp;tpl=overlay_wo_foot" 
            style="text-decoration:none"
            class="overlayed">
            <img    src="images/icons/text-editor.png" 
                    alt="Edition" 
                    style="width: 24px"></a>
    </div>
    <div class="row">
';

?>
<br>
<?php
$file1 = new ExampleLib('templates/overlay_foot_ctrl.php');
echo $file1->getCode();
?>
</div>
<div class="row">
    <div class="right">
        <form method="post" action="index.php?mod=example&amp;tpl=overlay_wo_foot" id="foo" class="overlayed">
            <input type="text" name="data" />
            <input type="submit" value="Test">
        </form></div>
    <b>Test form</b>
    <div class="help">Retrieval of shorthand CSS properties (e.g., margin, background, border), although functional with some browsers, is not guaranteed. For example, if you want to retrieve the rendered border-width, use: $( elem ).css( "borderTopWidth" ), $( elem ).css( "borderBottomWidth" ), and so on.</div>
</div>
</div>
</div>

<div class="overFoot"><span>Over FOOT</span></div>



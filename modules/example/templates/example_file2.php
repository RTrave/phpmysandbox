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

<div class="overHead"><div>example file 2</div></div>

<div class="overBody">
<div class="list_support" style="text-align: justify;">
    <div class="row">example_file2.php loaded !!! <br>
        <a  href="index.php?mod=example&amp;tpl=admin_example" 
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
YUY<br>
The .css() method is a convenient way to get a style property from the first matched element, especially in light of the different ways browsers access most of those properties (the getComputedStyle() method in standards-based browsers versus the currentStyle and runtimeStyle properties in Internet Explorer) and the different terms browsers use for certain properties. For example, Internet Explorer's DOM implementation refers to the float property as styleFloat, while W3C standards-compliant browsers refer to it as cssFloat. For consistency, you can simply use "float", and jQuery will translate it to the correct value for each browser.<br>
<br>
Also, jQuery can equally interpret the CSS and DOM formatting of multiple-word properties. For example, jQuery understands and returns the correct value for both .css( "background-color" ) and .css( "backgroundColor" ). Different browsers may return CSS color values that are logically but not textually equal, e.g., #FFF, #ffffff, and rgb(255,255,255).<br>
</div>
<div class="row">
    <div class="right">
        <form method="post" action="index.php?mod=example&amp;tpl=admin_example" id="foo" class="overlayed">
            <input type="text" name="data" />
            <input type="submit" value="Test">
        </form></div>
    <b>Test form</b>
    <div class="help">Retrieval of shorthand CSS properties (e.g., margin, background, border), although functional with some browsers, is not guaranteed. For example, if you want to retrieve the rendered border-width, use: $( elem ).css( "borderTopWidth" ), $( elem ).css( "borderBottomWidth" ), and so on.</div>
</div>
</div>
</div>

<div class="overFoot"><span>Over FOOT</span></div>



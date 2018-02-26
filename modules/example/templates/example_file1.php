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

<div class="navbar" id="Testnav">
<ul>
  <li>
    <a href="index.php"
       title="Title">
      Item1</a>
  </li><li class="icon-responsive">
    <a href="javascript:void(0);"
            onclick="responsiveToggle('Testnav','navbar')">
      <img src="images/icons/view-list.png" alt="view-list">
    </a>
  </li><li>
    <a href="index.php"
       title="Title">
    Item2</a>
  </li><li class="no-collapse">
    <a href="index.php"
       title="Title">
    Item3</a>
  </li><li class="dropdown" id="TestDropDown">
    <a href="javascript:void(0)" class="dropbtn"
       onclick="dropdownToggle('TestDropDown','dropdown')">
      <img src="images/icons/view-list.png" alt="view-list">More</a>
    <div class="dropdown-content">
      <div class="dropdown-item">
        <span>DD item1</span>
      </div>
      <div class="dropdown-item">
        <a href="#"
           title="DDitem2">DD item2 Test</a>
      </div>
      <div class="dropdown-item">
        <a href="#"
           title="DDitem3">DD item3</a>
      </div></div>
  </li><li class="right">
    <a href="index.php"
       title="Title">
    Item4</a>
  </li>
</ul></div>



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


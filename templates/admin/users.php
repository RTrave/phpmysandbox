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
?>

<div class="row">

<?php include( _pathI('admin/menu') ); ?>

<div class="col-lg-9">


<?php if( isset($_POST['users_search']) ) { ?>
<div class="content list">
  <h1><?= _G('SBGT_adminusers') ?></h1>
<?php
  foreach( $found_users as $user ) {
    echo '
  <div id="user'.$user->id.'" class="row">';
    include( _pathI('admin/user_display_ctrl') );
    echo '</div>';
  }
?>
</div>
<?php } ?>

<div class="content">

  <h1><?= _G('SBGT_adminusers_search') ?></h1>
  <form action="index.php?tpl=admin/users" method="post">

  <div class="row">
    <label for="bylogin">
    <div class="col-sm-4">
        <p><?= _G('SBGT_adminusers_searchbylogin') ?></p>
    </div>
    <div class="col-sm-8">
        <input type="text" name="bylogin" id="bylogin" value="<?= $bylogin ?>">
    </div>
    </label>
  </div>
  <div class="row">
    <label for="bylastname">
    <div class="col-sm-4">
        <p><?= _G('SBGT_adminusers_searchbylastname') ?></p>
    </div>
    <div class="col-sm-8">
        <input type="text" name="bylastname" id="bylastname" value="<?= $bylastname ?>">
    </div>
    </label>
  </div>
  <div class="row">
    <label for="bymail">
    <div class="col-sm-4">
        <p><?= _G('SBGT_adminusers_searchbymail') ?></p>
    </div>
    <div class="col-sm-8">
        <input type="text" name="bymail" id="bymail" value="<?= $bymail ?>">
    </div>
    </label>
  </div>

  <div class="row">
    <div class="col-sm-3"></div>
    <div class="col-sm-6">
        <input type="hidden" name="users_search" value="1">
        <input type="submit" value="<?= _G('SBGT_search') ?>">
    </div>
    <div class="col-sm-3"></div>
  </div>

  </form>
</div>


<div class="content">

  <h1><?= _G('SBGT_adminusers_new') ?></h1>
  <form action="index.php?tpl=admin/users" method="post">

  <div class="row">
    <label for="user_login">
    <div class="col-sm-4">
        <p><?= _G('SBGT_login') ?></p>
    </div>
    <div class="col-sm-8">
        <input type="text" name="user_login" id="user_login" value="">
    </div>
    </label>
  </div>
  <div class="row">
    <label for="user_lastname">
    <div class="col-sm-4">
        <p><?= _G('SBGT_lastname') ?></p>
    </div>
    <div class="col-sm-8">
        <input type="text" name="user_lastname" id="user_lastname" value="">
    </div>
    </label>
  </div>
  <div class="row">
    <label for="user_firstname">
    <div class="col-sm-4">
        <p><?= _G('SBGT_firstname') ?></p>
    </div>
    <div class="col-sm-8">
        <input type="text" name="user_firstname" id="user_firstname" value="">
    </div>
    </label>
  </div>
  <div class="row">
    <label for="user_mail">
    <div class="col-sm-4">
        <p><?= _G('SBGT_mail') ?></p>
    </div>
    <div class="col-sm-8">
        <input type="email" name="user_mail" id="user_mail" value="">
    </div>
    </label>
  </div>


  <div class="row">
    <div class="col-sm-3"></div>
    <div class="col-sm-6">
      <input type="hidden" name="users_search" value="1">
      <input type="hidden" name="user_add" value="1">
      <input type="submit"  class="danger"
             value="<?= _G('SBGT_adminusers_newsubmit') ?>">
    </div>
    <div class="col-sm-3"></div>
  </div>

  </form>
</div>

</div>
</div>


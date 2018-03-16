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

$httpbase = 'index.php?tpl=admin/admin&amp;page=users';

global $app;
global $groups_a;
?>


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
  <form action="<?= $httpbase ?>" method="post">

  <div class="row label">
    <label class="col-sm-4" for="bylogin">
      <?= _G('SBGT_adminusers_searchbylogin') ?>
    </label>
    <div class="col-sm-8">
      <input type="text" name="bylogin" id="bylogin" value="<?= $bylogin ?>">
    </div>
  </div>

  <div class="row label">
    <label class="col-sm-4" for="bylastname">
      <?= _G('SBGT_adminusers_searchbylastname') ?>
    </label>
    <div class="col-sm-8">
      <input type="text" name="bylastname" id="bylastname" value="<?= $bylastname ?>">
    </div>
  </div>

  <div class="row label">
    <label class="col-sm-4" for="bymail">
      <?= _G('SBGT_adminusers_searchbymail') ?>
    </label>
    <div class="col-sm-8">
      <input type="text" name="bymail" id="bymail" value="<?= $bymail ?>">
    </div>
  </div>

  <div class="row">
    <div class="col-sm-3"></div>
    <div class="col-sm-6">
        <input type="hidden" name="users_search" value="1">
        <input type="submit" class="btn-primary"
               value="<?= _G('SBGT_search') ?>">
    </div>
    <div class="col-sm-3"></div>
  </div>

  </form>
</div>


<div class="content">

  <h1><?= _G('SBGT_adminusers_new') ?></h1>
  <form action="<?= $httpbase ?>" method="post">

  <div class="row label">
    <label class="col-sm-4" for="user_login">
      <?= _G('SBGT_login') ?>
    </label>
    <div class="col-sm-8">
      <input type="text" name="user_login" id="user_login" value="">
    </div>
  </div>

  <div class="row label">
    <label class="col-sm-4" for="user_lastname">
      <?= _G('SBGT_lastname') ?>
    </label>
    <div class="col-sm-8">
      <input type="text" name="user_lastname" id="user_lastname" value="">
    </div>
  </div>

  <div class="row label">
    <label class="col-sm-4" for="user_firstname">
      <?= _G('SBGT_firstname') ?>
    </label>
    <div class="col-sm-8">
      <input type="text" name="user_firstname" id="user_firstname" value="">
    </div>
  </div>

  <div class="row label">
    <label class="col-sm-4" for="user_mail">
      <?= _G('SBGT_mail') ?>
    </label>
    <div class="col-sm-8">
      <input type="email" name="user_mail" id="user_mail" value="">
    </div>
  </div>


  <div class="row">
    <div class="col-sm-3"></div>
    <div class="col-sm-6">
      <input type="hidden" name="users_search" value="1">
      <input type="hidden" name="user_add" value="1">
      <input type="submit"  class="btn-danger"
             value="<?= _G('SBGT_adminusers_newsubmit') ?>">
    </div>
    <div class="col-sm-3"></div>
  </div>

  </form>
</div>


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

if(!MySBRoleHelper::checkAccess('admin')) return;

global $_GET;
if ( !isset($_GET['page']) or $_GET['page']=='' )
  $_GET['page'] = 'main';

function isActive($tpl_code) {
  if( $_GET['page']==$tpl_code )
    return 'no-collapse';
  else return '';
}


include(MySB_ROOTPATH.'/config.php');
?>

<div class="row">

<div class="col-lg-3">

<?php include( _pathI('admin/menu') ); ?>

</div>
<div class="col-lg-9">

<?php
if( isset($_GET['module']) )
  include( _pathT($_GET['page'].'_ctrl',$_GET['module']) );
else
  include( _pathT('admin/'.$_GET['page'].'_ctrl') );
?>

</div>
</div>

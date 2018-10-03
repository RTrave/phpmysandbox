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

global $matched_flag;
$matched_flag = 0;
function isActive($tpl_code) {
  global $matched_flag;
  if( $_GET['page']==$tpl_code ) {
    $matched_flag = 1;
    return 'no-collapse';
  } elseif( isset($_GET['module']) AND $_GET['page']==$tpl_code ) {
    $matched_flag = 1;
    return 'no-collapse';
  } else return '';
}
function isForceCollapse() {
  global $matched_flag;
  if( $matched_flag==1 )
    return ' force-collapse';
  else return '';
}

include(MySB_ROOTPATH.'/config.php');
?>

<div class="row">

<div class="col-lg-3 t-center">

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

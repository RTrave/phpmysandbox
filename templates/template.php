<?php 
/***************************************************************************
 *
 *   phpMySandBox/RSVP module - TRoman<abadcafe@free.fr> - 2012
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License', or
 *   ('at your option) any later version.
 *
***************************************************************************/

// No direct access.
defined('_MySBEXEC') or die;

?><!DOCTYPE html>
<html lang="<?= $app->locales->t_locale ?>">
<head><?php include(_pathI('head')) ?>
</head>
<body>
<noscript>
  <div class="advert"
       style="background-color: #ffe4e7; border: 4px solid #ffab67; font-size: 24px;">
    Javascript needed but not activated.
  </div>
</noscript>

<div id="mysbSpin">
  <div id="spinlayer"></div>
</div>
<div id="mysbOverlay" class="overlay">
<div id="mysbModal" class="mysb_overlay modal">
  <div class="close btn-light"
       title="<?= _G('SBGT_overlay_close') ?>">
    <img src="images/window-close-48.png" alt="">
  </div>
  <div class="contentWrap" id="contentWrap">...</div>
</div>
</div>
<div id="mysbMessages">
</div>
<div id="hidelayer">
</div>


<div id="mysbBody" style="min-height: 240px;">

<?php if($app->show_menu) { ?>
<div id="mysbTop" class="roundtop">
<?php include(_pathI('navbar_top')) ?>
</div>
<?php } ?>

<div id="mysbMiddle">

<?= $app->content['template'] ?>

</div>

<?php if($app->show_menu) { ?>
<div id="mysbBottom">
<?php include(_pathI('navbar_bottom')) ?>
</div>
<?php } ?>


</div>


<script type="text/javascript">
loadSpin();
wrapLayerCalls();
</script>

<div id="mysbLogSql">
<?= $app->logsqlWrite() ?>
</div>

</body>
</html>

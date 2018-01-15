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
    <div class="advert" style="background-color: #ffe4e7; border: 4px solid #ffab67; font-size: 24px;">Javascript needed but not activated.</div><br></noscript>
<div id="spinlayer">
</div>
<div id="overlayBg">
</div>
<script>
desactiveOverlay();
</script>

<div id="allshadow">

<?php 
if( $app->show_topmenu ) { ?>
<div id="mysbTop" class="roundtop">
<?php include(_pathI('top')) ?>
</div>

<div id="mysbMiddle">
<?php 
} else { ?>
<div id="mysbMiddle" class="roundtop">
<?php 
} ?>

<div class="content"> 

<div id="mysbMessages">
</div>
<div id="overlay" class="mysb_overlay roundtop">
    <div class="close" >
    <img src="images/window-close32.png"
         alt="<?= _G('SBGT_overlay_close') ?>"
         title="<?= _G('SBGT_overlay_close') ?>">
    </div>
    <div class="contentWrap" id="contentWrap">...</div>
</div>
<div id="hidelayer">
</div>
<script type="text/javascript">
loadSpin();
</script>

<?= $app->content['template'] ?>

<script type="text/javascript">
wrapLayerCalls();
</script>

</div>
</div>

<div id="mysbBottom" class="roundbottom">
<?php include(_pathI('bottom')) ?>
</div>

</div>

<div id="mysbLogSql">
<?= $app->logsqlWrite() ?>
</div>

</body>
</html>

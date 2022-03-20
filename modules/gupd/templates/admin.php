<?php
/**
 * phpMySandBox - GitUpdate module
 * 
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License', or
 * ('at your option) any later version. 
 * (Roman Travé <roman.trave@abadcafe.org>, 2022)
 *
 * @package    phpMySandBox\GUpd
 * @license    http://opensource.org/licenses/gpl-license.php  GNU Public License
 * @author     Roman Travé <roman.trave@abadcafe.org>
 */


// No direct access.
defined('_MySBEXEC') or die;


echo '
<h1 class="bg-primary">GitHub Updates</h1>
<div id="gupd_updater">

<div id="gupd_core">';
include(_pathI('core_ctrl','gupd'));
echo '
</div>

<div id="gupd_coremaster">';
$master = true;
include(_pathI('coremaster_ctrl','gupd'));
echo '
</div>

</div>';

?>


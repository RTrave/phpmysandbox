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

//global $app;
?>


<div class="content">

<?php 
echo '
<b>'.MySBConfigHelper::Value('website_name').'</b>';
?>

<div class="techinfo">
<?php 
echo '
    <a href="mailto:'.MySBConfigHelper::Value('technical_contact').'">Contact</a> - 
    <a  href="https://github.com/RTrave/phpmysandbox"
        target="_blank">PhpMySandBox 0.6</a>';

$modules = MySBModuleHelper::load();
if(count($modules)!=0) {
    echo ' ( mod';
    foreach($modules as $module) 
        if($module->isLoaded()) {
            //$init_file = 'modules/'.$module->name.'/__init.php';
            //$classmod = 'MySBModule_'.$module->name;
            //$cmod = new $classmod;
            $cmod = $module->module_helper;
            if( isset($cmod->homelink) and  isset($cmod->lname) )
                echo '  <a  href="'.$cmod->homelink.'"
                        target="_blank"
                        title="module '.$cmod->lname.' v:'.$cmod->version.'">'.$module->name.'</a>';
            else
                echo ' '.$module->name;
        }
    echo ' ) ';
}

echo '
- <i>'.strftime("%b %Y").'</i>
';
?>
</div>
</div>



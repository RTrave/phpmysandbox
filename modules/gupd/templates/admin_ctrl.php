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

global $app;

if(!MySBRoleHelper::checkAccess('admin')) return;


/*
$urlforcast="https://api.github.com/repos/RTrave/phpmysandbox/releases";
$opts = [
        'http' => [
                'method' => 'GET',
                'header' => [
                        'User-Agent: PHP'
                ]
        ]
];
$context = stream_context_create($opts);
$content = file_get_contents($urlforcast, false, $context);

//var_dump($content);


//$json=file_get_contents($urlforcast);
$data=json_decode($content,true);

function dumpjson($name, $value, $i)
{
  echo $i.' '.$name.' => ';
  if(is_array($value)) {
    foreach($value as $name1 => $value1) {
      echo '<br>'; 
      dumpjson($name1, $value1, $i+1);
    }
  } else
    echo $value.'<br>';
}
*/
//$data1 = $data[0];
/*
foreach($data['list'] as $day => $value) { 
  $desc = $value['weather'][0]['description'];
  $max_temp = $value['temp']['max'];
  $min_temp = $value['temp']['min'];
  $pressure = $value['pressure'];
}
*/
//var_dump($data);

include(_pathT("admin","gupd"));

?>


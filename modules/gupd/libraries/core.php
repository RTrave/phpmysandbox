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
 * @subpackage Libraries\Core
 * @license    http://opensource.org/licenses/gpl-license.php  GNU Public License
 * @author     Roman Travé <roman.trave@abadcafe.org>
 */


// No direct access.
defined('_MySBEXEC') or die;


/**
 * Event class
 *
 */
class GUpdCore {

  public $isUpdate = false;
  public $jsonfile = MySB_GUPDFiles.'gitapi.json';
  //public $content = null;
  //public $json_content = null;
  public $mysb_core = null;
  public $next_version = null;

	/**
	 * Constructor.
	 */
  public function __construct() {
    global $app;

    $this->mysb_core = new MySBCore();
    if(!file_exists($this->jsonfile)) {
      return;
    }
    $content = file_get_contents($this->jsonfile);
    //$this->json_content=json_decode($this->content,true);
    $this->process_json($content);
    $this->isUpdate = true;
  }

	/**
	 * Update releases infos.
	 */
  public function update() {
    global $app;

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
    $this->content = file_get_contents($urlforcast, false, $context);
    //$this->json_content=json_decode($this->content,true);
    $jsonfile = fopen(MySB_GUPDFiles."gitapi.json", "w") 
      or die("Unable to open file!");
    //$txt = "Donald Duck\n";
    fwrite($jsonfile, $this->content);
    //$txt = "Goofy Goof\n";
    //fwrite($myfile, $txt);
    fclose($jsonfile);
  }

  private function dumpjson($name, $value, $i)
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

	/**
	 * Constructor.
	 */
  public function display_json() {
    global $app;

    foreach($this->json_content as $name => $value) { 
      //echo $name.' => '.var_dump($value).'<br>';
      echo 'Version: '.$value['tag_name'].'<br>';
      //dumpjson($name, $value,1);
    }

  }

  private function process_json($content) {
    $version_act = 'rel'.
                   $this->mysb_core->mysb_major_version.'.'.
                   $this->mysb_core->mysb_minor_version;
    $version = '';
    $json_content=json_decode($content,true);
    foreach($json_content as $name => $value) { 
      //echo $name.' => '.var_dump($value).'<br>';
      //echo $value['tag_name'].'/'.$version_act.'<br>';
      if($value['tag_name']==$version_act) {
        if($version=='') {
          //echo 'NO';
          $this->next_version = null;
          return;
        } else {
          //echo 'YES';
          $this->next_version = $version;
          return;
        }
      }
      $version = $value['tag_name'];
      //$vertext .= 'Version: '.$value['tag_name'].'<br>';
      //dumpjson($name, $value,1);
    }
  }

	/**
	 * Get next version.
	 */
  public function next_version() {
    global $app;
    if(!$this->isUpdate) {
      return 'No file load.';
    }
    $vertext = ''.$this->next_version;
    
    return $vertext;
  }

	/**
	 * Get next version.
	 */
  public function update_available() {
    global $app;
    if(!$this->isUpdate) {
      return 'No file load.';
    }
    if(!$this->next_version)
      return 'No updates available.';
    $vertext = ''.$this->next_version;
    
    return $vertext;
  }


}

?>


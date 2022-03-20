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
  public $master = false;

	/**
	 * Constructor.
	 */
  public function __construct($master=false) {
    global $app;

    $this->mysb_core = new MySBCore();
    if($master) {
      $this->master = true;
      return;
    }
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
    $content = file_get_contents($urlforcast, false, $context);
    //$this->json_content=json_decode($this->content,true);
    $jsonfile = fopen(MySB_GUPDFiles."gitapi.json", "w") 
      or die("Unable to open file!");
    //$txt = "Donald Duck\n";
    fwrite($jsonfile, $content);
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

	/**
	 * Constructor.
	 */
  public function actual_version() {
    global $app;

    return 'rel'.
           $this->mysb_core->mysb_major_version.'.'.
           $this->mysb_core->mysb_minor_version;

  }

  private function process_json($content) {
    $version_act = $this->actual_version();
    $version = '';
    $version_array = null;
    $json_content=json_decode($content,true);
    foreach($json_content as $name => $value) { 
      //echo $name.' => '.var_dump($value).'<br>';
      //echo $value['tag_name'].'/'.$version_act.'<br>';
      if($value['tag_name']==$version_act) {
        if($version=='') {
          //echo 'NO';
          $this->next_version = null;
          $this->next_version_array = null;
          return;
        } else {
          //echo 'YES';
          $this->next_version = $version;
          $this->next_version_array = $version_array;
          return;
        }
      }
      $version = $value['tag_name'];
      $version_array = $value;
      //$vertext .= 'Version: '.$value['tag_name'].'<br>';
      //dumpjson($name, $value,1);
    }
  }

	/**
	 * Get next version.
	 */
  public function next_version() {
    global $app;
    if($this->master)
      return 'github master branch';
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
    if($this->master)
      return 'github master branch';
    if(!$this->isUpdate) {
      return 'No file load.';
    }
    if(!$this->next_version)
      return 'No updates available.';
    $vertext = ''.$this->next_version;
    
    return $vertext;
  }

	/**
	 * Get next version.
	 */
  public function update_available_infos() {
    global $app;
    if($this->master)
      return 'TODO';
    if(!$this->isUpdate) {
      return '';
    }
    if(!$this->next_version)
      return 'No updates available.';
    $vertext = ''.$this->next_version_array['body'];
    
    return $vertext;
  }




	/**
	 * Get next version.
	 */
  private function unzip($archive) {
    global $app;
    $zip = new ZipArchive;
    $res = $zip->open($archive);
    if ($res === TRUE) {
      $zip->extractTo(MySB_ROOTPATH.'/files/gupd/archive/');
      $zip->close();
      //echo 'woot!';
    } else {
      //echo 'doh!';
    }
  }


	/**
	 * Get next version.
	 */
  private function geturl($archive) {
    // Initialize a file URL to the variable
    $url = 
    'https://media.geeksforgeeks.org/wp-content/uploads/gfg-40.png';
      
    // Use basename() function to return the base name of file
    $file_name = MySB_ROOTPATH.'/files/gupd/'.basename($archive);
      
    //$urlforcast="https://api.github.com/repos/RTrave/phpmysandbox/releases";
    $opts = [
      'http' => [
        'method' => 'GET',
        'header' => [
          'User-Agent: PHP'
        ]
      ]
    ];
    $context = stream_context_create($opts);
    //$content = file_get_contents($urlforcast, false, $context);
    // Use file_get_contents() function to get the file
    // from url and use file_put_contents() function to
    // save the file by using base name
    if (file_put_contents($file_name, file_get_contents($archive, false, $context)))
    {
        echo "File downloaded successfully";
        return $file_name;
    }
    else
    {
        echo "File downloading failed.";
    }
    return null;
  }



	/**
	 * Prepare next version.
	 */
  public function prepare() {
    global $app;
    if($this->master) {
      $zip = MySBUtil::geturl('https://github.com/RTrave/phpmysandbox/archive/refs/heads/master.zip','gupd/');
      MySBUtil::delete('gupd/archive');
      MySBUtil::mkdir('gupd/archive');
      MySBUtil::unzip($zip,'gupd/archive');
      return true;
    }
    if(!$this->isUpdate) {
      return false;
    }
    if(!$this->next_version) {
      //echo 'TEST';
      //MySBUtil::recurseCopy(MySB_ROOTPATH.'/libraries',
      //                      MySB_ROOTPATH.'/files/gupd/libraries');
      //MySBUtil::delete('libraries','');
      //MySBUtil::recurseCopy(MySB_ROOTPATH.'/files/gupd/libraries',
      //                      MySB_ROOTPATH.'/libraries');
      //MySBUtil::delete('gupd/libraries');
      return false;
    }
    //$app->LOG( $this->next_version_array['zipball_url']);
    $zip = MySBUtil::geturl($this->next_version_array['zipball_url'],'gupd/');
    MySBUtil::delete('gupd/archive');
    MySBUtil::mkdir('gupd/archive');
    MySBUtil::unzip($zip,'gupd/archive');
    
    return true;
  }

	/**
	 * Install next version.
	 */
  public function upgrade() {
    global $app;
    $directory = opendir(MySB_ROOTPATH.'/files/gupd/archive');
    while(($folder = readdir($directory ))!==false) {
      if ($folder === '.' || $folder === '..') 
        continue;
      $archfolder = $folder;
    }
    $archpath = MySB_ROOTPATH.'/files/gupd/archive/'.$archfolder.'/';
    $app->LOG( 'Upgrade from: '.$archpath, 'gupd');
    //if(!($updfile=fopen($archpath."update.ini", "r"))) 
    //  if (!($updfile=fopen(MySB_ROOTPATH."/modules/gupd/update.ini", "r"))) 
    //    or return;
    if(file_exists($archpath."gupd_update.ini"))
      $lines = file($archpath."gupd_update.ini");
      //$updfile=fopen($archpath."modules/gupd/update.ini", "r");
    else if(file_exists(MySB_ROOTPATH."/gupd_update.ini"))
      $lines = file(MySB_ROOTPATH."/gupd_update.ini");
      //$updfile=fopen(MySB_ROOTPATH."/modules/gupd/update.ini", "r");
    else 
      return false;
    $app->LOG( $archpath."gupd_update.ini\n".
               MySB_ROOTPATH."/gupd_update.ini", 'gupd');
    //$content = file_get_contents($updfile);
    //$lines = file($updfile);
    $backup = MySBUtil::mkdir('gupd/archive/backup/');
    foreach($lines as $line) {
      //$fold = explode('\n',$line);
      //$line = $fold[0];
      $linek = str_replace( "\n", '', $line );
      if($linek!='') {
        if (is_dir(MySB_ROOTPATH.'/'.$linek) === false) {
            copy(MySB_ROOTPATH.'/'.$linek,
                 MySB_ROOTPATH.'/'.$backup.$linek);
        }
        else
          MySBUtil::recurseCopy($linek,
                                $backup,
                                $linek);
        $app->LOG( 'Update file: '.
                   MySB_ROOTPATH.'/'.$linek."\n".
                   MySB_ROOTPATH.'/'.$backup, 
                   'gupd');
      }
    }
    //fclose($updfile);
    return true;
  }

}

?>


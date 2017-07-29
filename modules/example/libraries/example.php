<?php
/***************************************************************************
 *
 *   phpMySandBox - TRoman<abadcafe@free.fr> - 2017
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License', or
 *   ('at your option) any later version.
 *
***************************************************************************/

// No direct access.
defined('_MySBEXEC') or die;

global $app;

class ExampleLib {

    /**
     * @var         string          Name of the file
     */
    public $file_name = '';

    /**
     * @var         string          Content of the file
     */
    public $file_content = '';

    /**
     * ExampleLib constructor.
     * param    string      file to display
     */
    public function __construct($file) {
        $this->file_name = $file;
        $this->file_content = str_replace('</textarea>','</textare_a>',
            file_get_contents(MySB_ROOTPATH.'/modules/example/'.$file));
    }

    /**
     * Get HTML code of the file
     */
    public function getCode() {
        return '<br>
<h2>'.$this->file_name.'</h2><br><br>
<textarea rows="8" cols="60" readonly style="font-size: 80%;">'.$this->file_content.'</textarea><br><br>';
    }
    

}


?>

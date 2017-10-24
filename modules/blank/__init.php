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

class MySBModule_blank {

    public $lname = 'blank';
    public $version = 1;
    public $homelink = 'https://github.com/RTrave/phpmysandbox/tree/master/modules/blank';
    public $require = array(
        'core' => 7,
        'example' => 3
        );

    public function create() {
        global $app;
    }

    public function delete() {
        global $app;
    }

    public function init1() {
        global $app;
    }


    public function uninit() {
        global $app;
    }

}
?>

<?php
/**
 * phpMySandBox - Simple Database Framework in PHP
 *
 * Menu Item plugin support library.
 * 
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License', or
 * ('at your option) any later version. 
 * (Roman Travé <roman.trave@gmail.com>, 2012)
 *
 * @package    phpMySandBox
 * @subpackage Libraries\Plugins
 * @license    http://opensource.org/licenses/gpl-license.php  GNU Public License
 * @author     Roman Travé <roman.trave@gmail.com>
 */

// No direct access.
defined('_MySBEXEC') or die;

/**
 * MenuItem plugin class
 * value0       Anchor text
 * value1       name of template
 * value2       Menu tip
 * ivalue0      Menu level
 *
 * @package    phpMySandBox
 * @subpackage Libraries\Plugins
 */
class MySBPluginMenuItem extends MySBPlugin {

    /**
     * Plugin constructor.
     * @param   array   $plugin            Parameters of plugin
     */
    public function __construct($plugin = array()) {
        parent::__construct((array) ($plugin));
    }

    /**
     * Display Menuitem HTML entity (<A>..</A>)
     * @param   integer $level      Level of menu: 0=admin menu,1=lvl1, 2=lvl2, 
     */
    public function displayA($level) {
        global $app;
        $code = '';
        if( isset($app->auth_user) and 
            MySBRoleHelper::checkAccess($this->role,false) and 
            $level==$this->ivalue0) {
            if( $this->ivalue0==3 ) 
                $code .= '
<a href="index.php?module='.$this->module.'&amp;tpl=admin/admin&amp;page='.$this->value1.'"
   title="'._G($this->value2).'">'._G($this->value0).'</a>';
            else 
                $code .= '
<a href="index.php?mod='.$this->module.'&amp;tpl='.$this->value1.'" 
   title="'._G($this->value2).'">'._G($this->value0).'</a>';
        }
        return $code;
    }

    /**
     * Display Menuitem HTML entity (<A>..</A>)
     * @param   integer $level      Level of menu: 0=admin menu,1=lvl1, 2=lvl2,
     */
    public function displayMenuItem($level) {
        global $app;
        $code = '';
        if( isset($app->auth_user) and
            MySBRoleHelper::checkAccess($this->role,false) and
            $level==$this->ivalue0) {
            if( $level==1 )
                $code .= '
<a href="index.php?mod='.$this->module.'&amp;tpl='.$this->value1.'"
   title="'._G($this->value2).'">'._G($this->value0).'</a>';
            else
                $code .= '
<a href="index.php?mod='.$this->module.'&amp;tpl='.$this->value1.'"
   class="dropdown-item" title="'._G($this->value2).'">'._G($this->value0).'</a>';
        }
        return $code;
    }

    /**
     * Html form (plugin edition)
     * @return  string          HTML entity output
     */
    public function html_valueform() {
        global $app;
        $output = '';
        $output .= '
<div class="row label">
  <label class="col-sm-4" for="plg_optval_level">
    Menu Level (1 or 2)
  </label>
  <div class="col-sm-8">
    <input type="text" name="plg_optval_level" id="plg_optval_level"
           value="'.$this->ivalue0.'">
  </div>
</div>
<div class="row label">
  <label class="col-sm-4" for="plg_optval_text">
    Anchor text
  </label>
  <div class="col-sm-8">
    <input type="text" name="plg_optval_text" id="plg_optval_text"
           value="'.$this->value0.'">
  </div>
</div>
<div class="row label">
  <label class="col-sm-4" for="plg_optval_tiptext">
    Tooltip text
  </label>
  <div class="col-sm-8">
    <input type="text" name="plg_optval_tiptext" id="plg_optval_tiptext"
           value="'.$this->value2.'">
  </div>
</div>';
        return $output;
    }

    /**
     * Html form process (plugin edition)
     */
    public function html_valueprocess() {
        global $app, $_POST;
        $this->update(array(
            'ivalue0' => $_POST['plg_optval_level'],
            'value0' => $_POST['plg_optval_text'],
            'value2' => $_POST['plg_optval_tiptext'] ));
    }

}

?>

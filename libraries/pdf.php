<?php
/**
 * phpMySandBox - Simple Database Framework in PHP
 *
 * PDF handling (via TCPDF http://www.tcpdf.org ) library class.
 * 
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License', or
 * ('at your option) any later version. 
 * (Roman Travé <roman.trave@gmail.com>, 2012)
 *
 * @package    phpMySandBox
 * @subpackage Libraries\Core
 * @license    http://opensource.org/licenses/gpl-license.php  GNU Public License
 * @author     Roman Travé <roman.trave@gmail.com>
 */


// No direct access.
defined('_MySBEXEC') or die;

include(MySB_ROOTPATH.'/config.php');

if( file_exists(MySB_ROOTPATH.'/vendor/tecnickcom/tcpdf/tcpdf.php') ) {

    define('MySB_TCPDF', MySB_ROOTPATH.'/vendor/tecnickcom/tcpdf/tcpdf.php');

} elseif( file_exists(MySB_ROOTPATH.'/vendor/tecnickcom/tcpdf_min/tcpdf.php') ) {

    define('MySB_TCPDF', MySB_ROOTPATH.'/vendor/tecnickcom/tcpdf_min/tcpdf.php');

} else { 

    define('MySB_TCPDF', MySB_ROOTPATH.'/vendor/tecnickcom/tcpdf_min/tcpdf.php');
    return;

}

include(MySB_TCPDF);


/**
 * TCPDF library implementation class.
 *
 * @package    phpMySandBox
 * @subpackage Libraries\Core
 */
class MySBPDF extends TCPDF {

    /**
	 * @var     string          HTML input to write to PDF
	 */
	private $pdf_htmlcontent = '';

    /**
	 * @var     string          CSS added to HTML write
	 */
	private $pdf_css = '';

    /**
	 * @var     string          TCPDF version
	 */
	public $mytcpdf_version = '';


    /**
     * Constructor.
     */
    public function __construct($title='No title', $subject='No subject') {
        global $app;
        parent::__construct(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        // set document information
        $this->SetCreator(PDF_CREATOR);
        $this->SetAuthor('phpMySandBox');
        $this->SetTitle($title);
        $this->SetSubject($subject);
        $this->setPrintHeader(false);
        $this->setPrintFooter(false);
        $this->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $this->getLibVersion();
    }

    /**
     * Get TCPDF version from lib comments.
     */
    private function getLibVersion() {
        $vlines = file(MySB_TCPDF);
        $versionl = explode('// ',$vlines[3]);
        $versionl1 = explode(' : ',$versionl[1]);
        $this->mytcpdf_version = $versionl1[1];
    }

    /**
     * Add a header CSS to PDF.
     * @param   string      $css_file         Header to add (null to reset)
     */
    public function addCSS($css_file=null) {
        if( $css_file==null )
            $this->pdf_css = '';
        else
            $this->pdf_css .= '<link rel="stylesheet" type="text/css" href="'.$css_file.'">';
    }

    /**
     * Append HTML code for PDF generation.
     * @param   string      $html           
     */
    public function HTML($html) {
        $this->pdf_htmlcontent .= $html;
    }

    /**
     * Write HTML code for PDF generation.
     */
    private function real_writeHTML() {
        $html_input = '
<head>
    '.$this->pdf_css.'
</head> '.$this->pdf_htmlcontent.'
';
        $this->writeHTML($html_input, false, false, true, false, '');
        $this->pdf_htmlcontent = '';
    }


    /**
     * Add a new page.
     */
    public function NewPage() {
        if( $this->pdf_htmlcontent!='' )
            $this->real_writeHTML();
        parent::AddPage();
    }

    /**
     * PDF browser output (plugin/download).
     * @param   string      $filename           PDF File name
     * @param   boolean     $force_download     "Save as" browser mode
     * @param   boolean     $store              Store generated PDF
     * @return  string                          Path to the stored file
     */
    public function OutputBrowser($filename,$force_download=false,$store=false) {
        if( $this->pdf_htmlcontent!='' )
            $this->real_writeHTML();
        if( $store ) {
            $storecode = 'F';
            $filepath = MySB_ROOTPATH.'/tmp/'.$filename;
        } else {
            $storecode = '';
            $filepath = $filename;
        }
        if( $force_download )
            $this->Output($filepath, $storecode.'D');
        else 
            $this->Output($filepath, $storecode.'I');
        return $filepath;
    }

    /**
     * PDF file output.
     * @param   string      $filename           PDF File name
     * @return  string                          Path to the stored file
     */
    public function OutputFile($filename) {
        if( $this->pdf_htmlcontent!='' )
            $this->real_writeHTML();
        $filepath = MySB_ROOTPATH.'/tmp/'.$filename;
        $this->Output($filepath, 'F');
        return $filepath;
    }

}

?>

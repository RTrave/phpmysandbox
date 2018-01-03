<?php
/**
 * phpMySandBox - Simple Database Framework in PHP
 *
 * Mail handling library class and factory.
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


/**
 * Mail API.
 *
 * @package    phpMySandBox
 * @subpackage Libraries\APIs
 */

interface MySBIMail {

    /**
     * Add a header to mail.
     * @param   string      $header         header to add
     */
    public function addHeader($header);

    /**
     * Add a TO recipient to mail.
     * @param   string      $address           mail address to add
     * @param   string      $gecko          gecko mail
     */
    public function addTO($address,$gecko='');

    /**
     * Add a CC recipient to mail.
     * @param   string      $address           mail address to add
     * @param   string      $gecko          gecko mail
     */
    public function addCC($address,$gecko='');

    /**
     * Add a BCC recipient to mail.
     * @param   string      $address           mail address to add
     * @param   string      $gecko          gecko mail
     */
    public function addBCC($address,$gecko='');

    /**
     * Clear all recipients in mail.
     */
    public function clearRecipients();

    /**
     * Set the address for reply to mail.
     * @param   string      $address           mail address to reply
     * @param   string      $gecko          gecko mail
     */
    public function setReplyTo($address,$gecko='');

    /**
     * Add an attachment to mail.
     * @param   string      $file           file path to attach
     * @param   string      $filename       file abitrary name
     */
    public function addAttachment($file,$filename='');

    /**
     * Send the mail.
     * @param   string      $c_subject      final subject of mail
     * @param   string      $c_body         final body of mail
     * @return  string                      return error if sent failed
     */
    public function send($c_subject,$c_body);

    /**
     * Close and unset mail process.
     */
    public function close();

}


/**
 * Mail class.
 *
 * @package    phpMySandBox
 * @subpackage Libraries\Core
 */
class MySBMail {

    /**
	 * @var    string       mail template
	 */
	protected $template = '';

    /**
	 * @var    string       module creating object
	 */
	protected $module = '';

    /**
	 * @var    array        mail TO adress array
	 */
	protected $to = array();

    /**
	 * @var    array        mail CC adress array
	 */
	protected $cc = array();

    /**
	 * @var    array        mail BCC adress array
	 */
	protected $bcc = array();

    /**
	 * @var    array        mail template subject
	 */
	public $subject = array();

    /**
	 * @var    array        mail template body
	 */
	public $body = array();

    /**
	 * @var    array        mail template footer
	 */
	public $foot = array();

    /**
	 * @var    array        added footers
	 */
	protected $footer_add = array();

    /**
	 * @var    array        mail template datas
	 */
	public $data = array();

    /**
	 * @var    MySBIMail    mail plugin object
	 */
	protected $mailobj = null;

    /**
	 * @var    string       mail computed subject
	 */
	protected $c_subject = '';

    /**
	 * @var    string       mail computed body
	 */
	protected $c_body = '';

    /**
	 * @var    boolean      mail default template (site adress)
	 */
	protected $default_footer = 'mail_tplfoot.php';

    /**
	 * @var    string       error string
	 */
	protected $error = '';


    /**
     * Constructor.
     * @param   string   $template          template used for the mail body
     * @param   string   $module            module calling the mail factory
     */
    public function __construct($template,$module='') {
        global $app;
        $this->template = 'mail_'.$template.'.php';
        $this->module = $module;
        include(MySB_ROOTPATH.'/config.php');
        if( $mysb_mail=='' )
            $app->displayStopAlert('$mysb_mail not set in config.php!!!');
        if( $mysb_ext_mail=='' ) 
            $mysb_ext_mail = 'Native';
        $MailClass = 'MySBMail'.$mysb_ext_mail;
        $this->mailobj = new $MailClass();
    }

    /**
     * Add a header to mail.
     * @param   string      $header         Header to add
     */
    public function addHeader($header) {
        $this->mailobj->addHeader($header);
    }

    /**
     * Add addresses in TO recipient.
     * @param   string   $addresses              mails added (coma separated)
     * @param   string   $gecko             gecko associated
     */
    public function addTO($addresses,$gecko='') {
        $mails = explode(',',$addresses);
        foreach($mails as $address) 
            $this->to[$address] = $gecko;
    }
    
    /**
     * Add addresses in CC recipient.
     * @param   string   $addresses              mails added (coma separated)
     * @param   string   $gecko             gecko associated
     */
    public function addCC($addresses,$gecko='') {
        $mails = explode(',',$addresses);
        foreach($mails as $address) 
            $this->cc[$address] = $gecko;
    }

    /**
     * Add addresses in BCC recipient.
     * @param   string   $addresses              mails added (coma separated)
     * @param   string   $gecko             gecko associated
     */
    public function addBCC($addresses,$gecko='') {
        $mails = explode(',',$addresses);
        foreach($mails as $address) 
            $this->bcc[$address] = $gecko;
    }

    /**
     * Clear addresses in recipients.
     */
    public function clearRecipients() {
        $this->to = array();
        $this->cc = array();
        $this->bcc = array();
        $this->mailobj->clearRecipients();
    }

    /**
     * Change ReplyTo address.
     * @param   string   $address              mail
     * @param   string   $gecko             gecko associated
     */
    public function setReplyTo($address,$gecko='') {
        $this->mailobj->setReplyTo($address,$gecko);
    }

    /**
     * Unset the default footer template.
     * @param   string   $template           template to remplace default footer
     */
    public function setFooter($template) {
        $this->default_footer = $template;
    }

    /**
     * Add a footer string.
     * @param   string   $footer              footer string to add
     */
    public function addFooter($footer) {
        $this->footer_add[] = $footer;
    }

    /**
     * Add attachment to the mail.
     * @param   string      $file           file path to attach
     * @param   string      $filename       file abitrary name
     */
    public function addAttachment($file,$filename='') {
        $this->mailobj->addAttachment($file,$filename);
    }

    /**
     * Compute subject, body and footer against datas.
     */
    protected function loadBody() {
        global $app, $mail;
        $mail = $this;
        if( !$app->locales->includeTemplate($this->template,$this->module) )
            $app->locales->includeTemplate($this->template);
        if( !$app->locales->includeTemplate($this->default_footer) )
            $this->foot = '';
        foreach($this->footer_add as $footeradd)
            $this->foot .= $footeradd."\r\n";
        $this->c_subject = '['.MySBConfigHelper::Value('website_name').'] '.$this->subject;
        $this->c_body = $this->body.$this->foot;
    }

    /**
     * Send the mail.
     * @param   boolean     $and_close      close the mail after sending it (default: true)
     * @return  boolean                     return error if sent failed
     */
    public function send($and_close=true) {
        global $app;
        if($this->c_subject=='')
            $this->loadBody();
        foreach($this->to as $address=>$gecko)
            $this->mailobj->addTO($address,$gecko);
        foreach($this->cc as $address=>$gecko)
            $this->mailobj->addCC($address,$gecko);
        foreach($this->bcc as $address=>$gecko)
            $this->mailobj->addBCC($address,$gecko);
        if( ($error=$this->mailobj->send($this->c_subject,$this->c_body))!='') {
            $this->error .= $error;
            if( $this->error!='' )
                $app->LOG("MySBMail::send($and_close): ".$this->error);
            if( $and_close )
                $this->mailobj->close();
            return false;
        }
        if( $and_close )
            $this->mailobj->close();
        return true;
    }

    /**
     * Send the mail to each recipient individually.
     * @return  boolean                     return error if a sent failed
     */
    public function sendBCCIndividually() {
        global $app;
        if($this->c_subject=='')
            $this->loadBody();
        $response = true;
        foreach($this->bcc as $address=>$gecko) {
            $this->mailobj->addTO($address,$gecko);
            if( ($error=$this->mailobj->send($this->c_subject,$this->c_body))!='') {
                $this->error .= $error;
                $response = false;
            }
            $this->mailobj->clearRecipients();
        }
        return $response;
    }

    /**
     * Close and unset mail process.
     */
    public function close() {
        $this->mailobj->close();
    }

    /**
     * Get error details.
     * @return  string                  error string
     */
    public function getError() {
        return $this->error;
    }

}


/**
 * PHP Mail native implementation class.
 *
 * @package    phpMySandBox
 * @subpackage Libraries\Core
 */
class MySBMailNative implements MySBIMail {

    /**
	 * @var    string           mail attachment files path
	 */
	protected $attfiles = '';

    /**
	 * @var    string           mail headers
	 */
	protected $headers = '';

    /**
	 * @var    array            mail headers
	 */
	protected $headers_add = array();

    /**
	 * @var    string           destination adress
	 */
	protected $to = '';

    /**
	 * @var    string           Reply-To adress
	 */
	protected $replyto = '';


    /**
     * Constructor.
     */
    public function __construct() {
        global $app;
        $this->createHeaders();
    }

    /**
     * Create headers for the current mail.
     */
    private function createHeaders() {
        global $app;
        include(MySB_ROOTPATH.'/config.php');
        $this->headers = 'From: '.MySBConfigHelper::Value('website_name').' <'.$mysb_mail.'>
Content-Type: text/plain; charset="UTF-8" format=flowed
Content-Transfer-Encoding: 8bit
MIME-Version: 1.0
X-Priority: 1
User-Agent: PHP '.phpversion().'
Message-ID: <'.rand(10000000,99999999).'.'.$mysb_mail.'>
X-Sender: '.$mysb_mail.'
Date: '.date('r').'
';
    }

    /**
     * Add a header to mail.
     * @param   string      $header         Header to add
     */
    public function addHeader($header) {
        $this->headers_add[] = $header;
    }

    /**
     * Add addresses in TO recipient.
     * @param   string   $address              mail added
     * @param   string   $gecko             gecko associated
     */
    public function addTO($address,$gecko='') {
        if( $this->to ) $this->to .= ',';
        $this->to .= $gecko.' <'.$address.'>';
    }

    /**
     * Add addresses in CC recipient.
     * @param   string   $address              mails added (coma separated)
     * @param   string   $gecko             gecko associated
     */
    public function addCC($address,$gecko='') {
        $this->headers .= 'CC: '.$gecko.' <'.$address.'>'."\r\n";
    }

    /**
     * Add addresses in BCC recipient.
     * @param   string   $address              mails added (coma separated)
     * @param   string   $gecko             gecko associated
     */
    public function addBCC($address,$gecko='') {
        $this->headers .= 'Bcc: '.$gecko.' <'.$address.'>'."\r\n";
    }

    /**
     * Clear all recipients in mail.
     */
    public function clearRecipients() {
        $this->to = '';
        $this->createHeaders();
    }

    /**
     * Change ReplyTo address.
     * @param   string   $address              mail added
     * @param   string   $gecko             gecko associated
     */
    public function setReplyTo($address,$gecko='') {
        $this->replyto = $gecko.' <'.$address.'>';
    }

    /**
     * Add attachment to the mail.
     * @param   string      $file           file path to attach
     * @param   string      $filename       file abitrary name
     */
    public function addAttachment($file,$filename='') {
        global $app;
        $app->LOG('MySBMailNative: attachments not supported without PHPMailer for now');
    }

    /**
     * Send the mail.
     * @param   string      $c_subject      final subject of mail
     * @param   string      $c_body         final body of mail
     * @return  string                      return error if sent failed
     */
    public function send($c_subject,$c_body) {
        global $app;
        include(MySB_ROOTPATH.'/config.php');
        foreach($this->headers_add as $header) 
            $this->headers .= $header."\r\n";
        if($this->replyto!='') 
            $this->headers .= 'Reply-To: '.$this->replyto."\r\n";
        if($this->to=='') 
            $this->to = $mysb_mail;
        mail($this->to,$c_subject,MySBUtil::html2str($c_body),$this->headers);
        return '';
    }

    /**
     * Close and unset mail process.
     */
    public function close() {

    }

}


?>

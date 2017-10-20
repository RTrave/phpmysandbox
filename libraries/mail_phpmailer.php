<?php
/**
 * phpMySandBox - Simple Database Framework in PHP
 *
 * PHPMailer handling library class.
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
if( $mysb_ext_mail=='PHPMailer' and 
    file_exists(MySB_ROOTPATH.'/phpmailer.conf.php') ) {

    include(MySB_ROOTPATH.'/phpmailer.conf.php');
    require (MySB_ROOTPATH.'/'.$phpmailer_path.'/PHPMailerAutoload.php');

    /**
     * PHPMailer extention class (dealing with Imap folders).
     *
     * @package    phpMySandBox
     * @subpackage Libraries\Core
     */
    class PHPMailer_copysent extends PHPMailer {

        /**
          * Constructor.
          * @param boolean $exceptions Should we throw external exceptions?
          */
        public function __construct($exceptions) {
            global $app;
            parent::__construct($exceptions);
        }

        /**
         * Append sent message to Sent folder
         */
        public function imap_append_msg() {
            include(MySB_ROOTPATH.'/phpmailer.conf.php');
            if( !isset($phpmailer_ImapHost) or !function_exists('imap_open') ) return;
            $sent_msg = $this->MIMEHeader.$this->MIMEBody;
            $ImapStream = imap_open(    "{".$phpmailer_ImapHost.":".$phpmailer_ImapPort."}",
                                        $this->Username,
                                        $this->Password, NULL, 1 );
            $folder = "{".$phpmailer_ImapHost."}".$phpmailer_ImapFolder;
            imap_append($ImapStream, $folder, $sent_msg, "\\Seen");
            imap_close($ImapStream);
        }
        
    }
}


/**
 * PHPMailer library mail implementation class.
 *
 * @package    phpMySandBox
 * @subpackage Libraries\Core
 */
class MySBMailPHPMailer implements MySBIMail {

    /**
	 * @var     array           mail BCC adress array
	 */
	public $attfiles = null;

    /**
	 * @var     PHPMailer       mail PHPMailer object
	 */
	public $mail = null;

    /**
	 * @var     PHPMailer       mail have a TO recipient
	 */
	public $to_flag = false;


    /**
     * Constructor.
     */
    public function __construct() {
        global $app;
        include(MySB_ROOTPATH.'/phpmailer.conf.php');
        require_once (MySB_ROOTPATH.'/'.$phpmailer_path.'/class.phpmailer.php');
        $this->mail = new PHPMailer_copysent(true);
        $this->mail->SetLanguage("en",$phpmailer_path.'/language/');
        $this->mail->IsSMTP();
        $this->mail->SMTPDebug = false;
        $this->mail->Host = $phpmailer_Host;
        $this->mail->Port = $phpmailer_Port;
        $this->mail->SMTPAuth = $phpmailer_SMTPAuth;
        $this->mail->SMTPSecure = $phpmailer_SMTPSecure;
        $this->mail->Username = $phpmailer_Username;
        $this->mail->Password = $phpmailer_Password;
        $this->mail->CharSet = 'UTF-8';

        if( isset($phpmailer_SMTPDebug) )
             $this->SMTPDebug = $phpmailer_SMTPDebug;
        else $this->SMTPDebug = 0;
        $this->mail->SetFrom(
            $phpmailer_Mail,
            MySBConfigHelper::Value('website_name') );

    }

    /**
     * Add a header to mail.
     * @param   string      $header         Header to add
     */
    public function addHeader($header) {
        $this->mail->AddCustomHeader($header);
    }

    /**
     * Add addresses in TO recipient.
     * @param   string   $mail              mails added (coma separated)
     * @param   string   $gecko             gecko associated
     */
    public function addTO($mail,$gecko='') {
        $this->mail->AddAddress($mail,$gecko);
        $this->to_flag = true;
    }
    
    /**
     * Add addresses in CC recipient.
     * @param   string   $mail              mails added (coma separated)
     * @param   string   $gecko             gecko associated
     */
    public function addCC($mail,$gecko='') {
        $this->mail->AddCC($mail,$gecko);
    }

    /**
     * Add addresses in BCC recipient.
     * @param   string   $mail              mails added (coma separated)
     * @param   string   $gecko             gecko associated
     */
    public function addBCC($mail,$gecko='') {
        $this->mail->AddBCC($mail,$gecko);
    }

    /**
     * Clear all recipients in mail.
     */
    public function clearRecipients() {
        $this->mail->ClearAllRecipients();
    }

    /**
     * Change ReplyTo address.
     * @param   string   $mail              mail added
     * @param   string   $gecko             gecko associated
     */
    public function setReplyTo($mail,$gecko='') {
        $this->mail->addReplyTo($mail,$gecko);
    }

    /**
     * Add attachment to the mail.
     * @param   string      $file           file path to attach
     * @param   string      $filename       file abitrary name
     */
    public function addAttachment($file,$filename='') {
        $this->mail->AddAttachment($file,$filename);
    }

    /**
     * Prepare HTML body with CSS.
     * @param   string      $c_body         body of mail
     * @return  string                      emmbeded body
     */
    private function body_prepare($c_body) {
        global $mail_css;
        include( _pathI('mailcss') );
        $comp_body = '
<html>
<head>
<style type="text/css">
'.$mail_css.'
</style>
</head>
<body>
<div class="content">
'.MySBUtil::str2html($c_body).'
</div>
</body>
</html>
';
        return $comp_body;
    }

    /**
     * Send the mail.
     * @param   string      $c_subject      final subject of mail
     * @param   string      $c_body         final body of mail
     * @return  string                      return error if sent failed
     */
    public function send($c_subject,$c_body) {
        global $app;
        if( $this->mail->Subject=='' ) {
            $this->mail->IsHTML(true);
            $this->mail->Subject = $c_subject;
            $this->mail->Body = $this->body_prepare($c_body);
            $this->mail->AltBody = MySBUtil::html2str($c_body);
            $this->mail->WordWrap = 80;
        }
        if( $this->SMTPDebug==1 ) {
            $this->mail->SMTPDebug = 1;
            $this->mail->Debugoutput = 'html';
        }
        if( !$this->to_flag ) {
            $this->mail->AddAddress(MySBConfigHelper::Value('technical_contact'),MySBConfigHelper::Value('website_name'));
            $this->to_flag = true;
        }
        $current_mail = clone $this->mail;
        try {
            $current_mail->Send();
        } catch (phpmailerException $e) {
            return $e->errorMessage();
        } catch (Exception $e) {
            return $e->getMessage();
        }
        $current_mail->imap_append_msg();
        unset($current_mail);
        return '';
    }

    /**
     * Close and unset mail process.
     */
    public function close() {
        $this->mail->SmtpClose();
        if( isset($this->mail) ) 
            unset($this->mail);
    }

}

?>

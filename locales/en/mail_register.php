<?php
    global $mail;

    $mail->subject = 'Welcome';

    $mail->body = 'hi '.$mail->data['geckos'].',<br>
<br>
you\'ve been registred on '.MySBConfigHelper::Value('website_name').' with:<br>
login: '.$mail->data['login'].'<br>
passwd: '.$mail->data['password'].'<br>
<br>
<i>On HOTMAIL, LIVE or MSN mails, mark this message as safe!</i><br>
<br>';

?>

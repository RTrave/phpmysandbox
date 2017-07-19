<?php
    global $mail;

    $mail->subject = 'User Option change';

    $mail->body = 'Hi '.$mail->data['geckos'].'<br>
<br>
Lastname: '.$mail->data['lastname'].'<br>
Firstname:'.$mail->data['firstname'].'<br>
Mail: '.$mail->data['mail'].'<br>
<br>
Option: '.$mail->data['optioninfos'].'<br>
Status: <b>'.$mail->data['status'].'</b><br>
<br>
';

?>

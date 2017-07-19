<?php
    global $mail;

    $mail->subject = 'New password';

    $mail->body = 'hi '.$mail->data['geckos'].'<br>
<br>
your password is reset .. your login is now:<br>
login: '.$mail->data['login'].'<br>
passwd: '.$mail->data['password'].'<br>
<br>';

?>

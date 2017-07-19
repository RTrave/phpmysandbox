<?php
    global $mail;

    $mail->subject = 'New user';

    $mail->body = 'a new user has been registred:<br>
<br>
'.$mail->data['userinfos'].'<br>
login: '.$mail->data['login'].'<br>
mail: '.$mail->data['mail'].'<br>
<br>';

?>

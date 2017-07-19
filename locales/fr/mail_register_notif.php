<?php
    global $mail;

    $mail->subject = 'Nouvel utilisateur';

    $mail->body = 'un nouvel utilisateur s\'est inscrit:<br>
<br>
'.$mail->data['userinfos'].'<br>
login: '.$mail->data['login'].'<br>
mail: '.$mail->data['mail'].'<br>
<br>';

?>

<?php
    global $mail;

    $mail->subject = 'Option utilisateur changee';

    $mail->body = 'Hiho '.$mail->data['geckos'].'<br>
<br>
Nom: '.$mail->data['lastname'].'<br>
Prénom: '.$mail->data['firstname'].'<br>
Mail: '.$mail->data['mail'].'<br>
<br>
Option: '.$mail->data['optioninfos'].'<br>
Status: <b>'.$mail->data['status'].'</b><br>
<br>
';

?>

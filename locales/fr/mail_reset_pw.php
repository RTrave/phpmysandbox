<?php
    global $mail;

    $mail->subject = 'Nouveau mot de passe';

    $mail->body = 'bonjour '.$mail->data['geckos'].'<br>
<br>
votre mot de passe est réinitialisé .. vos informations de connection sont maintenant:<br>
login: '.$mail->data['login'].'<br>
mot de passe: '.$mail->data['password'].'<br>
<br>';

?>

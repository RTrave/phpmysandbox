<?php
    global $mail;

    $mail->subject = 'Bienvenue';

    $mail->body = 'bonjour '.$mail->data['geckos'].'<br>
<br>
vous vous êtes inscrit sur '.MySBConfigHelper::Value('website_name').' <br>
et pouvez vous connecter avec les informations suivantes:<br>
login: '.$mail->data['login'].'<br>
mot de passe: '.$mail->data['password'].'<br>
<br>
<i>Sur HOTMAIL, LIVE ou MSN, pensez à marquer ce message comme sûr!</i><br>
<br>';

?>

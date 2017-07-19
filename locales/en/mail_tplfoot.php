<?php
    global $mail;

    $siteurl = 'http://'.$_SERVER['SERVER_NAME'].$_SERVER['SCRIPT_NAME'];
    $mail->foot = '
<br>
--<br>
the team of <b>"'.MySBConfigHelper::Value('website_name').'"</b>.<br>
<a href="'.$siteurl.'">'.$siteurl.'</a><br>';

?>

<?php
exit(); // do nothing
$to      = 'askubicz@svsu.edu';
$subject = 'svsu/fr registration';
$message = 'click this link to confirm';
$headers = 'From: askubicz@svsu.edu' . "\r\n" .
    'Reply-To: askubicz@svsu.edu' . "\r\n" .
    'X-Mailer: PHP/' . phpversion();

mail($to, $subject, $message, $headers);
?>
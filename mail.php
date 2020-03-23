<?php
exit(); // do nothing
$to      = 'eerokita@svsu.edu';
$subject = 'svsu/fr registration';
$message = 'click this link to confirm';
$headers = 'From: eerokita@svsu.edu' . "\r\n" .
    'Reply-To: eerokita@svsu.edu' . "\r\n" .
    'X-Mailer: PHP/' . phpversion();

mail($to, $subject, $message, $headers);
?>
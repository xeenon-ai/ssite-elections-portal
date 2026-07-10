<?php

require 'vendor/autoload.php';
require 'config/mail.php';

$mail = getMailer();

$mail->addAddress("YOURPERSONALEMAIL@gmail.com");

$mail->Subject = "Test";

$mail->Body = "Hello";

$mail->send();

echo "Success";
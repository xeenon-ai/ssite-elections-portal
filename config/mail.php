<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../vendor/autoload.php';

function getMailer()
{
    $mail = new PHPMailer(true);
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'ssiteelections@gmail.com';
    $mail->Password = 'ntkjevezrccshffz';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;
    $mail->SMTPDebug = 2;
$mail->Debugoutput = 'html';

    $mail->CharSet = 'UTF-8';

    $mail->setFrom(
        'ssiteelections@gmail.com',
        'SSITE Elections Portal'
    );

    return $mail;
}
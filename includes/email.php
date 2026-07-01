<?php

require_once __DIR__ . '/../config/mail.php';

function sendOTP($email, $fullname, $otp)
{
    try {

        $mail = getMailer();

        $mail->addAddress($email, $fullname);

        $mail->isHTML(true);

        $mail->Subject = "SSITE Elections Verification Code";

        $mail->Body = "
        <div style='font-family:Arial;padding:30px'>

            <h2 style='color:#0d3b66'>
                SSITE Elections Portal
            </h2>

            <p>Hello <b>{$fullname}</b>,</p>

            <p>Your verification code is:</p>

            <div style='
                font-size:40px;
                letter-spacing:10px;
                font-weight:bold;
                color:#0d6efd;
                text-align:center;
                margin:30px 0;
            '>

                {$otp}

            </div>

            <p>
                This code will expire in
                <b>5 minutes</b>.
            </p>

            <hr>

            <small>

            Student Society of Information Technology Education

            </small>

        </div>
        ";

        return $mail->send();

    } catch (Exception $e) {

        return false;

    }
}
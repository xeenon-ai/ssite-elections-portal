<?php

require_once __DIR__ . '/../config/mail.php';

function sendOTPEmail($email, $fullname, $otp)
{
    try {

        $mail = getMailer();

        $mail->addAddress($email, $fullname);

        $mail->isHTML(true);

        $mail->Subject = "SSITE Elections Portal - Verification Code";

        $mail->Body = "
        <!DOCTYPE html>
        <html>
        <body style='margin:0;padding:40px;background:#f5f7fa;font-family:Arial,sans-serif;'>

        <table width='100%' cellpadding='0' cellspacing='0'>
            <tr>
                <td align='center'>

                    <table width='600' style='background:#ffffff;border-radius:12px;padding:40px;'>

                        <tr>
                            <td align='center'>

                                <h2 style='color:#001F54;margin-bottom:10px;'>

                                    🗳 SSITE Elections Portal

                                </h2>

                                <p>

                                    Student Society of Information Technology Education

                                </p>

                                <hr>

                                <p>Hello <strong>{$fullname}</strong>,</p>

                                <p>

                                    Use the verification code below to continue.

                                </p>

                                <div style='
                                    font-size:42px;
                                    font-weight:bold;
                                    letter-spacing:10px;
                                    color:#001F54;
                                    margin:30px 0;
                                '>

                                    {$otp}

                                </div>

                                <p>

                                    This code expires in
                                    <strong>5 minutes</strong>.

                                </p>

                                <hr>

                                <small>

                                    If you did not request this code,
                                    you can safely ignore this email.

                                </small>

                            </td>

                        </tr>

                    </table>

                </td>

            </tr>

        </table>

        </body>
        </html>
        ";

        return $mail->send();

    }

    catch(Exception $e){

        return $e->getMessage();

    }

}
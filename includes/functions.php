<?php

function clean($data){

return htmlspecialchars(trim($data));

}

function generateOTP(){

return random_int(100000,999999);

}

function isValidPhinmaEmail($email){

return preg_match('/^[A-Za-z0-9._%+-]+@phinmaed\.com$/',$email);

}

function redirect($url){

header("Location: ".$url);

exit();

}   

function logActivity($pdo, $userType, $userId, $activity)
{

    $stmt = $pdo->prepare("
        INSERT INTO activity_logs
        (
            user_type,
            user_id,
            activity,
            ip_address
        )
        VALUES
        (
            ?, ?, ?, ?
        )
    ");

    $stmt->execute([

        $userType,

        $userId,

        $activity,

        $_SERVER['REMOTE_ADDR']

    ]);

}
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
<?php

require_once 'includes/email.php';

$result = sendOTPEmail(
    'Jafe.santiago.sjc@phinmaed.com',
    'Jake',
    '123456'
);

var_dump($result);
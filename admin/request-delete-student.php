<?php

require_once "../config/config.php";
require_once "../config/database.php";
require_once "../includes/session.php";
require_once "../includes/functions.php";
require_once "../includes/email.php";

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: students.php");
    exit();
}

$studentId = (int)$_GET['id'];

$stmt = $pdo->prepare("
    SELECT *
    FROM students
    WHERE id = ?
");

$stmt->execute([$studentId]);

$student = $stmt->fetch();

if(!$student){
    header("Location: students.php");
    exit();
}

// Generate OTP
$otp = random_int(100000,999999);

$expires = date(
    "Y-m-d H:i:s",
    time()+OTP_EXPIRY
);

// Save OTP
$stmt = $pdo->prepare("
INSERT INTO admin_otps
(
admin_id,
student_id,
otp_code,
expires_at
)
VALUES
(
?,?,?,?
)
");

$stmt->execute([
    $_SESSION['admin_id'],
    $studentId,
    $otp,
    $expires
]);

// Save student ID temporarily
$_SESSION['delete_student_id']=$studentId;

// Send Email
sendOTPEmail(
    ADMIN_EMAIL,
    ADMIN_NAME,
    $otp
);

header("Location: verify-admin-otp.php");
exit();
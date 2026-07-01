<?php

require_once 'config/database.php';
require_once 'includes/session.php';

if (!isset($_SESSION['signup_student'])) {
    header("Location: signup.php");
    exit();
}

$error = "";

if ($_SERVER['REQUEST_METHOD'] == "POST") {

    $otp = trim($_POST['otp']);

    $stmt = $pdo->prepare("
        SELECT *
        FROM otp_codes
        WHERE student_id = ?
        ORDER BY id DESC
        LIMIT 1
    ");

    $stmt->execute([
        $_SESSION['signup_student']
    ]);

    $record = $stmt->fetch();

    if (!$record) {

        $error = "OTP not found.";

    }

    elseif ($record['verified']) {

        $error = "OTP already used.";

    }

    elseif (strtotime($record['expires_at']) < time()) {

        $error = "OTP expired.";

    }

    elseif ($record['otp_code'] != $otp) {

        $error = "Invalid OTP.";

    }

    else {

        $pdo->prepare("
            UPDATE otp_codes
            SET verified = 1
            WHERE id = ?
        ")->execute([$record['id']]);

        $pdo->prepare("
            UPDATE students
            SET is_verified = 1
            WHERE id = ?
        ")->execute([
            $_SESSION['signup_student']
        ]);

        unset($_SESSION['signup_student']);
        unset($_SESSION['demo_signup_otp']);

        header("Location: login.php?registered=1");
        exit();

    }

}

$pageTitle = "Verify Registration";
include 'includes/header.php';
?>

<div class="container py-5">

<div class="row justify-content-center">

<div class="col-md-5">

<div class="card shadow">

<div class="card-body p-4">

<h3>Verify Your Account</h3>

<p>Enter the OTP sent to your email.</p>

<div class="alert alert-info">

<strong>Demo OTP:</strong>

<?= $_SESSION['demo_signup_otp']; ?>

</div>

<?php if($error): ?>

<div class="alert alert-danger">

<?= htmlspecialchars($error); ?>

</div>

<?php endif; ?>

<form method="POST">

<input
type="text"
name="otp"
class="form-control mb-3"
maxlength="6"
required>

<button class="btn btn-primary w-100">

Verify Account

</button>

</form>

</div>

</div>

</div>

</div>

</div>

<?php include 'includes/footer.php'; ?>
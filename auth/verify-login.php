<?php

$pageTitle = "Login Verification";

require_once "../config/config.php";
require_once "../config/database.php";
require_once "../includes/functions.php";
require_once "../includes/session.php";

if (!isset($_SESSION['login_student'])) {

    header("Location: login.php");
    exit();

}

$error = "";

$studentId = $_SESSION['login_student'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $otp = clean($_POST['otp']);

    if (empty($otp)) {

        $error = "Please enter the verification code.";

    } else {

        $stmt = $pdo->prepare("
            SELECT *
            FROM otp_codes
            WHERE student_id = ?
            AND otp_code = ?
            AND purpose = 'login'
            AND verified = 0
            ORDER BY id DESC
            LIMIT 1
        ");

        $stmt->execute([$studentId, $otp]);

        $record = $stmt->fetch();

        if (!$record) {

            $error = "Invalid verification code.";

        } elseif (strtotime($record['expires_at']) < time()) {

            $error = "Verification code has expired.";

        } else {

            $stmt = $pdo->prepare("
                UPDATE otp_codes
                SET verified = 1,
                    used_at = NOW()
                WHERE id = ?
            ");

            $stmt->execute([$record['id']]);

            $_SESSION['student_id'] = $studentId;

            unset($_SESSION['login_student']);

            header("Location: ../student/dashboard.php");
            exit();

        }

    }

}

include "../includes/header.php";
?>

<?php if (!empty($error)): ?>

<script>
document.addEventListener("DOMContentLoaded", function(){

Swal.fire({

icon:"error",

title:"Verification Failed",

text:"<?= htmlspecialchars($error, ENT_QUOTES); ?>"

});

});
</script>

<?php endif; ?>

<section class="py-5 dashboard-section">
<div class="container">

<div class="row justify-content-center">

<div class="col-lg-5">

<div class="card dashboard-card border-0">

<div class="card-body p-5">

<div class="text-center mb-4">

<img src="<?= BASE_URL ?>assets/images/ssite-logo.png" width="90" class="mb-3">

<div class="alert alert-light border rounded-4 mb-4">

<i class="bi bi-envelope-check-fill text-primary me-2"></i>

A verification code has been sent to your registered email.
The code is valid for <strong>5 minutes</strong>.

</div>

<h2 class="fw-bold text-primary mb-2">

<i class="bi bi-shield-lock-fill me-2"></i>

Login Verification

</h2>

<p class="text-muted">

Enter the 6-digit verification code sent to your registered email address.

</p>

</div>

<form method="POST">

<div class="mb-4">

<label class="form-label">

Verification Code

</label>

<div class="input-group">

<span class="input-group-text">

<i class="bi bi-key-fill"></i>

</span>

<input
type="text"
name="otp"
maxlength="6"
class="form-control form-control-lg text-center"

style="letter-spacing:8px;font-size:24px;font-weight:bold;"

placeholder="123456"

autocomplete="one-time-code"

required>

</div>

</div>

<button
type="submit"
class="btn btn-primary btn-lg rounded-pill w-100">

Verify Login

</button>

<p class="text-center text-muted small mt-3 mb-0">

<i class="bi bi-clock-history me-1"></i>

Didn't receive the code? Check your spam folder or request a new login.

</p>

</form>

<div class="text-center mt-4">

<a
href="<?= BASE_URL ?>auth/login.php"
class="text-decoration-none">

<i class="bi bi-arrow-left-circle me-1"></i>

Back to Login

</a>

</div>

</div>

</div>

</div>

</div>

</div>

</section>

<?php include "../includes/footer.php"; ?>
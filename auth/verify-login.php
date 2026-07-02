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

<section class="py-5" style="background:#f5f7fa;min-height:90vh;">

<div class="container">

<div class="row justify-content-center">

<div class="col-lg-5">

<div class="card border-0 shadow-lg rounded-4">

<div class="card-body p-5">

<div class="text-center mb-4">

<img src="<?= BASE_URL ?>assets/images/ssite-logo.png" width="90" class="mb-3">

<h2 class="fw-bold text-primary">

Login Verification

</h2>

<p class="text-muted">

Enter the 6-digit code sent to your email.

</p>

</div>

<form method="POST">

<div class="mb-4">

<label class="form-label">

Verification Code

</label>

<input
type="text"
name="otp"
maxlength="6"
class="form-control form-control-lg text-center"
placeholder="123456"
required>

</div>

<button
type="submit"
class="btn btn-primary btn-lg w-100">

Verify Login

</button>s

</form>

</div>

</div>

</div>

</div>

</div>

</section>

<?php include "../includes/footer.php"; ?>
<?php

$pageTitle = "Verify Account";

require_once "../config/config.php";
require_once "../config/database.php";
require_once "../includes/functions.php";
require_once "../includes/session.php";

if (!isset($_SESSION['signup_student'])) {
    header("Location: signup.php");
    exit();
}

$error = "";

$studentId = $_SESSION['signup_student'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $otp = clean($_POST['otp']);

    if (empty($otp)) {

        $error = "Please enter your verification code.";

    } else {

        $stmt = $pdo->prepare("
            SELECT *
            FROM otp_codes
            WHERE student_id = ?
              AND otp_code = ?
              AND purpose = 'signup'
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

            // Verify OTP
            $stmt = $pdo->prepare("
                UPDATE otp_codes
                SET verified = 1,
                    used_at = NOW()
                WHERE id = ?
            ");

            $stmt->execute([$record['id']]);

            // Verify student
            $stmt = $pdo->prepare("
                UPDATE students
                SET is_verified = 1
                WHERE id = ?
            ");

            $stmt->execute([$studentId]);

            unset($_SESSION['signup_student']);

            $_SESSION['success'] = "Your account has been verified.";

            header("Location: login.php");
            exit();

        }

    }

}

include "../includes/header.php";
?>

<?php if (isset($_SESSION['success'])): ?>

<script>

document.addEventListener("DOMContentLoaded", function () {

    Swal.fire({

        icon: "success",

        title: "Registration Successful!",

        text: "<?= $_SESSION['success']; ?>",

        confirmButtonColor: "#001F54"

    });

});

</script>

<?php unset($_SESSION['success']); endif; ?>

<section class="py-5 dashboard-section">

<div class="container">

<div class="row justify-content-center">

<div class="col-lg-5">

<div class="card dashboard-card border-0">

<div class="card-body p-5">

<div class="text-center mb-4">

<img
src="<?= BASE_URL ?>assets/images/ssite-logo.png"
width="90"
class="mb-3"
alt="SSITE Logo">

<div class="alert alert-light border rounded-4 mb-4">

<i class="bi bi-info-circle-fill text-primary me-2"></i>

Your verification code will expire in
<strong>5 minutes</strong>.

</div>

<h2 class="fw-bold text-primary mb-2">

<i class="bi bi-envelope-check-fill me-2"></i>

Verify Your Account

</h2>

<p class="text-muted">

We've sent a 6-digit verification code to your PHINMA email address.

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

<i class="bi bi-shield-check me-2"></i>

Verify Account

</button>

</form>

<div class="text-center mt-4">

<p class="text-muted mb-2">

Didn't receive the verification code?

</p>

<a
href="resend-otp.php"
class="btn btn-outline-primary rounded-pill">

<i class="bi bi-arrow-clockwise me-2"></i>

Resend OTP

</a>

</div>

<div class="text-center mt-3">

<a
href="login.php"
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


<?php if (!empty($error)): ?>

<script>
document.addEventListener("DOMContentLoaded", function () {

    Swal.fire({
        icon: "error",
        title: "Verification Failed",
        text: "<?= htmlspecialchars($error, ENT_QUOTES) ?>"
    });

});
</script>

<?php endif; ?>
<?php

$pageTitle = "Student Login";

require_once "../config/config.php";
require_once "../config/database.php";
require_once "../config/mail.php";

require_once "../includes/functions.php";
require_once "../includes/session.php";

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $student_number = clean($_POST['student_number']);
    $password = $_POST['password'];

    if (empty($student_number)) {

        $error = "Please enter your Student Number.";

    }

    if (empty($error)) {

        $stmt = $pdo->prepare("
            SELECT *
            FROM students
            WHERE student_number = ?
            LIMIT 1
        ");

        $stmt->execute([$student_number]);

        $student = $stmt->fetch();

        if (!$student['is_active']) {

    $error = "Your account has been deactivated. Please contact the administrator.";

}

if (!$student) {

    $error = "Student number not found.";

} elseif (!$student['is_verified']) {

    $error = "Your account is not yet verified.";

} elseif (!$student['is_active']) {

    $error = "Your account has been deactivated. Please contact the administrator.";

} else {

    // Password verification
    // Generate OTP
    // Redirect to verify-login.php

}

if (!$student) {

    $error = "Student account not found.";

}

elseif (!password_verify($password, $student['password'])) {

    $error = "Incorrect password.";

}

elseif (!$student['is_verified']) {

    $error = "Please verify your account before logging in.";

}
        if (empty($error)) {

    // Generate OTP
    $otp = generateOTP();

    $expires = date(
        "Y-m-d H:i:s",
        time() + OTP_EXPIRY
    );

    // Save Login OTP
    $stmt = $pdo->prepare("
        INSERT INTO otp_codes
        (
            student_id,
            otp_code,
            expires_at,
            purpose
        )
        VALUES
        (
            ?, ?, ?, 'login'
        )
    ");

    $stmt->execute([
        $student['id'],
        $otp,
        $expires
    ]);

    try {

        $mail = getMailer();

        $mail->addAddress(
            $student['email'],
            $student['fullname']
        );

        $mail->isHTML(true);

        $mail->Subject = "SSITE Elections Portal - Login OTP";

        $mail->Body = "
            <h2>Login Verification</h2>

            <p>Hello <strong>{$student['fullname']}</strong>,</p>

            <p>Your login verification code is:</p>

            <h1 style='letter-spacing:6px;color:#001F54;'>{$otp}</h1>

            <p>This OTP expires in <strong>5 minutes</strong>.</p>
        ";

        $mail->send();

        $_SESSION['login_student'] = $student['id'];

        $_SESSION['success'] = "A login verification code has been sent to your email.";

        header("Location: verify-login.php");
        exit();

    } catch (Exception $e) {

        $error = "Unable to send the login verification email.";

    }

}

    }

}

include "../includes/header.php";
?>

<?php if (isset($_SESSION['success'])): ?>

<script>

document.addEventListener("DOMContentLoaded", function(){

    Swal.fire({

        icon: "success",

        title: "Login OTP",

        text: "<?= $_SESSION['success']; ?>",

        confirmButtonColor: "#001F54"

    });

});

</script>

<?php unset($_SESSION['success']); endif; ?>

<?php if (!empty($error)): ?>

<script>

document.addEventListener("DOMContentLoaded", function(){

    Swal.fire({

        icon: "error",

        title: "Login Failed",

        text: "<?= htmlspecialchars($error, ENT_QUOTES); ?>",

        confirmButtonColor: "#001F54"

    });

});

</script>

<?php endif; ?>

<section class="py-5" style="background:#f5f7fa;min-height:90vh;">

<div class="container">

<div class="row justify-content-center">

<div class="col-lg-5">

<div class="card dashboard-card border-0">

<div class="card-body p-5">

<div class="text-center mb-4">

<img src="<?= BASE_URL ?>assets/images/ssite-logo.png"
width="90"
class="mb-3">

<h2 class="fw-bold text-primary mb-2">

<i class="bi bi-person-circle me-2"></i>

Student Login

</h2>

<p class="text-muted">

Sign in securely using your student credentials to receive your one-time verification code.

</p>
</div>

<div class="alert alert-light border rounded-4 mb-4">

<i class="bi bi-shield-check me-2 text-primary"></i>

Your account is protected with OTP verification for every login.

</div>

<form method="POST">

<div class="mb-4">

<label class="form-label">

Student Number

</label>

<div class="input-group">

<span class="input-group-text">

<i class="bi bi-person-vcard-fill"></i>

</span>

<input
type="text"
name="student_number"
class="form-control form-control-lg"
placeholder="Enter your student number"
required>

</div>
</div>
<div class="mb-4">

<label class="form-label">

Password

</label>

<div class="input-group">

<span class="input-group-text">

<i class="bi bi-lock-fill"></i>

</span>

<input
type="password"
name="password"
id="password"
class="form-control form-control-lg"
placeholder="Enter your password"
required>

<button
class="btn btn-outline-secondary"
type="button"
onclick="togglePassword()">

<i class="bi bi-eye"></i>

</button>

</div>

</div>

<button
type="submit"
class="btn btn-primary btn-lg rounded-pill w-100">

<i class="bi bi-envelope-paper me-2"></i>

Send Login OTP

</button>

</form>

<p class="text-center text-muted small mt-3 mb-0">

<i class="bi bi-envelope-check me-1"></i>

A verification code will be sent to your registered email address.

</p>

<div class="text-center mt-4">

<p class="mb-2">

Don't have an account?

<a href="<?= BASE_URL ?>auth/signup.php">

Register here

</a>

</p>

<a
href="<?= BASE_URL ?>index.php"
class="text-decoration-none">

<i class="bi bi-arrow-left-circle me-1"></i>

Back to Homepage

</a>

</div>

</div>

</div>

</div>

</div>

</div>

</section>
<script>

function togglePassword(){

    const input = document.getElementById("password");
    const icon = document.querySelector("#password + button i");

    if(input.type === "password"){

        input.type = "text";
        icon.classList.remove("bi-eye");
        icon.classList.add("bi-eye-slash");

    }else{

        input.type = "password";
        icon.classList.remove("bi-eye-slash");
        icon.classList.add("bi-eye");

    }

}

</script>
<?php include "../includes/footer.php"; ?>
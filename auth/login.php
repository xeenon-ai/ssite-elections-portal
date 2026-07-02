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

<div class="card border-0 shadow-lg rounded-4">

<div class="card-body p-5">

<div class="text-center mb-4">

<img src="<?= BASE_URL ?>assets/images/ssite-logo.png"
width="90"
class="mb-3">

<h2 class="fw-bold text-primary">

Student Login

</h2>

<p class="text-muted">

Enter your Student Number to receive your login verification code.

</p>

</div>

<form method="POST">

<div class="mb-4">

<label class="form-label">

Student Number

</label>

<input
type="text"
name="student_number"
class="form-control form-control-lg"
placeholder="2024-00001"
required>

</div>
<div class="mb-4">

<label class="form-label">

Password

</label>

<div class="input-group">

<input
type="password"
name="password"
id="password"
class="form-control form-control-lg"
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
class="btn btn-primary btn-lg w-100">

<i class="bi bi-envelope-paper me-2"></i>

Send Login OTP

</button>

</form>

<div class="text-center mt-4">

<a href="<?= BASE_URL ?>auth/signup.php">

Don't have an account? Register

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
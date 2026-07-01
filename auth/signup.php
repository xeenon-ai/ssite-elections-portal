<?php
/*
|--------------------------------------------------------------------------
| SSITE Elections Portal
|--------------------------------------------------------------------------
| File: auth/signup.php
| Purpose: Student Registration
|--------------------------------------------------------------------------
*/

$pageTitle = "Student Registration";

require_once "../config/config.php";
require_once "../config/database.php";
require_once "../config/mail.php";

require_once "../includes/functions.php";
require_once "../includes/session.php";

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {


    $student_number = clean($_POST['student_number']);
    $fullname       = clean($_POST['fullname']);
    $email          = clean($_POST['email']);
    $course         = clean($_POST['course']);
    $year_level     = clean($_POST['year_level']);
    $section        = clean($_POST['section']);
    $password       = $_POST['password'];
    $confirm        = $_POST['confirm_password'];

// Empty fields
if (
    empty($student_number) ||
    empty($fullname) ||
    empty($email) ||
    empty($course) ||
    empty($year_level) ||
    empty($section) ||
    empty($password) ||
    empty($confirm)
) {

    $error = "Please complete all fields.";

}

// PHINMA Email
elseif (!isValidPhinmaEmail($email)) {

    $error = "Only @phinmaed.com email addresses are allowed.";

}

// Passwords
elseif ($password != $confirm) {

    $error = "Passwords do not match.";

}

// Password Length
elseif (strlen($password) < 8) {

    $error = "Password must be at least 8 characters.";

}

if (empty($error)) {

    // Student Number
    $stmt = $pdo->prepare("
        SELECT id
        FROM students
        WHERE student_number = ?
    ");

    $stmt->execute([$student_number]);

    if ($stmt->fetch()) {

        $error = "Student Number already exists.";

    }

}

if (empty($error)) {

    // Email
    $stmt = $pdo->prepare("
        SELECT id
        FROM students
        WHERE email = ?
    ");

    $stmt->execute([$email]);

    if ($stmt->fetch()) {

        $error = "Email already exists.";

    }

}
if (empty($error)) {

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $pdo->prepare("
        INSERT INTO students
        (
            student_number,
            fullname,
            email,
            password,
            course,
            year_level,
            section,
            is_verified,
            has_voted
        )
        VALUES
        (
            ?, ?, ?, ?, ?, ?, ?, 0, 0
        )
    ");

    if ($stmt->execute([
        $student_number,
        $fullname,
        $email,
        $hashedPassword,
        $course,
        $year_level,
        $section
    ])) {
// Get the new student's ID
$studentId = $pdo->lastInsertId();

// Generate a 6-digit OTP
$otp = generateOTP();

// OTP expires in 5 minutes
$expires = date(
    "Y-m-d H:i:s",
    time() + OTP_EXPIRY
);
// Save OTP to the database
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
        ?, ?, ?, 'signup'
    )
");

$stmt->execute([
    $studentId,
    $otp,
    $expires
]);

try {

    $mail = getMailer();

    $mail->addAddress($email, $fullname);

    $mail->isHTML(true);

    $mail->Subject = "SSITE Elections Portal - Account Verification";

    $mail->Body = "
        <h2>Welcome to SSITE Elections Portal</h2>

        <p>Hello <strong>{$fullname}</strong>,</p>

        <p>Your verification code is:</p>

        <h1 style='letter-spacing:6px;color:#001F54;'>{$otp}</h1>

        <p>This code will expire in <strong>5 minutes</strong>.</p>

        <p>If you did not request this account, simply ignore this email.</p>
    ";

    $mail->send();

    $_SESSION['signup_student'] = $studentId;

$_SESSION['success'] = "Registration successful! We've sent a verification code to your email.";
    
header("Location: " . BASE_URL . "auth/verify-signup.php");
exit();

} catch (Exception $e) {

    $error = "Unable to send verification email.";

}

    } else {

        $error = "Unable to create account.";

    }

}
}

include "../includes/header.php";
?>

<section class="py-5" style="background:#f5f7fa;min-height:90vh;">

<div class="container">

<div class="row justify-content-center">

<div class="col-lg-8">

<div class="card border-0 shadow-lg rounded-4">

<div class="card-body p-5">

<div class="text-center mb-4">

<img
src="<?= BASE_URL ?>assets/images/ssite-logo.png"
width="90"
alt="SSITE Logo"
class="mb-3">

<h2 class="fw-bold text-primary">

Student Registration

</h2>

<p class="text-muted">

Create your SSITE Elections Portal account.

<br>

Only
<strong>@phinmaed.com</strong>
email addresses are allowed.

</p>

</div>

<?php if(!empty($error)): ?>

<script>

document.addEventListener("DOMContentLoaded",function(){

Swal.fire({

icon:"error",

title:"Registration Failed",

text:"<?= htmlspecialchars($error,ENT_QUOTES) ?>",

confirmButtonColor:"#001F54"

});

});

</script>

<?php endif; ?>

<form method="POST">

<div class="row">

<div class="col-md-6 mb-3">

<label class="form-label">

Student Number

</label>

<input
type="text"
name="student_number"
class="form-control"
placeholder="2024-00001"
required>

</div>

<div class="col-md-6 mb-3">

<label class="form-label">

Full Name

</label>

<input
type="text"
name="fullname"
class="form-control"
placeholder="Juan Dela Cruz"
required>

</div>

</div>
<div class="mb-3">

<label class="form-label">

PHINMA Email

</label>

<input
type="email"
name="email"
id="email"
class="form-control"
placeholder="example@phinmaed.com"
required>

<div class="form-text">

Only PHINMA Education email addresses are accepted.

</div>

</div>

<div class="row">

<div class="col-md-4 mb-3">

<label class="form-label">

Course

</label>

<select
name="course"
class="form-select"
required>

<option value="">Select Course</option>

<option value="BSIT">BSIT</option>

<option value="BSCS">BSCS</option>

<option value="BSIS">BSIS</option>

</select>

</div>

<div class="col-md-4 mb-3">

<label class="form-label">

Year Level

</label>

<select
name="year_level"
class="form-select"
required>

<option value="">Select Year</option>

<option value="1st Year">1st Year</option>

<option value="2nd Year">2nd Year</option>

<option value="3rd Year">3rd Year</option>

<option value="4th Year">4th Year</option>

</select>

</div>

<div class="col-md-4 mb-3">

<label class="form-label">

Section

</label>

<input
type="text"
name="section"
class="form-control"
placeholder="A"
required>

</div>

</div>

<div class="row">

<div class="col-md-6 mb-3">

<label class="form-label">

Password

</label>

<div class="input-group">

<input
type="password"
name="password"
id="password"
class="form-control"
required>

<button
class="btn btn-outline-secondary"
type="button"
onclick="togglePassword('password',this)">

<i class="bi bi-eye"></i>

</button>

</div>

<div id="strengthText"
class="form-text text-muted">

Minimum of 8 characters.

</div>

</div>

<div class="col-md-6 mb-3">

<label class="form-label">

Confirm Password

</label>

<div class="input-group">

<input
type="password"
name="confirm_password"
id="confirm_password"
class="form-control"
required>

<button
class="btn btn-outline-secondary"
type="button"
onclick="togglePassword('confirm_password',this)">

<i class="bi bi-eye"></i>

</button>

</div>

</div>

</div>
<div class="form-check mb-4">

    <input
        class="form-check-input"
        type="checkbox"
        id="terms"
        required>

    <label
        class="form-check-label"
        for="terms">

        I agree to the
        <a href="#" class="text-decoration-none">
            Terms and Conditions
        </a>

    </label>

</div>

<button
    type="submit"
    class="btn btn-primary btn-lg w-100">

    <i class="bi bi-person-plus-fill me-2"></i>

    Create Account

</button>

</form>

<div class="text-center mt-4">

    Already have an account?

    <a href="<?= BASE_URL ?>auth/login.php">

        Login here

    </a>

</div>

</div>

</div>

</div>

</div>

</div>

</section>

<script>

function togglePassword(id, button)
{
    const input = document.getElementById(id);
    const icon = button.querySelector("i");

    if(input.type === "password")
    {
        input.type = "text";
        icon.classList.remove("bi-eye");
        icon.classList.add("bi-eye-slash");
    }
    else
    {
        input.type = "password";
        icon.classList.remove("bi-eye-slash");
        icon.classList.add("bi-eye");
    }
}

const password = document.getElementById("password");
const strength = document.getElementById("strengthText");

password.addEventListener("keyup", function(){

    let value = password.value;

    if(value.length < 8)
    {
        strength.innerHTML =
            "<span class='text-danger'>Weak Password</span>";
    }
    else if(value.length < 12)
    {
        strength.innerHTML =
            "<span class='text-warning'>Medium Password</span>";
    }
    else
    {
        strength.innerHTML =
            "<span class='text-success'>Strong Password</span>";
    }

});

</script>
<script>

document.querySelector("form").addEventListener("submit", function(e){

    const email = document
        .getElementById("email")
        .value
        .trim()
        .toLowerCase();

    if(!email.endsWith("@phinmaed.com"))
    {
        e.preventDefault();

        Swal.fire({
            icon:"error",
            title:"Invalid Email",
            text:"Only @phinmaed.com email addresses are allowed.",
            confirmButtonColor:"#001F54"
        });

        return;
    }

});

</script>

<?php include '../includes/footer.php'; ?>
<?php exit; ?>
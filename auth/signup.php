<?php

require_once 'config/database.php';
require_once 'includes/functions.php';
require_once 'includes/session.php';

$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $student_number = trim($_POST['student_number']);
    $fullname = trim($_POST['fullname']);
    $email = trim($_POST['email']);
    $course = trim($_POST['course']);
    $year_level = trim($_POST['year_level']);
    $section = trim($_POST['section']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Email validation
    if (!preg_match('/@phinmaed\.com$/', $email)) {
        $error = "Only @phinmaed.com email addresses are allowed.";
    }

    // Password validation
    elseif ($password !== $confirm_password) {
        $error = "Passwords do not match.";
    }

    elseif (strlen($password) < 8) {
        $error = "Password must be at least 8 characters.";
    }

    else {

        // Check Student Number
        $stmt = $pdo->prepare("SELECT id FROM students WHERE student_number = ?");
        $stmt->execute([$student_number]);

        if ($stmt->fetch()) {

            $error = "Student Number already exists.";

        } else {

            // Check Email
            $stmt = $pdo->prepare("SELECT id FROM students WHERE email = ?");
            $stmt->execute([$email]);

            if ($stmt->fetch()) {

                $error = "Email already registered.";

            } else {

                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

                $stmt = $pdo->prepare("
                    INSERT INTO students
                    (student_number, fullname, email, password, course, year_level, section)
                    VALUES (?, ?, ?, ?, ?, ?, ?)
                ");

                $stmt->execute([
                    $student_number,
                    $fullname,
                    $email,
                    $hashedPassword,
                    $course,
                    $year_level,
                    $section
                ]);

                $studentId = $pdo->lastInsertId();

                $otp = generateOTP();

                $expires = date("Y-m-d H:i:s", time() + OTP_EXPIRATION);

                $stmt = $pdo->prepare("
                    INSERT INTO otp_codes
                    (student_id, otp_code, expires_at)
                    VALUES (?, ?, ?)
                ");

                $stmt->execute([
                    $studentId,
                    $otp,
                    $expires
                ]);

                $_SESSION['signup_student'] = $studentId;
                $_SESSION['demo_signup_otp'] = $otp;

                header("Location: verify-signup.php");
                exit();

            }

        }

    }

}

$pageTitle = "Student Registration";
include 'includes/header.php';
?>

<section class="py-5" style="background:#f5f7fa; min-height:90vh;">

    <div class="container">

        <div class="row justify-content-center">

            <div class="col-lg-7">

                <div class="card shadow-lg border-0 rounded-4">

                    <div class="card-body p-5">

                        <div class="text-center mb-4">

                            <img src="assets/images/ssite-logo.png"
                                 width="80"
                                 class="mb-3"
                                 alt="SSITE Logo">

                            <h2 class="fw-bold text-primary">
                                Student Registration
                            </h2>

                            <p class="text-muted">
                                Create your SSITE Elections account.
                                Only <strong>@phinmaed.com</strong> email addresses are allowed.
                            </p>

                        </div>

                        </div>

<form action="" method="POST">

                        <form action="" method="POST">

                            <div class="row">

                                <div class="col-md-6 mb-3">

                                    <label class="form-label">Student Number</label>

                                    <input
                                        type="text"
                                        name="student_number"
                                        class="form-control"
                                        placeholder="2024-00001"
                                        required>

                                </div>

                                <div class="col-md-6 mb-3">

                                    <label class="form-label">Full Name</label>

                                    <input
                                        type="text"
                                        name="fullname"
                                        class="form-control"
                                        placeholder="Juan Dela Cruz"
                                        required>

                                </div>

                            </div>

                            <div class="mb-3">

                                <label class="form-label">PHINMA Email</label>

                                <input
                                    type="email"
                                    name="email"
                                    class="form-control"
                                    placeholder="example@phinmaed.com"
                                    required>

                            </div>

                            <div class="row">

                                <div class="col-md-4 mb-3">

                                    <label class="form-label">Course</label>

                                    <select
                                        name="course"
                                        class="form-select"
                                        required>

                                        <option value="">Select</option>
                                        <option>BSIT</option>
                                        <option>BSCS</option>
                                        <option>BSIS</option>

                                    </select>

                                </div>

                                <div class="col-md-4 mb-3">

                                    <label class="form-label">Year Level</label>

                                    <select
                                        name="year_level"
                                        class="form-select"
                                        required>

                                        <option value="">Select</option>
                                        <option>1st Year</option>
                                        <option>2nd Year</option>
                                        <option>3rd Year</option>
                                        <option>4th Year</option>

                                    </select>

                                </div>

                                <div class="col-md-4 mb-3">

                                    <label class="form-label">Section</label>

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

                                    <label class="form-label">Password</label>

                                    <input
                                        type="password"
                                        name="password"
                                        class="form-control"
                                        required>

                                </div>

                                <div class="col-md-6 mb-3">

                                    <label class="form-label">Confirm Password</label>

                                    <input
                                        type="password"
                                        name="confirm_password"
                                        class="form-control"
                                        required>

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

                                    I agree to the Terms and Conditions.

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

                            <a href="login.php">
                                Login here
                            </a>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</section>

<?php include 'includes/footer.php'; ?>
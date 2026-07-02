<?php

$pageTitle = "Administrator Verification";

require_once "../config/config.php";
require_once "../config/database.php";
require_once "../includes/session.php";
require_once "../includes/functions.php";

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

if (!isset($_SESSION['delete_student_id'])) {
    header("Location: students.php");
    exit();
}

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $otp = clean($_POST['otp']);

$stmt = $pdo->prepare("
    SELECT *
    FROM admin_otps
    WHERE admin_id = ?
      AND student_id = ?
      AND otp_code = ?
      AND verified = 0
    ORDER BY id DESC
    LIMIT 1
");

$stmt->execute([
    $_SESSION['admin_id'],
    $_SESSION['delete_student_id'],
    $otp
]);

    $record = $stmt->fetch();

    if (!$record) {

        $error = "Invalid OTP.";

    } elseif (strtotime($record['expires_at']) < time()) {

        $error = "OTP has expired.";

    } else {

        // Mark OTP as used
        $stmt = $pdo->prepare("
            UPDATE admin_otps
            SET
                verified = 1,
                used_at = NOW()
            WHERE id = ?
        ");

        $stmt->execute([$record['id']]);

        // Delete student
        $stmt = $pdo->prepare("
            DELETE FROM students
            WHERE id = ?
        ");

        // Get student information before deleting
$stmt = $pdo->prepare("
    SELECT fullname
    FROM students
    WHERE id = ?
");

$stmt->execute([
    $_SESSION['delete_student_id']
]);

$student = $stmt->fetch();

        $stmt->execute([
            $_SESSION['delete_student_id']
        ]);

        unset($_SESSION['delete_student_id']);

        $_SESSION['student_success'] = [
            "title" => "Student Deleted!",
            "text" => "The student account has been permanently deleted.",
            "icon" => "success"
        ];

        logActivity(
    $pdo,
    "admin",
    $_SESSION['admin_id'],
    "Deleted student: " . $student['fullname']
);


        header("Location: students.php");
        exit();

    }

}

include "../includes/header.php";
?>

<?php if(!empty($error)): ?>

<script>

document.addEventListener("DOMContentLoaded",function(){

Swal.fire({

icon:"error",

title:"Verification Failed",

text:"<?= htmlspecialchars($error,ENT_QUOTES) ?>"

});

});

</script>

<?php endif; ?>

<section class="py-5" style="background:#f5f7fa;min-height:90vh;">

<div class="container">

<div class="row justify-content-center">

<div class="col-lg-5">

<div class="card shadow-lg border-0 rounded-4">

<div class="card-body p-5">

<div class="text-center mb-4">

<img
src="<?= BASE_URL ?>assets/images/ssite-logo.png"
width="90"
class="mb-3">

<h2 class="fw-bold text-primary">

Administrator Verification

</h2>

<p class="text-muted">

Enter the OTP sent to

<strong><?= ADMIN_EMAIL ?></strong>

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
class="btn btn-danger btn-lg w-100">

<i class="bi bi-shield-lock-fill me-2"></i>

Verify & Delete Student

</button>

</form>

<div class="text-center mt-4">

<a href="students.php">

Cancel

</a>

</div>

</div>

</div>

</div>

</div>

</div>

</section>

<?php include "../includes/footer.php"; ?>
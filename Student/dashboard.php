<?php

$pageTitle = "Student Dashboard";

require_once "../config/config.php";
require_once "../config/database.php";
require_once "../includes/session.php";

if (!isset($_SESSION['student_id'])) {

    header("Location: ../auth/login.php");
    exit();

}

$stmt = $pdo->prepare("
    SELECT *
    FROM students
    WHERE id = ?
");

$stmt->execute([
    $_SESSION['student_id']
]);

$student = $stmt->fetch();

include "../includes/header.php";

?>

<section class="py-5">

<div class="container">

<div class="row justify-content-center">

<div class="col-lg-8">

<div class="card shadow-lg border-0 rounded-4">

<div class="card-body p-5">

<h2 class="fw-bold text-primary">

Welcome,

<?= htmlspecialchars($student['fullname']); ?>

👋

</h2>

<hr>

<p>

<strong>Student Number:</strong>

<?= htmlspecialchars($student['student_number']); ?>

</p>

<p>

<strong>Course:</strong>

<?= htmlspecialchars($student['course']); ?>

</p>

<p>

<strong>Year Level:</strong>

<?= htmlspecialchars($student['year_level']); ?>

</p>

<p>

<strong>Section:</strong>

<?= htmlspecialchars($student['section']); ?>

</p>

<?php if($student['has_voted']): ?>

<div class="alert alert-success">

✅ You have already voted.

</div>

<?php else: ?>

<div class="alert alert-warning">

You have not voted yet.

</div>

<a
href="vote.php"
class="btn btn-success btn-lg">

Vote Now

</a>

<?php endif; ?>

</div>

</div>

</div>

</div>

</div>

</section>

<?php include "../includes/footer.php"; ?>
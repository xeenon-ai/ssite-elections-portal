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

include "student-header.php";

?>

<section class="py-5 dashboard-section">
<div class="container">

<div class="row justify-content-center">

<div class="col-lg-8">
<div class="card dashboard-card border-0">

<div class="card-body p-5">

<div class="text-center mb-5">

<i class="bi bi-person-circle display-1 text-primary"></i>

<h2 class="fw-bold text-primary mt-3">

Welcome,

<?= htmlspecialchars($student['fullname']); ?>

👋

</h2>

<div class="text-center mb-4">

<span class="badge bg-success fs-6 px-4 py-2 rounded-pill">

Verified Student

</span>

</div>

<p class="text-muted">

Student Dashboard

</p>

</div>

<div class="row g-4 mb-4">

<div class="col-md-6">

<div class="card border-0 bg-light">

<div class="card-body">

<i class="bi bi-person-vcard-fill text-primary"></i>

<strong> Student Number</strong>

<p class="mb-0 mt-2">

<?= htmlspecialchars($student['student_number']); ?>

</p>

</div>

</div>

</div>

<div class="col-md-6">

<div class="card border-0 bg-light">

<div class="card-body">

<i class="bi bi-book-fill text-success"></i>

<strong> Course</strong>

<p class="mb-0 mt-2">

<?= htmlspecialchars($student['course']); ?>

</p>

</div>

</div>

</div>

<div class="col-md-6">

<div class="card border-0 bg-light">

<div class="card-body">

<i class="bi bi-mortarboard-fill text-warning"></i>

<strong> Year Level</strong>

<p class="mb-0 mt-2">

<?= htmlspecialchars($student['year_level']); ?>

</p>

</div>

</div>

</div>

<div class="col-md-6">

<div class="card border-0 bg-light">

<div class="card-body">

<i class="bi bi-people-fill text-danger"></i>

<strong> Section</strong>

<p class="mb-0 mt-2">

<?= htmlspecialchars($student['section']); ?>

</p>

</div>

</div>

</div>

</div>

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

<div class="alert alert-success rounded-4">

<i class="bi bi-check-circle-fill me-2"></i>

You have successfully cast your vote.

Thank you for participating in the SSITE Election.

</div>

<?php else: ?>

<div class="alert alert-warning rounded-4">

<i class="bi bi-exclamation-triangle-fill me-2"></i>

You haven't voted yet.

Please cast your vote before the election closes.

</div>

<hr class="my-5">

<h4 class="fw-bold mb-4">

⚡ Quick Actions

</h4>

<div class="row g-3">

<div class="col-md-6">

<a
href="vote.php"
class="btn btn-primary w-100 rounded-pill py-3">

<i class="bi bi-check2-square me-2"></i>

Vote Now

</a>

</div>

<div class="col-md-6">

<a
href="../auth/logout.php"
class="btn btn-outline-danger w-100 rounded-pill py-3">

<i class="bi bi-box-arrow-right me-2"></i>

Logout

</a>

</div>

</div>

<?php endif; ?>

</div>

</div>

</div>

</div>

</div>

</section>

<?php include "../includes/footer.php"; ?>
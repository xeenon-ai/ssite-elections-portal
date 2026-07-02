<?php

$pageTitle = "Admin Dashboard";

require_once "../config/config.php";
require_once "../config/database.php";
require_once "../includes/session.php";

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

// Dashboard Statistics
$totalStudents = $pdo->query("SELECT COUNT(*) FROM students")->fetchColumn();
$totalCandidates = $pdo->query("SELECT COUNT(*) FROM candidates")->fetchColumn();
$totalVotes = $pdo->query("SELECT COUNT(*) FROM votes")->fetchColumn();

include "../includes/header.php";

?>

<section class="py-5" style="background:#f5f7fa;min-height:90vh;">

<div class="container">

<div class="mb-5">

<h2 class="fw-bold text-primary">

<i class="bi bi-speedometer2 me-2"></i>

Admin Dashboard

</h2>

<p class="text-muted">

Welcome,

<strong><?= htmlspecialchars($_SESSION['admin_name']); ?></strong>

</p>

</div>

<div class="row g-4">

<div class="col-md-4">

<div class="card shadow border-0">

<div class="card-body text-center">

<i class="bi bi-people-fill display-3 text-primary"></i>

<h1><?= $totalStudents ?></h1>

<p>Total Students</p>

</div>

</div>

</div>

<div class="col-md-4">

<div class="card shadow border-0">

<div class="card-body text-center">

<i class="bi bi-person-badge-fill display-3 text-success"></i>

<h1><?= $totalCandidates ?></h1>

<p>Total Candidates</p>

</div>

</div>

</div>

<div class="col-md-4">

<div class="card shadow border-0">

<div class="card-body text-center">

<i class="bi bi-check2-square display-3 text-danger"></i>

<h1><?= $totalVotes ?></h1>

<p>Total Votes Cast</p>

</div>

</div>

</div>

</div>

<div class="row mt-5 g-4">

<div class="col-md-6">

<a href="candidates.php" class="btn btn-primary w-100 btn-lg">

<i class="bi bi-person-plus-fill me-2"></i>

Manage Candidates

</a>

</div>

<div class="col-md-6">

<a href="students.php" class="btn btn-success w-100 btn-lg">

<i class="bi bi-person-lines-fill me-2"></i>

View Students

</a>

</div>

<div class="col-md-6">

<a href="results.php" class="btn btn-warning w-100 btn-lg">

<i class="bi bi-bar-chart-fill me-2"></i>

Election Results

</a>

</div>


<div class="col-md-6">

<a href="settings.php" class="btn btn-dark w-100 btn-lg">

<i class="bi bi-gear-fill me-2"></i>

Election Settings

</a>

</div>


<div class="col-md-6">

<a href="activity-log.php" class="btn btn-dark w-100 btn-lg">

<i class="bi bi-clock-history me-2"></i>

Activity Log

</a>

</div>


<div class="col-md-6">

<a href="logout.php" class="btn btn-danger w-100 btn-lg">

<i class="bi bi-box-arrow-right me-2"></i>

Logout

</a>

</div>

</div>

</div>

</section>

<?php include "../includes/footer.php"; ?>
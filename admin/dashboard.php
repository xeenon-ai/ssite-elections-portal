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

include "admin-header.php";

?>

<section class="py-5" style="background:#e9eef5;min-height:90vh;">

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

<div class="row g-4 mt-1">

<div class="col-md-4">

<div class="card dashboard-card shadow-lg">

<div class="card-body text-center">

<i class="bi bi-people-fill display-2 text-primary"></i>

<h1 class="fw-bold text-primary">

<?= $totalStudents ?>

</h1>
<p>Total Students</p>

</div>

</div>

</div>

<div class="col-md-4">

<div class="card dashboard-card shadow-lg">
<div class="card-body text-center">

<i class="bi bi-person-badge-fill display-2 text-success"></i>

<h1 class="fw-bold text-success">

<?= $totalCandidates ?>

</h1>

<p>Total Candidates</p>

</div>

</div>

</div>

<div class="col-md-4">

<div class="card dashboard-card shadow-lg">

<div class="card-body text-center">

<i class="bi bi-check2-square display-2 text-danger"></i>

<h1 class="fw-bold text-danger">

<?= $totalVotes ?>

</h1>

<p>Total Votes Cast</p>

</div>

</div>

</div>

</div>

<h4 class="fw-bold text-dark mt-5 mb-3">

<i class="bi bi-lightning-charge-fill text-warning me-2"></i>

Quick Actions

</h4>

<div class="card quick-actions-panel shadow-lg border-0 rounded-4">

<div class="card-body p-4">

<div class="row mt-5 g-4">

<div class="col-md-4">

<a href="candidates.php"
class="text-decoration-none">

<div class="card dashboard-card h-100">

<div class="card-body text-center">

<i class="bi bi-person-plus-fill display-5 text-primary"></i>

<h5 class="fw-bold mt-3">

Manage Candidates

</h5>

<p class="text-muted mb-0">

Add, edit and remove candidates.

</p>

</div>

</div>

</a>

</div>

<div class="col-md-4">

<a href="students.php"
class="text-decoration-none">

<div class="card dashboard-card h-100">

<div class="card-body text-center">

<i class="bi bi-people-fill display-5 text-success"></i>

<h5 class="fw-bold mt-3">

View Students

</h5>

<p class="text-muted mb-0">

Manage student accounts.

</p>

</div>

</div>

</a>

</div>

<div class="col-md-4">

<a href="results.php"
class="text-decoration-none">

<div class="card dashboard-card h-100">

<div class="card-body text-center">

<i class="bi bi-bar-chart-fill display-5 text-warning"></i>

<h5 class="fw-bold mt-3">

Election Results

</h5>

<p class="text-muted mb-0">

View live election statistics.

</p>

</div>

</div>

</a>

</div>


<div class="col-md-4">

<a href="settings.php"
class="text-decoration-none">

<div class="card dashboard-card h-100">

<div class="card-body text-center">

<i class="bi bi-gear-fill display-5 text-secondary"></i>

<h5 class="fw-bold mt-3">

Election Settings

</h5>

<p class="text-muted mb-0">

Configure election preferences.

</p>

</div>

</div>

</a>

</div>


<div class="col-md-4">

<a href="activity-log.php"
class="text-decoration-none">

<div class="card dashboard-card h-100">

<div class="card-body text-center">

<i class="bi bi-clock-history display-5 text-dark"></i>

<h5 class="fw-bold mt-3">

Activity Log

</h5>

<p class="text-muted mb-0">

View administrator activities.

</p>

</div>

</div>

</a>

</div>


<div class="col-md-4">

<a href="logout.php"
class="text-decoration-none">

<div class="card dashboard-card h-100">

<div class="card-body text-center">

<i class="bi bi-box-arrow-right display-5 text-danger"></i>

<h5 class="fw-bold mt-3">

Logout

</h5>

<p class="text-muted mb-0">

Sign out of the administrator account.

</p>

</div>

</div>

</div>

</div>

</a>

</div>
</div>

</div>

</section>

<?php include "../includes/footer.php"; ?>
<?php
require_once __DIR__ . '/../config/config.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title><?= $pageTitle ?? 'SSITE Elections Portal' ?></title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">

<link rel="stylesheet"
href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">

<link rel="stylesheet"
href="<?= BASE_URL ?>assets/css/style.css">

</head>

<body>

<!-- ADMIN NAVBAR -->

<nav class="navbar navbar-expand-lg navbar-dark shadow sticky-top">

<div class="container">

<a class="navbar-brand fw-bold" href="dashboard.php">

<img
src="<?= BASE_URL ?>assets/images/ssite-logo.png"
width="55"
class="me-2">

SSITE Admin

</a>

<button
class="navbar-toggler"
type="button"
data-bs-toggle="collapse"
data-bs-target="#adminMenu">

<span class="navbar-toggler-icon"></span>

</button>

<div class="collapse navbar-collapse"
id="adminMenu">

<ul class="navbar-nav ms-auto align-items-center">

<li class="nav-item">

<a class="nav-link <?= $current=='dashboard.php'?'active':'' ?>"

href="dashboard.php">

<i class="bi bi-speedometer2 me-1"></i>

Dashboard

</a>

</li>

<li class="nav-item">

<a class="nav-link" href="candidates.php">

<i class="bi bi-person-badge me-1"></i>

Candidates

</a>

</li>

<li class="nav-item">

<a class="nav-link" href="students.php">

<i class="bi bi-people me-1"></i>

Students

</a>

</li>

<li class="nav-item">

<a class="nav-link" href="results.php">

<i class="bi bi-bar-chart me-1"></i>

Results

</a>

</li>

<li class="nav-item">

<a class="nav-link" href="settings.php">

<i class="bi bi-gear me-1"></i>

Settings

</a>

</li>

<li class="nav-item">

<a class="nav-link" href="activity-log.php">

<i class="bi bi-clock-history me-1"></i>

Activity

</a>

</li>

<li class="nav-item me-3">

<span class="navbar-text text-white">

<i class="bi bi-person-circle me-1"></i>

<?= htmlspecialchars($_SESSION['admin_name']) ?>

</span>

</li>

<li class="nav-item ms-2">

<a class="btn btn-danger"

href="logout.php">

<i class="bi bi-box-arrow-right me-1"></i>

Logout

</a>

</li>

</ul>

</div>

</div>

</nav>
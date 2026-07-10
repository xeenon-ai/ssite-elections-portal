<?php
require_once __DIR__ . "/../config/config.php";
require_once __DIR__ . "/../includes/session.php";

$current = basename($_SERVER['PHP_SELF']);
?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport"
      content="width=device-width, initial-scale=1.0">

<title><?= $pageTitle ?? "Student Portal"; ?></title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css"
      rel="stylesheet">

<link rel="stylesheet"
href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">

<link rel="stylesheet"
href="<?= BASE_URL ?>assets/css/style.css">

<link
rel="stylesheet"
href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">

</head>

<body>

<nav class="navbar navbar-expand-lg navbar-dark shadow sticky-top">

<div class="container">

<a class="navbar-brand d-flex align-items-center"
href="<?= BASE_URL ?>student/dashboard.php">

<img
src="<?= BASE_URL ?>assets/images/ssite-logo.png"
width="55"
class="me-3">

<div>

<div class="fw-bold fs-4">

SSITE Elections Portal

</div>

<small class="text-light opacity-75">

Student Portal

</small>

</div>

</a>

<button
class="navbar-toggler"
type="button"
data-bs-toggle="collapse"
data-bs-target="#studentMenu">

<span class="navbar-toggler-icon"></span>

</button>

<div
class="collapse navbar-collapse"
id="studentMenu">

<ul class="navbar-nav ms-auto align-items-lg-center">

<li class="nav-item">

<a class="nav-link <?= $current=="dashboard.php" ? "active" : "" ?>"
href="dashboard.php">

<i class="bi bi-house-door me-1"></i>

Dashboard

</a>

</li>

<li class="nav-item">

<a class="nav-link <?= $current=="vote.php" ? "active" : "" ?>"
href="vote.php">

<i class="bi bi-check2-square me-1"></i>

Vote

</a>

</li>

<li class="nav-item ms-lg-4">

<span class="navbar-text text-white">

<i class="bi bi-person-circle me-1"></i>

<?= htmlspecialchars($student['fullname']) ?>

</span>

</li>

<li class="nav-item ms-lg-3">

<a
href="logout.php"
class="btn btn-outline-light rounded-pill">

<i class="bi bi-box-arrow-right me-1"></i>

Logout

</a>

</li>

</ul>

</div>

</div>

</nav>

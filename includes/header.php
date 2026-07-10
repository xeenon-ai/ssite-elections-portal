<?php
require_once __DIR__ . '/../config/config.php';
$current = basename($_SERVER['PHP_SELF']);
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

<title><?= $pageTitle ?? 'SSITE Elections Portal'; ?></title>

    <!-- Bootstrap -->

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css"
          rel="stylesheet">

    <!-- Bootstrap Icons -->

    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">

    <!-- CSS -->

<link rel="stylesheet" href="<?= BASE_URL ?>assets/css/style.css">

<link
rel="stylesheet"
href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">

</head>

<body>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-dark shadow sticky-top">

<div class="container">

<a class="navbar-brand d-flex align-items-center"
href="<?= BASE_URL ?>index.php">

<img
src="<?= BASE_URL ?>assets/images/ssite-logo.png"
alt="SSITE Logo"
width="55"
class="me-3">

<div>

<div class="fw-bold fs-5">

SSITE Elections Portal

</div>

<small class="text-light opacity-50">

College of Information Technology Education

</small>

</div>

</a>

<button
class="navbar-toggler"
type="button"
data-bs-toggle="collapse"
data-bs-target="#menu">

<span class="navbar-toggler-icon"></span>

</button>

<div class="collapse navbar-collapse justify-content-end"
id="menu">

<ul class="navbar-nav align-items-lg-center">

<li class="nav-item">

<a class="nav-link <?= $current=="index.php" ? "active" : "" ?>"
href="<?= BASE_URL ?>index.php">

Home

</a>

</li>

<li class="nav-item">

<a class="nav-link"
href="<?= BASE_URL ?>index.php#about">

About

</a>

</li>

<?php

$hideButtons = in_array($current,[
"login.php",
"signup.php",
"otp.php"
]);

if(!$hideButtons):

?>

<li class="nav-item ms-lg-3">

<a
href="<?= BASE_URL ?>auth/login.php"
class="btn btn-light rounded-pill px-4">

Login

</a>

</li>

<li class="nav-item ms-2">

<a
href="<?= BASE_URL ?>auth/signup.php"
class="btn btn-warning rounded-pill px-4">

Register

</a>

</li>

<?php endif; ?>

</ul>

</div>

</div>

</nav>
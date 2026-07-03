<?php
$pageTitle = "Home";
include 'includes/header.php';
?>

<!-- HERO -->

<section class="hero">

<div class="container">

<div class="row align-items-center">

<div class="col-lg-6">

<h1 class="display-3 fw-bold">

Vote with

<span class="text-warning">

Confidence

</span>

</h1>

<h3 class="fw-semibold mt-3">

SSITE Elections Portal

</h3>

<p class="lead mt-4">

Welcome to the official online election platform of the

<strong>Student Society of Information Technology Education (SSITE)</strong>.

Cast your vote securely, transparently, and only once.

</p>

<div class="mt-4">

<a href="<?= BASE_URL ?>auth/login.php"
class="btn btn-warning btn-lg rounded-pill px-5 me-2">

<i class="bi bi-box-arrow-in-right me-2"></i>

Vote Now

</a>

<a href="#about"
class="btn btn-outline-light btn-lg rounded-pill px-5">

Learn More

</a>

</div>

</div>

<div class="col-lg-6 text-center">

<img src="assets/images/vote.svg"
class="img-fluid"
alt="Voting">

</div>

</div>

</div>

</section>

<div class="row text-center mt-5">

<div class="col-4">

<h2 class="fw-bold">

100%

</h2>

<p>

Secure

</p>

</div>

<div class="col-4">

<h2 class="fw-bold">

OTP

</h2>

<p>

Verified

</p>

</div>

<div class="col-4">

<h2 class="fw-bold">

1 Vote

</h2>

<p>

Per Student

</p>

</div>

</div>

<!-- FEATURES -->

<section id="about"
class="py-5">

<div class="container">

<h2 class="text-center fw-bold mb-2">

Why Choose Our Election Portal?

</h2>

<p class="text-center text-muted mb-5">

Built to provide secure, transparent, and fair student elections.

</p>

<div class="row g-4">

<div class="col-md-4">

<div class="card dashboard-card h-100 text-center">

<div class="card-body text-center">

<i class="bi bi-shield-check display-3 text-primary"></i>

<h4 class="mt-3">

Secure OTP

</h4>

<p>

Every login requires OTP verification.

</p>

</div>

</div>

</div>

<div class="col-md-4">

<div class="card h-100 shadow">

<div class="card-body text-center">

<i class="bi bi-person-check display-4 text-primary"></i>

<h4 class="mt-3">

One Student

</h4>

<p>

Each account can only vote once.

</p>

</div>

</div>

</div>

<div class="col-md-4">

<div class="card h-100 shadow">

<div class="card-body text-center">

<i class="bi bi-bar-chart display-4 text-primary"></i>

<h4 class="mt-3">

Transparent

</h4>

<p>

Results are published after voting closes.

</p>

</div>

</div>

</div>

</div>

</div>

</section>
<section class="py-5 text-center bg-light">

<div class="container">

<h2 class="fw-bold">

Ready to Cast Your Vote?

</h2>

<p class="text-muted">

Participate in shaping the future of SSITE by casting your secure vote.

</p>

<a
href="<?= BASE_URL ?>auth/login.php"
class="btn btn-primary btn-lg rounded-pill px-5">

<i class="bi bi-check2-circle me-2"></i>

Get Started

</a>

</div>

</section>
<?php include 'includes/footer.php'; ?>
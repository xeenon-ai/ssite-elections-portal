<footer class="footer text-white mt-5">

<div class="container py-5">

<div class="row">

<!-- Branding -->

<div class="col-lg-5 mb-4">

<div class="d-flex align-items-center mb-3">

<img
src="<?= BASE_URL ?>assets/images/ssite-logo.png"
width="60"
class="me-3">

<div>

<h5 class="fw-bold mb-0">

SSITE Elections Portal

</h5>

<small class="text-light opacity-75">

Student Society of Information Technology Education

</small>

</div>

</div>

<p class="mb-2">

College of Information Technology Education (CITE)

</p>

<p class="small text-light opacity-75 mb-0">

A secure and transparent online election platform for the
Student Society of Information Technology Education.

</p>

</div>

<!-- Quick Links -->

<div class="col-lg-3 mb-4">

<h6 class="fw-bold mb-3">

Quick Links

</h6>

<ul class="list-unstyled">

<li>

<a href="<?= BASE_URL ?>index.php"
class="text-decoration-none text-light">

<i class="bi bi-house-door-fill me-2"></i>

Home

</a>

</li>

<li class="mt-2">

<a href="<?= BASE_URL ?>index.php#about"
class="text-decoration-none text-light">

<i class="bi bi-info-circle-fill me-2"></i>

About

</a>

</li>

<li class="mt-2">

<a href="<?= BASE_URL ?>auth/login.php"
class="text-decoration-none text-light">

<i class="bi bi-box-arrow-in-right me-2"></i>

Student Login

</a>

</li>

</ul>

</div>

<!-- Contact -->

<div class="col-lg-4">

<h6 class="fw-bold mb-3">

Portal Information

</h6>

<p class="mb-2">

<i class="bi bi-shield-check me-2"></i>

Secure Voting System

</p>

<p class="mb-2">

<i class="bi bi-people-fill me-2"></i>

For SSITE Students

</p>

<p class="mb-0">

<i class="bi bi-calendar-event me-2"></i>

<?= date("Y") ?> Election System

</p>

</div>

</div>

<hr class="border-light opacity-25 my-4">

<div class="d-flex justify-content-between align-items-center flex-wrap">

<small>

© <?= date("Y") ?>

SSITE Elections Portal.

All Rights Reserved.

</small>

<small class="opacity-75">

Secure • Transparent • Fair Elections

</small>

</div>

</div>

</footer>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Custom JS -->
<script src="<?= BASE_URL ?>assets/js/script.js"></script>

</body>
</html>
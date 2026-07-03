<?php

$pageTitle = "Vote Submitted";

require_once "../config/config.php";
require_once "../includes/session.php";

include "student-header.php";

?>

<section class="d-flex align-items-center" style="min-height:90vh;background:#f5f7fa;">

<div class="container">

<div class="row justify-content-center">

<div class="col-lg-6">

<div class="card border-0 shadow-lg rounded-4">

<div class="card-body text-center p-5">

<div class="mb-4">

<i class="bi bi-check-circle-fill text-success" style="font-size:90px;"></i>

</div>

<h2 class="fw-bold text-success">

Vote Submitted Successfully!

</h2>

<p class="lead mt-3">

Thank you for participating in the

<strong>SSITE Elections 2026.</strong>

</p>

<p class="text-muted">

Your three votes have been securely recorded.

You can no longer vote again using this account.

</p>

<div class="d-grid gap-3 mt-5">

<a
href="dashboard.php"
class="btn btn-primary btn-lg">

<i class="bi bi-house-fill me-2"></i>

Return to Dashboard

</a>

<a
href="../auth/logout.php"
class="btn btn-outline-danger btn-lg">

<i class="bi bi-box-arrow-right me-2"></i>

Logout

</a>

</div>

</div>

</div>

</div>

</div>

</div>

</section>

<?php include "../includes/footer.php"; ?>
<?php

$pageTitle = "Terms and Conditions";

include "includes/header.php";

?>

<section class="py-5 dashboard-section">

<div class="container">

<div class="row justify-content-center">

<div class="col-lg-9">

<div class="card dashboard-card border-0">

<div class="card-body p-5">

<h2 class="fw-bold text-primary mb-4">

<i class="bi bi-file-earmark-text-fill me-2"></i>

Terms and Conditions

</h2>

<p class="text-muted">

Welcome to the <strong>SSITE Elections Portal</strong>. By creating an account and using this system, you agree to the following terms and conditions.

</p>

<hr>

<h5 class="mt-4">

1. Eligibility

</h5>

<p>

Only officially enrolled students of the College of Information Technology Education (CITE) using a valid <strong>@phinmaed.com</strong> email address may register and participate in the election.

</p>

<h5 class="mt-4">

2. One Student, One Vote

</h5>

<p>

Each registered student is permitted to cast only one vote during the election period. Once a vote has been submitted, it cannot be changed or withdrawn.

</p>

<h5 class="mt-4">

3. Account Security

</h5>

<p>

Students are responsible for keeping their login credentials confidential. Sharing passwords or verification codes with others is strictly prohibited.

</p>

<h5 class="mt-4">

4. Email Verification

</h5>

<p>

Registration and login require One-Time Password (OTP) verification sent to the student's registered PHINMA email address. Verification codes are valid only for a limited time.

</p>

<h5 class="mt-4">

5. Fair Voting

</h5>

<p>

Students must vote honestly and fairly. Any attempt to manipulate, duplicate, or interfere with the election process may result in account suspension and disciplinary action.

</p>

<h5 class="mt-4">

6. Privacy

</h5>

<p>

Personal information collected during registration is used solely for election purposes. Student information will not be shared with unauthorized individuals.

</p>

<h5 class="mt-4">

7. Election Results

</h5>

<p>

Election results will only be available after administrators officially enable public viewing. The displayed results are considered the official outcome of the election.

</p>

<h5 class="mt-4">

8. Administrator Rights

</h5>

<p>

System administrators reserve the right to deactivate accounts that violate these terms or compromise the integrity and security of the election system.

</p>

<hr>

<p class="text-muted mb-0">

By registering for the SSITE Elections Portal, you acknowledge that you have read, understood, and agree to abide by these Terms and Conditions.

</p>

<div class="text-center mt-5">

<a href="<?= BASE_URL ?>auth/signup.php"
class="btn btn-primary rounded-pill px-5">

<i class="bi bi-arrow-left me-2"></i>

Back to Registration

</a>

</div>

</div>

</div>

</div>

</div>

</div>

</section>

<?php include "includes/footer.php"; ?>
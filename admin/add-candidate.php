<?php

$pageTitle = "Add Candidate";

require_once "../config/config.php";
require_once "../config/database.php";
require_once "../includes/session.php";

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $fullname = trim($_POST['fullname']);
    $year_level = trim($_POST['year_level']);
    $section = trim($_POST['section']);
    $bio = trim($_POST['bio']);
    $is_active = isset($_POST['is_active']) ? 1 : 0;

    $photo = "";

    if (!empty($_FILES['photo']['name'])) {

        $extension = strtolower(pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION));

        $allowed = ["jpg","jpeg","png","webp"];

        if (!in_array($extension, $allowed)) {

            $error = "Only JPG, JPEG, PNG and WEBP are allowed.";

        } else {

$photo = uniqid() . "." . $extension;

if (!move_uploaded_file(
    $_FILES['photo']['tmp_name'],
    "../uploads/" . $photo
)) {
    $error = "Failed to upload the candidate photo.";
}

        }

    }

    if (empty($error)) {

        $stmt = $pdo->prepare("
            INSERT INTO candidates
            (
                fullname,
                year_level,
                section,
                photo,
                bio,
                is_active
            )
            VALUES
            (
                ?, ?, ?, ?, ?, ?
            )
        ");

        $stmt->execute([
            $fullname,
            $year_level,
            $section,
            $photo,
            $bio,
            $is_active
        ]);

        $_SESSION['candidate_success'] = [
    "title" => "Candidate Added!",
    "text" => "The new candidate has been added successfully.",
    "icon" => "success"
];
        header("Location: candidates.php");
        exit();

    }

}

include "../includes/header.php";


?>



<section class="py-5" style="background:#f5f7fa;min-height:90vh;">

<div class="container">

<div class="row justify-content-center">

<div class="col-lg-8">

<div class="card shadow-lg border-0 rounded-4">

<div class="card-body p-5">

<div class="text-center mb-4">

<img
src="<?= BASE_URL ?>assets/images/ssite-logo.png"
width="90"
class="mb-3">

<h2 class="fw-bold text-primary">

Add New Candidate

</h2>

<p class="text-muted">

Complete the information below.

</p>

</div>

<?php if(!empty($error)): ?>

<div class="alert alert-danger">

<?= htmlspecialchars($error); ?>

</div>

<?php endif; ?>

<form method="POST" enctype="multipart/form-data">

<div class="mb-3">

<label class="form-label">

Full Name

</label>

<input
type="text"
name="fullname"
class="form-control"
required>

</div>

<div class="row">

<div class="col-md-6 mb-3">

<label class="form-label">

Year Level

</label>

<select
name="year_level"
class="form-select"
required>

<option value="">Select Year</option>

<option>1st Year</option>

<option>2nd Year</option>

<option>3rd Year</option>

<option>4th Year</option>

</select>

</div>

<div class="col-md-6 mb-3">

<label class="form-label">

Section

</label>

<input
type="text"
name="section"
class="form-control"
placeholder="BSIT 3A"
required>

</div>

</div>

<div class="mb-3">

<label class="form-label">

Biography

</label>

<textarea
name="bio"
rows="4"
class="form-control"
placeholder="Candidate platform..."></textarea>

</div>

<div class="mb-3">

<label class="form-label">

Candidate Photo

</label>

<input
type="file"
name="photo"
class="form-control"
accept=".jpg,.jpeg,.png,.webp">

</div>

<div class="form-check mb-4">

<input
type="checkbox"
name="is_active"
class="form-check-input"
checked>

<label class="form-check-label">

Active Candidate

</label>

</div>

<div class="d-grid gap-2">

<button
class="btn btn-primary btn-lg">

<i class="bi bi-person-plus-fill me-2"></i>

Save Candidate

</button>

<a
href="candidates.php"
class="btn btn-secondary">

Cancel

</a>

</div>

</form>

</div>

</div>

</div>

</div>

</div>

</section>

<?php include "../includes/footer.php"; ?>
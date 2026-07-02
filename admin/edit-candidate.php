<?php

$pageTitle = "Edit Candidate";

require_once "../config/config.php";
require_once "../config/database.php";
require_once "../includes/session.php";

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: candidates.php");
    exit();
}

$id = (int)$_GET['id'];

$stmt = $pdo->prepare("
    SELECT *
    FROM candidates
    WHERE id = ?
");

$stmt->execute([$id]);

$candidate = $stmt->fetch();

if (!$candidate) {
    header("Location: candidates.php");
    exit();
}

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $fullname = trim($_POST['fullname']);
    $year_level = trim($_POST['year_level']);
    $section = trim($_POST['section']);
    $bio = trim($_POST['bio']);
    $is_active = isset($_POST['is_active']) ? 1 : 0;

    $photo = $candidate['photo'];

    if (!empty($_FILES['photo']['name'])) {

        $extension = strtolower(pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION));

        $allowed = ["jpg","jpeg","png","webp"];

        if (!in_array($extension, $allowed)) {

            $error = "Only JPG, JPEG, PNG and WEBP files are allowed.";

        } else {

            if (!empty($photo) && file_exists("../uploads/".$photo)) {
                unlink("../uploads/".$photo);
            }

            $photo = uniqid().".".$extension;

            move_uploaded_file(
                $_FILES['photo']['tmp_name'],
                "../uploads/".$photo
            );

        }

    }

    if (empty($error)) {

        $stmt = $pdo->prepare("
            UPDATE candidates
            SET
                fullname = ?,
                year_level = ?,
                section = ?,
                photo = ?,
                bio = ?,
                is_active = ?
            WHERE id = ?
        ");

        $stmt->execute([
            $fullname,
            $year_level,
            $section,
            $photo,
            $bio,
            $is_active,
            $id
        ]);

        $_SESSION['candidate_success'] = [
    "title" => "Candidate Updated!",
    "text" => "The candidate information has been updated successfully.",
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

Edit Candidate

</h2>

<p class="text-muted">

Update candidate information.

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
value="<?= htmlspecialchars($candidate['fullname']); ?>"
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

<option <?= $candidate['year_level']=="1st Year"?"selected":"" ?>>1st Year</option>

<option <?= $candidate['year_level']=="2nd Year"?"selected":"" ?>>2nd Year</option>

<option <?= $candidate['year_level']=="3rd Year"?"selected":"" ?>>3rd Year</option>

<option <?= $candidate['year_level']=="4th Year"?"selected":"" ?>>4th Year</option>

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
value="<?= htmlspecialchars($candidate['section']); ?>"
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
class="form-control"><?= htmlspecialchars($candidate['bio']); ?></textarea>

</div>

<div class="mb-3">

<label class="form-label">

Current Photo

</label>

<br>

<?php if(!empty($candidate['photo'])): ?>

<img
src="../uploads/<?= htmlspecialchars($candidate['photo']); ?>"
width="150"
class="rounded shadow mb-3">

<?php else: ?>

<p class="text-muted">

No photo uploaded.

</p>

<?php endif; ?>

</div>

<div class="mb-3">

<label class="form-label">

Replace Photo (Optional)

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
<?= $candidate['is_active'] ? "checked" : "" ?>>

<label class="form-check-label">

Active Candidate

</label>

</div>

<div class="d-grid gap-2">

<button
class="btn btn-warning btn-lg">

<i class="bi bi-pencil-square me-2"></i>

Update Candidate

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
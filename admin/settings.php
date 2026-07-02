<?php

$pageTitle = "Election Settings";

require_once "../config/config.php";
require_once "../config/database.php";
require_once "../includes/session.php";
require_once "../includes/functions.php";

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $voting_open = isset($_POST['voting_open']) ? 1 : 0;
    $show_results = isset($_POST['show_results']) ? 1 : 0;

    $stmt = $pdo->prepare("
        UPDATE election_settings
        SET
            voting_open = ?,
            show_results = ?
        WHERE id = 1
    ");

    $stmt->execute([
        $voting_open,
        $show_results
    ]);

    logActivity(
    $pdo,
    "admin",
    $_SESSION['admin_id'],
    "Updated election settings"
);

    $_SESSION['settings_success'] = true;

    header("Location: settings.php");
    exit();
}

$stmt = $pdo->query("
    SELECT *
    FROM election_settings
    WHERE id = 1
");

$settings = $stmt->fetch();

include "../includes/header.php";
?>

<?php if(isset($_SESSION['settings_success'])): ?>

<script>

document.addEventListener("DOMContentLoaded",function(){

Swal.fire({

icon:"success",

title:"Settings Saved",

text:"Election settings have been updated.",

confirmButtonColor:"#001F54"

});

});

</script>

<?php unset($_SESSION['settings_success']); endif; ?>

<section class="py-5" style="background:#f5f7fa;min-height:90vh;">

<div class="container">

<div class="row justify-content-center">

<div class="col-lg-7">

<div class="card shadow border-0 rounded-4">

<div class="card-body p-5">

<h2 class="fw-bold text-primary mb-4">

<i class="bi bi-gear-fill me-2"></i>

Election Settings

</h2>

<form method="POST">

<div class="form-check form-switch mb-4">

<input
class="form-check-input"
type="checkbox"
name="voting_open"
<?= $settings['voting_open'] ? 'checked' : '' ?>>

<label class="form-check-label">

Allow Students to Vote

</label>

</div>

<div class="form-check form-switch mb-4">

<input
class="form-check-input"
type="checkbox"
name="show_results"
<?= $settings['show_results'] ? 'checked' : '' ?>>

<label class="form-check-label">

Allow Students to View Results

</label>

</div>

<button class="btn btn-primary">

<i class="bi bi-save me-2"></i>

Save Changes

</button>

<a href="dashboard.php" class="btn btn-secondary">

Back

</a>

</form>

</div>

</div>

</div>

</div>

</div>

</section>

<?php include "../includes/footer.php"; ?>
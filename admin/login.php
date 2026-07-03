<?php

$pageTitle = "Administrator Login";

require_once "../config/config.php";
require_once "../config/database.php";
require_once "../includes/session.php";

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = trim($_POST['username']);
    $password = $_POST['password'];

    $stmt = $pdo->prepare("
        SELECT *
        FROM admins
        WHERE username = ?
        LIMIT 1
    ");

    $stmt->execute([$username]);

    $admin = $stmt->fetch();

    if (!$admin) {

        $error = "Invalid username.";

    } elseif (!password_verify($password, $admin['password'])) {

        $error = "Incorrect password.";

    } else {

        $_SESSION['admin_id'] = $admin['id'];
        $_SESSION['admin_name'] = $admin['fullname'];

        header("Location: dashboard.php");
        exit();

    }

}

include "../includes/header.php";

?>

<section class="d-flex align-items-center dashboard-section">

<div class="container">

<div class="row justify-content-center">

<div class="col-lg-5">

<div class="card dashboard-card border-0">

<div class="card-body p-5">

<div class="text-center mb-4">

<img src="<?= BASE_URL ?>assets/images/ssite-logo.png" width="90" class="mb-3">

<h2 class="fw-bold text-primary mb-2">

<i class="bi bi-shield-lock-fill me-2"></i>

Administrator Login

</h2>

<p class="text-muted">

Sign in to manage the SSITE Elections Portal.

</p>

</div>

<?php if(!empty($error)): ?>

<div class="alert alert-light border rounded-4 mb-4">

<i class="bi bi-info-circle-fill text-primary me-2"></i>

Only authorized administrators can access this portal.

</div>

<script>

document.addEventListener("DOMContentLoaded",function(){

Swal.fire({

icon:"error",

title:"Login Failed",

text:"<?= htmlspecialchars($error,ENT_QUOTES) ?>"

});

});

</script>



<?php endif; ?>

<form method="POST">

<div class="mb-3">

<label class="form-label">

Username

</label>

<div class="input-group">



<span class="input-group-text">

<i class="bi bi-person-fill"></i>

</span>

<input
type="text"
name="username"
class="form-control"
placeholder="Enter username"
required>

</div>

</div>

<div class="mb-4">

<label class="form-label">

Password

</label>

<div class="input-group">

<span class="input-group-text">

<i class="bi bi-lock-fill"></i>

</span>

<input
type="password"
id="password"
name="password"
class="form-control"
placeholder="Enter password"
required>

<button
class="btn btn-outline-secondary"
type="button"
onclick="togglePassword()">

<i class="bi bi-eye"></i>

</button>

</div>

</div>

<button
class="btn btn-primary rounded-pill w-100 btn-lg">

<i class="bi bi-box-arrow-in-right me-2"></i>

Login

</button>

<div class="text-center mt-4">

<a href="<?= BASE_URL ?>index.php"
class="text-decoration-none">

<i class="bi bi-house-door-fill me-1"></i>

Return to Homepage

</a>

</div>

</form>

<hr>

<div class="text-center text-muted small">

<i class="bi bi-shield-check me-1"></i>

Protected Administrator Portal

</div>

</div>

</div>

</div>

</div>

</div>

</section>

<script>

function togglePassword(){

const input=document.getElementById("password");
const icon=document.querySelector("button i");

if(input.type==="password"){

input.type="text";
icon.classList.replace("bi-eye","bi-eye-slash");

}else{

input.type="password";
icon.classList.replace("bi-eye-slash","bi-eye");

}

}

</script>

<?php include "../includes/footer.php"; ?>
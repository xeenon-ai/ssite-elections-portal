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

<section class="d-flex align-items-center" style="min-height:90vh;background:#f5f7fa;">

<div class="container">

<div class="row justify-content-center">

<div class="col-lg-5">

<div class="card shadow-lg border-0 rounded-4">

<div class="card-body p-5">

<div class="text-center mb-4">

<img src="<?= BASE_URL ?>assets/images/ssite-logo.png" width="90" class="mb-3">

<h2 class="fw-bold text-primary">

Administrator Login

</h2>

<p class="text-muted">

SSITE Elections Portal

</p>

</div>

<?php if(!empty($error)): ?>

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

<input
type="text"
name="username"
class="form-control"
required>

</div>

<div class="mb-4">

<label class="form-label">

Password

</label>

<div class="input-group">

<input
type="password"
id="password"
name="password"
class="form-control"
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
class="btn btn-primary w-100 btn-lg">

<i class="bi bi-box-arrow-in-right me-2"></i>

Login

</button>

</form>

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
<?php

$pageTitle = "Student Login";

require_once "../config/config.php";
require_once "../includes/session.php";

include "../includes/header.php";

?>

<?php if (isset($_SESSION['success'])): ?>

<script>

document.addEventListener("DOMContentLoaded", function () {

    Swal.fire({

        icon: "success",

        title: "Account Verified!",

        text: "<?= $_SESSION['success']; ?>",

        confirmButtonColor: "#001F54"

    });

});

</script>

<?php unset($_SESSION['success']); endif; ?>

<section class="d-flex align-items-center" style="min-height:90vh; background:#f5f7fa;">

    <div class="container">

        <div class="row justify-content-center">

            <div class="col-lg-5">

                <div class="card shadow-lg border-0 rounded-4">

                    <div class="card-body p-5">

                        <div class="text-center mb-4">

<img src="../assets/images/ssite-logo.png">

                            <h2 class="fw-bold text-primary">
                                Student Login
                            </h2>

                            <p class="text-muted">
                                Enter your Student Number to receive a One-Time Password (OTP).
                            </p>

                        </div>

                        <form method="POST" action="">

                            <div class="mb-4">

                                <label class="form-label fw-semibold">
                                    Student Number
                                </label>

                                <input
                                    type="text"
                                    name="student_number"
                                    class="form-control form-control-lg"
                                    placeholder="Ex. 2024-00001"
                                    required>

                            </div>

                            <button
                                type="submit"
                                class="btn btn-primary btn-lg w-100">

                                <i class="bi bi-envelope-paper me-2"></i>

                                Send OTP

                            </button>

                        </form>

                        <div class="text-center mt-4">

<a href="<?= BASE_URL ?>index.php"class="text-decoration-none">

                                ← Back to Home

                            </a>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</section>

<?php include '../includes/footer.php'; ?>
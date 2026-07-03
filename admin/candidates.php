<?php

$pageTitle = "Manage Candidates";

require_once "../config/config.php";
require_once "../config/database.php";
require_once "../includes/session.php";

if(!isset($_SESSION['admin_id'])){
    header("Location: login.php");
    exit();
}

$stmt = $pdo->query("
    SELECT *
    FROM candidates
    ORDER BY fullname ASC
");

$candidates = $stmt->fetchAll();

include "admin-header.php";


?>

<?php if (isset($_SESSION['candidate_success'])): ?>

<script>

document.addEventListener("DOMContentLoaded", function(){

    Swal.fire({

        icon: "<?= $_SESSION['candidate_success']['icon']; ?>",

        title: "<?= $_SESSION['candidate_success']['title']; ?>",

        text: "<?= $_SESSION['candidate_success']['text']; ?>",

        confirmButtonColor: "#001F54"

    });

});

</script>

<?php unset($_SESSION['candidate_success']); endif; ?>

<section class="py-5" style="background:#f5f7fa;min-height:90vh;">

<div class="container">

<div class="d-flex justify-content-between align-items-center mb-4">

<div>

<h2 class="fw-bold text-primary mb-1">

<i class="bi bi-person-badge-fill me-2"></i>

Manage Candidates

</h2>

<p class="text-muted mb-0">

Manage election candidates and their information.

</p>

</div>

<div>

<a href="dashboard.php"
class="btn btn-secondary rounded-pill me-2">

<i class="bi bi-arrow-left me-1"></i>

Dashboard

</a>

<a href="add-candidate.php"
class="btn btn-primary rounded-pill px-4">

<i class="bi bi-person-plus-fill me-2"></i>

Add Candidate

</a>

</div>

</div>
<div class="card dashboard-card border-0">

<div class="card-body">

<table class="table table-hover align-middle">

<thead>

<tr>

<th>ID</th>

<th>Photo</th>

<th>Name</th>

<th>Year</th>

<th>Section</th>

<th>Status</th>

<th class="text-center" width="220">

Actions

</th>

</tr>

</thead>

<tbody>

<?php foreach($candidates as $candidate): ?>

<tr>

<td>

<?= $candidate['id']; ?>

</td>

<td>

<?php if(!empty($candidate['photo'])): ?>

<img
src="../uploads/<?= htmlspecialchars($candidate['photo']); ?>"
width="70"
height="70"
class="rounded-circle shadow"
style="object-fit:cover;">

<?php else: ?>

<img
src="../assets/images/default-user.png"
width="70"
class="rounded-circle shadow">

<?php endif; ?>

</td>

<td>

<?= htmlspecialchars($candidate['fullname']); ?>

</td>

<td>

<?= htmlspecialchars($candidate['year_level']); ?>

</td>

<td>

<?= htmlspecialchars($candidate['section']); ?>

</td>

<td>

<?php if($candidate['is_active']): ?>

<span class="badge bg-success">

<i class="bi bi-check-circle-fill me-1"></i>

Active

</span>

<?php else: ?>

<span class="badge bg-danger">

<i class="bi bi-x-circle-fill me-1"></i>

Inactive

</span>

<?php endif; ?>

</td>

<td class="text-center text-nowrap">

<a
href="edit-candidate.php?id=<?= $candidate['id']; ?>"
class="btn btn-outline-warning btn-sm rounded-pill me-1">

<i class="bi bi-pencil-fill me-1"></i>

Edit

</a>

<a
href="delete-candidate.php?id=<?= $candidate['id']; ?>"
class="btn btn-outline-danger btn-sm rounded-pill delete-btn"
data-name="<?= htmlspecialchars($candidate['fullname']); ?>">

<i class="bi bi-trash-fill me-1"></i>

Delete

</a>

</td>

</tr>

<?php endforeach; ?>

</tbody>

</table>

</div>

</div>

</div>

</section>

<script>

document.querySelectorAll(".delete-btn").forEach(button => {

    button.addEventListener("click", function(e){

        e.preventDefault();

        const url = this.href;
        const name = this.dataset.name;

        Swal.fire({

            icon: "warning",

            title: "Delete Candidate?",

            html: "Are you sure you want to delete <b>" + name + "</b>?<br><br>This action cannot be undone.",

            showCancelButton: true,

            confirmButtonText: "Yes, Delete",

            cancelButtonText: "Cancel",

            confirmButtonColor: "#dc3545"

        }).then((result)=>{

            if(result.isConfirmed){

                window.location.href = url;

            }

        });

    });

});

</script>

<?php include "../includes/footer.php"; ?>
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

include "../includes/header.php";

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

<h2 class="fw-bold text-primary">

Manage Candidates

</h2>

<a
href="add-candidate.php"
class="btn btn-primary">

<i class="bi bi-plus-circle me-2"></i>

Add Candidate

</a>

</div>

<div class="card shadow border-0">

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

<th width="180">Actions</th>

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
width="60"
height="60"
style="object-fit:cover;border-radius:50%;">

<?php else: ?>

<img
src="../assets/images/default-user.png"
width="60">

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

Active

</span>

<?php else: ?>

<span class="badge bg-danger">

Inactive

</span>

<?php endif; ?>

</td>

<td>

<a
href="edit-candidate.php?id=<?= $candidate['id']; ?>"
class="btn btn-warning btn-sm">

Edit

</a>

<a
href="delete-candidate.php?id=<?= $candidate['id']; ?>"
class="btn btn-danger btn-sm delete-btn"
data-name="<?= htmlspecialchars($candidate['fullname']); ?>">

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
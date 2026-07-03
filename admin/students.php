<?php

$pageTitle = "Manage Students";

require_once "../config/config.php";
require_once "../config/database.php";
require_once "../includes/session.php";

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

$search = trim($_GET['search'] ?? '');

if ($search != "") {

    $stmt = $pdo->prepare("
        SELECT *
        FROM students
        WHERE
            student_number LIKE ?
            OR fullname LIKE ?
        ORDER BY fullname ASC
    ");

    $keyword = "%{$search}%";

    $stmt->execute([$keyword, $keyword]);

} else {

    $stmt = $pdo->query("
        SELECT *
        FROM students
        ORDER BY fullname ASC
    ");

}

$students = $stmt->fetchAll();

include "admin-header.php";

?>

<?php if(isset($_SESSION['student_success'])): ?>

<script>

document.addEventListener("DOMContentLoaded", function(){

    Swal.fire({

        icon: "<?= $_SESSION['student_success']['icon']; ?>",

        title: "<?= $_SESSION['student_success']['title']; ?>",

        text: "<?= $_SESSION['student_success']['text']; ?>",

        confirmButtonColor:"#001F54"

    });

});

</script>

<?php unset($_SESSION['student_success']); endif; ?>

<section class="py-5" style="background:#f5f7fa;min-height:90vh;">

<div class="container">

<div class="d-flex justify-content-between align-items-center mb-4">

<div class="d-flex justify-content-between align-items-center mb-4">

<div>

<h2 class="fw-bold text-primary mb-1">

<i class="bi bi-people-fill me-2"></i>

Manage Students

</h2>

<p class="text-muted mb-0">

Manage registered student accounts and voting status.

</p>

</div>

<a href="add-student.php"

class="btn btn-primary rounded-pill px-4">

<i class="bi bi-person-plus-fill me-2"></i>

Add Student

</a>

</div>
<a href="dashboard.php" class="btn btn-secondary">

<i class="bi bi-arrow-left"></i>

Back

</a>

</div>

<form method="GET" class="mb-4">

<div class="input-group">

<span class="input-group-text">

<i class="bi bi-search"></i>

</span>

<input
type="text"
name="search"
class="form-control"
placeholder="Search student number or name..."
value="<?= htmlspecialchars($search) ?>">

<button class="btn btn-primary">

<i class="bi bi-search"></i>

Search

</button>

</div>

</form>

<div class="card shadow border-0">

<div class="card-body">

<table class="table table-hover align-middle">

<thead>

<tr>

<th>Student No.</th>

<th>Name</th>

<th>Course</th>

<th>Year</th>

<th>Verified</th>

<th>Status</th>

<th>Voted</th>

<th>Action</th>

</tr>

</thead>

<tbody>

<?php foreach($students as $student): ?>

<tr>

<td><?= htmlspecialchars($student['student_number']) ?></td>

<td><?= htmlspecialchars($student['fullname']) ?></td>

<td><?= htmlspecialchars($student['course']) ?></td>

<td><?= htmlspecialchars($student['year_level']) ?></td>


<td>

<?php if($student['is_verified']): ?>

<span class="badge bg-success">
<i class="bi bi-patch-check-fill me-1"></i>
Verified
</span>

<?php else: ?>

<span class="badge bg-warning text-dark">
<i class="bi bi-clock-fill me-1"></i>
Pending
</span>

<?php endif; ?>

</td>

<!-- ADD THIS BLOCK HERE -->

<td>

<?php if($student['is_active']): ?>

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

<!-- END OF NEW BLOCK -->


<td>

<?php if($student['has_voted']): ?>

<span class="badge bg-primary">
<i class="bi bi-check2-square me-1"></i>
Voted
</span>

<?php else: ?>

<span class="badge bg-warning text-dark">Not Yet</span>

<?php endif; ?>

</td>

<td class="text-nowrap">

<a
href="view-student.php?id=<?= $student['id']; ?>"
class="btn btn-outline-primary btn-sm rounded-pill me-1">

<i class="bi bi-eye-fill"></i>

</a>

<?php if($student['is_active']): ?>

<a
href="toggle-student.php?id=<?= $student['id']; ?>"
class="btn btn-outline-warning btn-sm rounded-pill me-1">

<i class="bi bi-person-x-fill me-1"></i>

Deactivate

</a>

<?php else: ?>

<a
href="toggle-student.php?id=<?= $student['id']; ?>"
class="btn btn-outline-success btn-sm rounded-pill me-1">

<i class="bi bi-person-check-fill me-1"></i>

Activate

</a>

<?php endif; ?>

<a
href="request-delete-student.php?id=<?= $student['id']; ?>"
class="btn btn-outline-danger btn-sm rounded-pill delete-btn"
data-name="<?= htmlspecialchars($student['fullname']); ?>">

<i class="bi bi-trash-fill"></i>

</a>

</td></tr>

<?php endforeach; ?>

</tbody>

</table>

</div>

</div>

</div>

</section>
<script>

document.querySelectorAll(".delete-btn").forEach(button=>{

button.addEventListener("click",function(e){

e.preventDefault();

const url=this.href;

const name=this.dataset.name;

Swal.fire({

icon:"warning",

title:"Delete Student?",

html:"Delete <b>"+name+"</b>?<br><br>An OTP will be sent to the administrator email.",

showCancelButton:true,

confirmButtonText:"Continue",

cancelButtonText:"Cancel",

confirmButtonColor:"#dc3545"

}).then((result)=>{

if(result.isConfirmed){

window.location=url;

}

});

});

});

</script>
<?php include "../includes/footer.php"; ?>
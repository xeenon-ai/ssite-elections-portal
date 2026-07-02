<?php

$pageTitle = "Vote";

require_once "../config/config.php";
require_once "../config/database.php";
require_once "../includes/session.php";

if (!isset($_SESSION['student_id'])) {

    header("Location: ../auth/login.php");
    exit();

}

$studentId = $_SESSION['student_id'];

// Get student
$stmt = $pdo->prepare("
    SELECT *
    FROM students
    WHERE id = ?
");

$stmt->execute([$studentId]);

$student = $stmt->fetch();

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $selected = $_POST['candidates'] ?? [];

    if (count($selected) != 3) {

        $error = "Please select exactly 3 candidates.";

    } else {

        foreach ($selected as $candidateId) {

            $stmt = $pdo->prepare("
                INSERT INTO votes (student_id, candidate_id)
                VALUES (?, ?)
            ");

            $stmt->execute([
                $studentId,
                $candidateId
            ]);

        }

        $stmt = $pdo->prepare("
            UPDATE students
            SET has_voted = 1
            WHERE id = ?
        ");

        $stmt->execute([$studentId]);

        header("Location: success.php");
        exit();

    }

}

// Already voted?
if ($student['has_voted']) {

    header("Location: success.php");
    exit();

}

// Get active candidates
$stmt = $pdo->query("
    SELECT *
    FROM candidates
    WHERE is_active = 1
    ORDER BY fullname ASC
");

$candidates = $stmt->fetchAll();

include "../includes/header.php";

?>

<section class="py-5" style="background:#f5f7fa;min-height:90vh;">

<div class="container">

<div class="text-center mb-5">

<img
src="<?= BASE_URL ?>assets/images/ssite-logo.png"
width="100"
class="mb-3">

<h2 class="fw-bold text-primary">

SSITE Elections 2026

</h2>

<p class="lead">

Welcome,

<strong><?= htmlspecialchars($student['fullname']); ?></strong>

</p>

<p class="text-muted">

Choose exactly <strong>3 candidates</strong>.

Once submitted, your vote cannot be changed.

</p>

<div class="alert alert-warning mt-3">

<i class="bi bi-exclamation-triangle-fill"></i>

You may only vote once.

</div>

</div>

<form method="POST" id="voteForm">

<div class="row g-4">


<?php foreach($candidates as $candidate): ?>

<div class="col-md-4">

<div class="card h-100 shadow border-0 rounded-4 candidate-card">

<?php if(!empty($candidate['photo'])): ?>

<img
src="../uploads/<?= htmlspecialchars($candidate['photo']); ?>"
class="card-img-top"
style="height:280px;object-fit:cover;">

<?php else: ?>

<img
src="<?= BASE_URL ?>assets/images/default-user.png"
class="card-img-top"
style="height:280px;object-fit:cover;">

<?php endif; ?>

<div class="card-body">

<h4 class="fw-bold text-primary">

<?= htmlspecialchars($candidate['fullname']); ?>

</h4>

<p class="text-muted">

<?= htmlspecialchars($candidate['year_level']); ?>

•
<?= htmlspecialchars($candidate['section']); ?>

</p>

<hr>

<p>

<?= nl2br(htmlspecialchars($candidate['bio'])); ?>

</p>

<div class="form-check mt-4">

<input
class="form-check-input candidateCheck"
type="checkbox"
name="candidates[]"
value="<?= $candidate['id']; ?>">

<label class="form-check-label fw-bold">

Vote for this Candidate

</label>

</div>

</div>

</div>

</div>

<?php endforeach; ?>

</div>

<div class="text-center mt-5">

<div class="alert alert-primary d-inline-block px-5">

<h4 class="mb-0">

Selected

<span id="selectedCount">

0

</span>

/3

</h4>

</div>

<button
class="btn btn-success btn-lg mt-3">

<i class="bi bi-check-circle-fill me-2"></i>

Submit My Votes

</button>

</div>

</form>

</div>

</section>
<script>

const checks = document.querySelectorAll(".candidateCheck");
const counter = document.getElementById("selectedCount");
const form = document.getElementById("voteForm");

checks.forEach(check => {

    check.addEventListener("change", () => {

        let selected = document.querySelectorAll(".candidateCheck:checked");

        if(selected.length > 3){

            check.checked = false;

            Swal.fire({
                icon:"warning",
                title:"Maximum Reached",
                text:"You may only vote for 3 candidates."
            });

            selected = document.querySelectorAll(".candidateCheck:checked");

        }

        counter.innerHTML = selected.length;

    });

});

form.addEventListener("submit", function(e){

    const selected = document.querySelectorAll(".candidateCheck:checked");

    if(selected.length != 3){

        e.preventDefault();

        Swal.fire({

            icon:"warning",

            title:"Incomplete Vote",

            text:"Please select exactly 3 candidates."

        });

        return;

    }

    e.preventDefault();

    Swal.fire({

        icon:"question",

        title:"Submit Your Vote?",

        html:"<b>You cannot change your vote after submission.</b><br><br>Do you want to continue?",

        showCancelButton:true,

        confirmButtonText:"Yes, Submit",

        cancelButtonText:"Cancel",

        confirmButtonColor:"#198754"

    }).then((result)=>{

        if(result.isConfirmed){

            form.submit();

        }

    });

});

</script>

<?php include "../includes/footer.php"; ?>
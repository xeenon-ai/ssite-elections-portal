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

    if (count($selected) != 15) {
        $error = "Please select exactly 15 candidates.";

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

include "student-header.php";

?>

<section class="py-5 dashboard-section">
<div class="container">

<div class="text-center mb-5">

<img
src="<?= BASE_URL ?>assets/images/ssite-logo.png"
width="100"
class="mb-3">

<h2 class="display-5 fw-bold text-primary">

🗳️ SSITE Elections 2026

</h2>

<p class="text-muted">

Student Society of Information Technology Education

</p>

<p class="lead">

Welcome,

<strong><?= htmlspecialchars($student['fullname']); ?></strong>

</p>

<div class="mt-3">

<span class="badge bg-success fs-6 rounded-pill px-4 py-2">

🟢 Voting is Open

</span>

</div>

<p class="text-muted">

Choose exactly <strong>15 candidates</strong>.

Once submitted, your vote cannot be changed.

</p>

<div class="alert alert-light border rounded-4 mt-4">

<h5 class="fw-bold text-primary">

<i class="bi bi-info-circle-fill me-2"></i>

Voting Instructions

</h5>

<ul class="mb-0 text-start">

<li>Select exactly <strong>15 candidates</strong>.</li>
<li>Your vote can only be submitted once.</li>

<li>Your vote cannot be changed after submission.</li>

<li>Please review your selections before submitting.</li>

</ul>

</div>


<div class="progress mb-4" style="height:12px;">

<div
id="voteProgress"
class="progress-bar bg-success"
style="width:0%;">

</div>

</div>

<form method="POST" id="voteForm">

<div class="row g-4">

<?php foreach($candidates as $candidate): ?>

<div class="col-md-4">

<div class="card dashboard-card candidate-card h-100">

    <!-- Selected Ribbon -->
    <div class="selectedRibbon d-none">

        <i class="bi bi-check-circle-fill me-1"></i>

        Selected

    </div>

    <!-- Candidate Photo -->

    <?php if(!empty($candidate['photo'])): ?>

        <img
        src="../uploads/<?= htmlspecialchars($candidate['photo']); ?>"
        class="card-img-top candidate-photo"
        alt="<?= htmlspecialchars($candidate['fullname']); ?>">

    <?php else: ?>

        <img
        src="<?= BASE_URL ?>assets/images/default-user.png"
        class="card-img-top candidate-photo"
        alt="Default Photo">

    <?php endif; ?>

<div class="card-body candidate-body">

    <h3 class="candidate-name">

        <?= htmlspecialchars($candidate['fullname']); ?>

    </h3>

    <span class="candidate-badge">

        <i class="bi bi-patch-check-fill me-1"></i>

        Candidate

    </span>

    <div class="candidate-meta">

        <div>

            <i class="bi bi-mortarboard-fill"></i>

            <?= htmlspecialchars($candidate['year_level']); ?>

        </div>

        <div>

            <i class="bi bi-book-fill"></i>

            <?= htmlspecialchars($candidate['section']); ?>

        </div>

    </div>

    <hr>

    <h6 class="about-title">

        About Me

    </h6>

    <p class="candidate-bio">

        <?= nl2br(htmlspecialchars($candidate['bio'])); ?>

    </p>

    <div class="select-area mt-auto">

        <input
        class="candidateCheck form-check-input"
        type="checkbox"
        name="candidates[]"
        value="<?= $candidate['id']; ?>">

        <span class="voteStatus badge bg-light text-dark ms-2">

            Click to Select

        </span>

    </div>

</div>

</div>

</div>

<?php endforeach; ?>

<div class="text-center mt-5">

<div class="alert alert-primary rounded-pill d-inline-block px-5 shadow">

<h4 class="mb-0">

Selected

<span id="selectedCount">0</span>/15

</h4>

</div>

<button
id="submitVote"
disabled
class="btn btn-success btn-lg rounded-pill px-5 mt-3">

<i class="bi bi-check-circle-fill me-2"></i>

Submit My Votes

</button>

</div>

</form>

</div>

</section>
<script>
const checks = document.querySelectorAll(".candidateCheck");
const cards = document.querySelectorAll(".candidate-card");

const counter = document.getElementById("selectedCount");
const progress = document.getElementById("voteProgress");
const submitBtn = document.getElementById("submitVote");
const form = document.getElementById("voteForm");

function updateSelection(){

    const selected = document.querySelectorAll(".candidateCheck:checked");

    counter.textContent = selected.length;

    progress.style.width = (selected.length / 15 * 100) + "%";
    submitBtn.disabled = selected.length !== 15;

    cards.forEach(card=>{

        const badge = card.querySelector(".voteStatus");
        const ribbon = card.querySelector(".selectedRibbon");
        const checkbox = card.querySelector(".candidateCheck");

        card.classList.remove("selected");

        ribbon.classList.add("d-none");

badge.className =
"voteStatus badge rounded-pill bg-light text-dark px-3 py-2 ms-2";
        badge.innerHTML = "Not Selected";

        checkbox.disabled = selected.length >= 15 && !checkbox.checked;

    });

    selected.forEach(item=>{

        const card = item.closest(".candidate-card");

        const badge = card.querySelector(".voteStatus");

        const ribbon = card.querySelector(".selectedRibbon");

        card.classList.add("selected");

        ribbon.classList.remove("d-none");

badge.className =
"voteStatus badge rounded-pill bg-success px-3 py-2 ms-2";
        badge.innerHTML =
        '<i class="bi bi-check-circle-fill me-1"></i>Selected';

    });

}

checks.forEach(check=>{

    check.addEventListener("change",updateSelection);

});

cards.forEach(card=>{

    card.addEventListener("click",function(e){

        if(e.target.closest(".candidateCheck")) return;

        const checkbox = this.querySelector(".candidateCheck");

        if(checkbox.disabled) return;

        checkbox.checked = !checkbox.checked;

        checkbox.dispatchEvent(new Event("change"));

    });

});

form.addEventListener("submit",function(e){

    const selected = document.querySelectorAll(".candidateCheck:checked");

    if(selected.length !== 15){
        e.preventDefault();

        Swal.fire({

            icon:"warning",

            title:"Incomplete Vote",

            text:"Please select exactly fifteen candidates."

        });

        return;

    }

    e.preventDefault();

    Swal.fire({

        icon:"question",

        title:"Submit Vote?",

        html:"<b>Your vote cannot be changed after submission.</b>",

        showCancelButton:true,

        confirmButtonText:"Submit Vote",

        cancelButtonText:"Cancel",

        confirmButtonColor:"#198754"

    }).then((result)=>{

        if(result.isConfirmed){

            form.submit();

        }

    });

});

updateSelection();
</script>

<?php include "../includes/footer.php"; ?>
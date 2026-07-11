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

<div class="container-fluid px-4">

    <!-- HERO -->

    <div class="vote-hero mb-4">

        <div class="row align-items-center">

            <div class="col-lg-8">

                <div class="d-flex align-items-center">

                    <img
                        src="<?= BASE_URL ?>assets/images/ssite-logo.png"
                        class="vote-logo me-4"
                        alt="SSITE Logo">

                    <div>

                        <h1 class="vote-title">

                            SSITE Elections 2026

                        </h1>

                        <p class="vote-subtitle mb-2">

                            Student Society of Information Technology Education

                        </p>

                        <h5 class="mb-0">

                            Welcome,

                            <strong><?= htmlspecialchars($student['fullname']); ?></strong>

                            👋

                        </h5>

                    </div>

                </div>

            </div>

            <div class="col-lg-4 text-lg-end mt-4 mt-lg-0">

                <span class="badge bg-success fs-6 rounded-pill px-4 py-3">

                    <i class="bi bi-check-circle-fill me-2"></i>

                    Voting Open

                </span>

            </div>

        </div>

    </div>

    <!-- Instructions -->

    <div class="alert alert-light border shadow-sm rounded-4 mb-4">

        <h5 class="fw-bold text-primary mb-3">

            <i class="bi bi-info-circle-fill me-2"></i>

            Voting Instructions

        </h5>

        <ul class="mb-0">

            <li>Select exactly <strong>15 candidates</strong>.</li>

            <li>Your vote can only be submitted once.</li>

            <li>Your vote cannot be changed after submission.</li>

            <li>Please review your selections before submitting.</li>

        </ul>

    </div>

    <div class="row">

        <!-- LEFT SIDE -->

        <div class="col-xl-9">

            <form method="POST" id="voteForm">

                <div class="row g-4">

<?php foreach($candidates as $candidate): ?>

<div class="col-lg-4 col-md-6">

    <div class="card vote-candidate-card h-100">

        <div class="selectedRibbon d-none">

            <i class="bi bi-check-circle-fill me-1"></i>

            Selected

        </div>

        <?php if(!empty($candidate['photo'])): ?>

            <img
                src="../uploads/<?= htmlspecialchars($candidate['photo']); ?>"
                class="vote-candidate-photo"
                alt="<?= htmlspecialchars($candidate['fullname']); ?>">

        <?php else: ?>

            <img
                src="<?= BASE_URL ?>assets/images/default-user.png"
                class="vote-candidate-photo"
                alt="Candidate">

        <?php endif; ?>

        <div class="card-body d-flex flex-column">

            <h4 class="vote-candidate-name">

                <?= htmlspecialchars($candidate['fullname']); ?>

            </h4>

            <span class="vote-candidate-badge">

                Candidate

            </span>

            <div class="vote-candidate-meta">

                <span>

                    <i class="bi bi-mortarboard-fill me-1"></i>

                    <?= htmlspecialchars($candidate['year_level']); ?>

                </span>

                <span>

                    <i class="bi bi-people-fill me-1"></i>

                    <?= htmlspecialchars($candidate['section']); ?>

                </span>

            </div>

            <hr>

            <h6 class="fw-bold text-primary">

                About Me

            </h6>

            <p class="vote-candidate-bio flex-grow-1">

                <?= nl2br(htmlspecialchars($candidate['bio'])); ?>

            </p>

            <div class="vote-select-box mt-3">

                <input

                    type="checkbox"

                    class="candidateCheck form-check-input"

                    name="candidates[]"

                    value="<?= $candidate['id']; ?>">

                <span class="vote-selection-status ms-2">

                    Click to Select

                </span>

            </div>

        </div>

    </div>

</div>

<?php endforeach; ?>

                </div>

            </form>

        </div>

        <!-- RIGHT SIDE -->

        <div class="col-xl-3">

            <div class="vote-sidebar">

                <div class="vote-summary">

                    <h4 class="fw-bold text-primary">

                        Your Vote

                    </h4>

                    <hr>

                    <h1 id="selectedCountSide">

                        0

                    </h1>

                    <p class="text-muted">

                        Selected out of 15

                    </p>

                    <div class="progress mb-4">

                        <div

                            id="voteProgressSide"

                            class="progress-bar"

                            style="width:0%">

                        </div>

                    </div>

                    <div class="remaining-box">

                        Remaining

                        <strong id="remainingCount">

                            15

                        </strong>

                    </div>

                    <hr>

<h6 class="fw-bold text-primary mb-3">

    <i class="bi bi-person-check-fill me-2"></i>

    Selected Candidates

</h6>

<div id="selectedCandidateList" class="selected-candidate-list">

    <div class="text-muted small">

        No candidates selected yet.

    </div>

</div>

                    <button

                        id="submitVoteSide"

                        class="btn btn-primary w-100 btn-lg rounded-pill mt-4"

                        disabled>

                        <i class="bi bi-check-circle-fill me-2"></i>

                        Submit Vote

                    </button>



                </div>



            </div>

        </div>

    </div>

</div>

</section>

<?php include "../includes/footer.php"; ?>

<script>

const MAX_VOTES = 15;

const form = document.getElementById("voteForm");

const cards = document.querySelectorAll(".vote-candidate-card");

const checks = document.querySelectorAll(".candidateCheck");

const selectedCount = document.getElementById("selectedCountSide");

const remainingCount = document.getElementById("remainingCount");

const progressBar = document.getElementById("voteProgressSide");

const selectedList =
document.getElementById("selectedCandidateList");

const submitButton = document.getElementById("submitVoteSide");

function updateSelection(){



    const selected = document.querySelectorAll(".candidateCheck:checked");

    selectedCount.textContent = selected.length;

    remainingCount.textContent = MAX_VOTES - selected.length;

    selectedList.innerHTML = "";

if(selected.length === 0){

    selectedList.innerHTML =
    '<div class="text-muted small">No candidates selected yet.</div>';

}else{

    selected.forEach(item=>{

        const card =
            item.closest(".vote-candidate-card");
const name =
card.querySelector(".vote-candidate-name").textContent;

const photo =
card.querySelector(".vote-candidate-photo").src;

selectedList.innerHTML +=
`
<div class="selected-person">

    <div class="selected-person-left">

        <img
            src="${photo}"
            class="selected-person-photo">

        <div class="selected-person-name">

            ${name}

        </div>

    </div>

    <button
        type="button"
        class="remove-selected"
        data-id="${item.value}">

        <i class="bi bi-x-lg"></i>

    </button>

</div>
`;

    });

}

    progressBar.style.width =
        (selected.length / MAX_VOTES * 100) + "%";

    submitButton.disabled = selected.length !== MAX_VOTES;

    cards.forEach(card=>{

        const ribbon = card.querySelector(".selectedRibbon");

        const badge = card.querySelector(".vote-selection-status");

        const checkbox = card.querySelector(".candidateCheck");

        card.classList.remove("selected");

        ribbon.classList.add("d-none");

        badge.innerHTML = "Click to Select";

        badge.classList.remove("selected");

        checkbox.disabled =
            selected.length >= MAX_VOTES && !checkbox.checked;

    });

    selected.forEach(item=>{

        const card = item.closest(".vote-candidate-card");

        const ribbon = card.querySelector(".selectedRibbon");

        const badge = card.querySelector(".vote-selection-status");

        card.classList.add("selected");

        ribbon.classList.remove("d-none");

        badge.innerHTML =
            '<i class="bi bi-check-circle-fill me-1"></i> Selected';

        badge.classList.add("selected");

    });

}

checks.forEach(check=>{

    check.addEventListener("change",updateSelection);

});

cards.forEach(card=>{

    card.addEventListener("click",function(e){

        if(
            e.target.tagName === "INPUT" ||
            e.target.closest("label")
        ) return;

        const checkbox = this.querySelector(".candidateCheck");

        if(checkbox.disabled) return;

        checkbox.checked = !checkbox.checked;

        updateSelection();

        document.addEventListener("click",function(e){

    const btn=e.target.closest(".remove-selected");

    if(!btn) return;

    const checkbox=document.querySelector(
        '.candidateCheck[value="'+btn.dataset.id+'"]'
    );

    if(!checkbox) return;

    checkbox.checked=false;

    checkbox.dispatchEvent(new Event("change"));

});

    });

});

submitButton.addEventListener("click",()=>{

    form.requestSubmit();

});

form.addEventListener("submit",function(e){

    const selected =
        document.querySelectorAll(".candidateCheck:checked");

    if(selected.length !== MAX_VOTES){

        e.preventDefault();

        Swal.fire({

            icon:"warning",

            title:"Incomplete Vote",

            text:
            `Please select exactly ${MAX_VOTES} candidates.`

        });

        return;

    }

    e.preventDefault();

    Swal.fire({

        icon:"question",

        title:"Submit Vote?",

        html:
        "<b>Your vote cannot be changed after submission.</b>",

        showCancelButton:true,

        confirmButtonColor:"#0d6efd",

        confirmButtonText:"Submit Vote",

        cancelButtonText:"Cancel"

    }).then(result=>{

        if(result.isConfirmed){

            form.submit();

        }

    });

});

updateSelection();



</script> 
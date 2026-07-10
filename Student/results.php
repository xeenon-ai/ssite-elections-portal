<?php

$pageTitle = "Election Results";

require_once "../config/config.php";
require_once "../config/database.php";
require_once "../includes/session.php";

if(!isset($_SESSION['student_id'])){
    header("Location: ../auth/login.php");
    exit();
}

$stmt = $pdo->query("
    SELECT show_results
    FROM election_settings
    LIMIT 1
");

$settings = $stmt->fetch();

$stmt = $pdo->prepare("
    SELECT *
    FROM students
    WHERE id = ?
");

$stmt->execute([
    $_SESSION['student_id']
]);

$student = $stmt->fetch();

// ===========================
// Election Summary
// ===========================

// Total Candidates
$totalCandidates = $pdo->query("
    SELECT COUNT(*)
    FROM candidates
    WHERE is_active = 1
")->fetchColumn();

// Total Votes Cast
$totalVotes = $pdo->query("
    SELECT COUNT(*)
    FROM votes
")->fetchColumn();

// Total Registered Students
$totalStudents = $pdo->query("
    SELECT COUNT(*)
    FROM students
")->fetchColumn();

// Voter Turnout
$turnout = $totalStudents > 0
    ? round(($totalVotes / $totalStudents) * 100)
    : 0;


// ===========================
// Candidate Rankings
// ===========================

$stmt = $pdo->query("
    SELECT
        c.id,
        c.fullname,
        c.photo,
        c.year_level,
        c.section,
        COUNT(v.id) AS total_votes

    FROM candidates c

    LEFT JOIN votes v
        ON c.id = v.candidate_id

    WHERE c.is_active = 1

    GROUP BY c.id

    ORDER BY total_votes DESC,
             c.fullname ASC
");

$rankings = $stmt->fetchAll();

include "student-header.php";

?>

<section class="py-5 dashboard-section">

<div class="container">

<h2 class="fw-bold text-primary mb-4">

    <i class="bi bi-trophy-fill me-2"></i>

    Election Results

</h2>

<?php if(!$settings['show_results']): ?>

<div class="row justify-content-center">

<div class="col-lg-7">

<div class="card shadow border-0 rounded-4">

<div class="card-body text-center p-5">

<i class="bi bi-lock-fill display-1 text-warning mb-4"></i>

<h2 class="fw-bold">

Results Not Available Yet

</h2>

<p class="text-muted mt-3">

The official election results have not yet been published by the Election Committee.

Please check back later.

</p>

<div class="alert alert-warning mt-4">

<i class="bi bi-info-circle-fill me-2"></i>

Status:
<strong>Hidden by Administrator</strong>

</div>

<a href="dashboard.php"

class="btn btn-primary rounded-pill px-5 mt-3">

<i class="bi bi-arrow-left"></i>

Back to Dashboard

</a>

</div>

</div>

</div>

</div>

<?php else: ?>

<div class="row g-4 mb-5">

    <div class="col-md-3">

        <div class="card shadow-sm border-0 text-center p-4">

            <i class="bi bi-people-fill display-5 text-primary"></i>

            <h2 class="mt-3">

                <?= $totalCandidates ?>

            </h2>

            <p class="text-muted mb-0">

                Candidates

            </p>

        </div>

    </div>

    <div class="col-md-3">

        <div class="card shadow-sm border-0 text-center p-4">

            <i class="bi bi-check2-square display-5 text-success"></i>

            <h2 class="mt-3">

                <?= $totalVotes ?>

            </h2>

            <p class="text-muted mb-0">

                Votes Cast

            </p>

        </div>

    </div>

    <div class="col-md-3">

        <div class="card shadow-sm border-0 text-center p-4">

            <i class="bi bi-person-vcard-fill display-5 text-warning"></i>

            <h2 class="mt-3">

                <?= $totalStudents ?>

            </h2>

            <p class="text-muted mb-0">

                Registered Students

            </p>

        </div>

    </div>

    <div class="col-md-3">

        <div class="card shadow-sm border-0 text-center p-4">

            <i class="bi bi-bar-chart-fill display-5 text-danger"></i>

            <h2 class="mt-3">

                <?= $turnout ?>%

            </h2>

            <p class="text-muted mb-0">

                Voter Turnout

            </p>

        </div>

    </div>

</div>


<div class="card shadow border-0 rounded-4">

<div class="card-header bg-primary text-white">

<h4 class="mb-0">

🏆 Candidate Rankings

</h4>

</div>

<div class="table-responsive">

<table class="table table-hover align-middle mb-0">

<thead>

<tr>

<th width="80">Rank</th>

<th>Candidate</th>

<th>Year</th>

<th>Section</th>

<th class="text-center">Votes</th>

</tr>

</thead>

<tbody>

<?php

$rank = 1;

foreach($rankings as $candidate):

?>

<tr>

<td>

<?php

if($rank == 1){

    echo "🥇";

}elseif($rank == 2){

    echo "🥈";

}elseif($rank == 3){

    echo "🥉";

}else{

    echo "#".$rank;

}

?>

</td>

<td>

<div class="d-flex align-items-center">

<?php if(!empty($candidate['photo'])): ?>

<img
src="../uploads/<?= htmlspecialchars($candidate['photo']) ?>"
width="55"
height="55"
class="rounded-circle me-3"
style="object-fit:cover;">

<?php else: ?>

<img
src="<?= BASE_URL ?>assets/images/default-user.png"
width="55"
height="55"
class="rounded-circle me-3">

<?php endif; ?>

<div>

<strong>

<?= htmlspecialchars($candidate['fullname']) ?>

</strong>

</div>

</div>

</td>

<td>

<?= htmlspecialchars($candidate['year_level']) ?>

</td>

<td>

<?= htmlspecialchars($candidate['section']) ?>

</td>

<td class="text-center fw-bold">

<?= $candidate['total_votes'] ?>

</td>

</tr>

<?php

$rank++;

endforeach;

?>

</tbody>

</table>

</div>

</div>

<?php endif; ?>

</div>

</section>

<?php include "../includes/footer.php"; ?>
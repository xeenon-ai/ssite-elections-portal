<?php

$pageTitle = "Election Results";

require_once "../config/config.php";
require_once "../config/database.php";
require_once "../includes/session.php";

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

/* ===========================
   Dashboard Statistics
=========================== */

$totalStudents = $pdo->query("
SELECT COUNT(*)
FROM students
")->fetchColumn();

$totalCandidates = $pdo->query("
SELECT COUNT(*)
FROM candidates
")->fetchColumn();

$totalVotes = $pdo->query("
SELECT COUNT(*)
FROM votes
")->fetchColumn();

$totalVoted = $pdo->query("
SELECT COUNT(*)
FROM students
WHERE has_voted = 1
")->fetchColumn();

$turnout = 0;

if($totalStudents > 0){
    $turnout = round(($totalVoted / $totalStudents) * 100, 2);
}

/* ===========================
   Top Candidates
=========================== */

$stmt = $pdo->query("

SELECT

c.id,
c.fullname,
c.photo,
c.year_level,
c.section,
c.bio,

COUNT(v.id) AS total_votes

FROM candidates c

LEFT JOIN votes v
ON c.id = v.candidate_id

GROUP BY
c.id,
c.fullname,
c.photo,
c.year_level,
c.section,
c.bio

ORDER BY total_votes DESC,
c.fullname ASC

LIMIT 10

");

$leaders = $stmt->fetchAll();

include "../includes/header.php";

?>

<section class="py-5" style="background:#f5f7fa;min-height:90vh;">

<div class="container">

<h2 class="fw-bold text-primary mb-5">

<i class="bi bi-trophy-fill me-2"></i>

Election Results

</h2>

<div class="row g-4">

<div class="col-lg-3">

<div class="card shadow border-0 text-center">

<div class="card-body">

<i class="bi bi-people-fill display-4 text-primary"></i>

<h2><?= $totalStudents ?></h2>

<p class="mb-0">Registered Students</p>

</div>

</div>

</div>

<div class="col-lg-3">

<div class="card shadow border-0 text-center">

<div class="card-body">

<i class="bi bi-check2-square display-4 text-success"></i>

<h2><?= $totalVotes ?></h2>

<p class="mb-0">Votes Cast</p>

</div>

</div>

</div>

<div class="col-lg-3">

<div class="card shadow border-0 text-center">

<div class="card-body">

<i class="bi bi-bar-chart-fill display-4 text-warning"></i>

<h2><?= $turnout ?>%</h2>

<p class="mb-0">Turnout</p>

</div>

</div>

</div>

<div class="col-lg-3">

<div class="card shadow border-0 text-center">

<div class="card-body">

<i class="bi bi-person-badge-fill display-4 text-danger"></i>

<h2><?= $totalCandidates ?></h2>

<p class="mb-0">Candidates</p>

</div>

</div>

</div>

</div>

<?php if(count($leaders) > 0): ?>

<div class="card shadow-lg border-0 rounded-4 mt-5">

<div class="card-body text-center">

<h3 class="text-warning fw-bold">

🏆 Election Leader

</h3>

<?php if(!empty($leaders[0]['photo'])): ?>

<img
src="../uploads/<?= htmlspecialchars($leaders[0]['photo']) ?>"
class="rounded-circle shadow mt-3"
width="150"
height="150"
style="object-fit:cover;">

<?php else: ?>

<img
src="../assets/images/default-user.png"
class="rounded-circle shadow mt-3"
width="150">

<?php endif; ?>

<h2 class="mt-4">

<?= htmlspecialchars($leaders[0]['fullname']) ?>

</h2>

<p>

<?= htmlspecialchars($leaders[0]['year_level']) ?>

•

<?= htmlspecialchars($leaders[0]['section']) ?>

</p>

<h1 class="text-primary">

<?= $leaders[0]['total_votes'] ?>

Votes

</h1>

</div>

</div>

<?php endif; ?>

<div class="card shadow border-0 rounded-4 mt-5">

<div class="card-body">

<h3 class="fw-bold mb-4">

🏅 Top 10 Candidates

</h3>

<?php

$highestVotes = count($leaders) ? max(array_column($leaders, 'total_votes')) : 1;

foreach($leaders as $index => $candidate):

$percentage = ($highestVotes > 0)
? ($candidate['total_votes'] / $highestVotes) * 100
: 0;

?>

<div class="row align-items-center mb-4">

<div class="col-lg-1 text-center">

<?php

$rank = $index + 1;

if($rank == 1){

echo "<span style='font-size:30px;'>🥇</span>";

}elseif($rank == 2){

echo "<span style='font-size:30px;'>🥈</span>";

}elseif($rank == 3){

echo "<span style='font-size:30px;'>🥉</span>";

}else{

echo "<strong>#{$rank}</strong>";

}

?>

</div>

<div class="col-lg-2">

<?php if(!empty($candidate['photo'])): ?>

<img
src="../uploads/<?= htmlspecialchars($candidate['photo']) ?>"
class="rounded-circle shadow"
width="70"
height="70"
style="object-fit:cover;">

<?php else: ?>

<img
src="../assets/images/default-user.png"
class="rounded-circle"
width="70">

<?php endif; ?>

</div>

<div class="col-lg-3">

<h5 class="mb-1">

<?= htmlspecialchars($candidate['fullname']) ?>

</h5>

<small class="text-muted">

<?= htmlspecialchars($candidate['year_level']) ?>

•

<?= htmlspecialchars($candidate['section']) ?>

</small>

</div>

<div class="col-lg-4">

<div class="progress" style="height:25px;">

<div
class="progress-bar bg-primary"
style="width:<?= $percentage ?>%;">

<?= $candidate['total_votes'] ?>

</div>

</div>

</div>

<div class="col-lg-2 text-end">

<strong>

<?= $candidate['total_votes'] ?>

Votes

</strong>

</div>

</div>

<hr>

<?php endforeach; ?>

</div>

</div>

</div>

</section>

<?php include "../includes/footer.php"; ?>
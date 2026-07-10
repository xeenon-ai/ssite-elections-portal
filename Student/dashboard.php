<?php

$pageTitle = "Student Dashboard";

require_once "../config/config.php";
require_once "../config/database.php";
require_once "../includes/session.php";

if (!isset($_SESSION['student_id'])) {

    header("Location: ../auth/login.php");
    exit();

}

$stmt = $pdo->prepare("
    SELECT *
    FROM students
    WHERE id = ?
");

$stmt->execute([
    $_SESSION['student_id']
]);

$student = $stmt->fetch();

$stmt = $pdo->query("
    SELECT *
    FROM candidates
    WHERE is_active = 1
    ORDER BY RAND()
");

$candidates = $stmt->fetchAll();

include "student-header.php";

?>

<section class="py-5 dashboard-section">
<div class="container-fluid px-0">

<!-- ===========================
     Candidate Auto Carousel
=========================== -->

<div class="carousel-wrapper">
    <div class="carousel-wrapper py-2">

    <div class="candidate-strip" id="candidate-strip">

        <?php
        $colors = ['accent-blue','accent-gold','accent-navy'];
        ?>

        <?php foreach($candidates as $candidate): ?>

            <?php $accent = $colors[array_rand($colors)]; ?>

            <div class="candidate-card-hero">

                <div class="chero-img <?= $accent; ?>">

                    <?php if(!empty($candidate['photo'])): ?>

                        <img
                        src="../uploads/<?= htmlspecialchars($candidate['photo']); ?>"
                        alt="<?= htmlspecialchars($candidate['fullname']); ?>">

                    <?php else: ?>

                        <img
                        src="<?= BASE_URL ?>assets/images/default-user.png"
                        alt="Candidate">

                    <?php endif; ?>

                </div>

                <div class="candidate-label">

                    <div class="c-name">

                        <?= htmlspecialchars($candidate['fullname']); ?>

                    </div>

<div class="c-role">

<?= htmlspecialchars($candidate['section']); ?>

    •

    <?= htmlspecialchars($candidate['year_level']); ?>

</div>

                </div>

            </div>

        <?php endforeach; ?>



        <!-- Duplicate Cards for Infinite Loop -->

        <?php foreach($candidates as $candidate): ?>

            <?php $accent = $colors[array_rand($colors)]; ?>

            <div class="candidate-card-hero" aria-hidden="true">

                <div class="chero-img <?= $accent; ?>">

                    <?php if(!empty($candidate['photo'])): ?>

                        <img
                        src="../uploads/<?= htmlspecialchars($candidate['photo']); ?>">

                    <?php else: ?>

                        <img
                        src="<?= BASE_URL ?>assets/images/default-user.png">

                    <?php endif; ?>

                </div>

                <div class="candidate-label">

                    <div class="c-name">

                        <?= htmlspecialchars($candidate['fullname']); ?>

                    </div>

                    <div class="c-role">
<?= htmlspecialchars($candidate['year_level']); ?>

                    </div>

                </div>

            </div>

        <?php endforeach; ?>

    </div>

</div>

<div class="row justify-content-center">

<div class="col-lg-8">

    <div class="card shadow-lg border-0 rounded-4">

<div class="card-body p-5">

<div class="welcome-hero">

    <div class="hero-avatar">

        <i class="bi bi-person-circle"></i>

    </div>

    <div class="hero-content">

        <h2>

            Welcome Back,

            <span>

                <?= htmlspecialchars($student['fullname']); ?>

            </span>

            👋

        </h2>

        <div class="hero-badges">

            <span class="badge bg-success">

                <i class="bi bi-patch-check-fill"></i>

                Verified Student

            </span>

        </div>

        <p>

            <?= htmlspecialchars($student['course']); ?>

            •

            <?= htmlspecialchars($student['year_level']); ?>

            •

            <?= htmlspecialchars($student['section']); ?>

        </p>

    </div>

</div>
<div class="row g-4 mb-4">

    <div class="col-md-6 col-lg-3">

        <div class="info-card">

            <div class="info-icon bg-primary">

                <i class="bi bi-person-vcard-fill"></i>

            </div>

            <div class="info-title">

                Student Number

            </div>

            <div class="info-value">

                <?= htmlspecialchars($student['student_number']); ?>

            </div>

        </div>

    </div>

    <div class="col-md-6 col-lg-3">

        <div class="info-card">

            <div class="info-icon bg-success">

                <i class="bi bi-book-fill"></i>

            </div>

            <div class="info-title">

                Course

            </div>

            <div class="info-value">

                <?= htmlspecialchars($student['course']); ?>

            </div>

        </div>

    </div>

    <div class="col-md-6 col-lg-3">

        <div class="info-card">

            <div class="info-icon bg-warning">

                <i class="bi bi-mortarboard-fill"></i>

            </div>

            <div class="info-title">

                Year Level

            </div>

            <div class="info-value">

                <?= htmlspecialchars($student['year_level']); ?>

            </div>

        </div>

    </div>

    <div class="col-md-6 col-lg-3">

        <div class="info-card">

            <div class="info-icon bg-danger">

                <i class="bi bi-people-fill"></i>

            </div>

            <div class="info-title">

                Section

            </div>

            <div class="info-value">

                <?= htmlspecialchars($student['section']); ?>

            </div>

        </div>

    </div>

</div>
<div class="status-card">

    <div class="status-header">

        <div>

            <h4>

                <i class="bi bi-check2-circle"></i>

                Election Status

            </h4>

            <small>

                SSITE Student Elections

            </small>

        </div>

        <?php if($student['has_voted']): ?>

            <span class="status-badge success">

                Voted

            </span>

        <?php else: ?>

            <span class="status-badge warning">

                Pending

            </span>

        <?php endif; ?>

    </div>

    <hr>

    <?php if($student['has_voted']): ?>

        <div class="status-message success">

            <i class="bi bi-patch-check-fill"></i>

            <div>

                <h5>

                    Vote Successfully Submitted

                </h5>

                <p>

                    Thank you for participating in the SSITE Elections.
                    Your vote has been securely recorded.

                </p>

            </div>

        </div>

    <?php else: ?>

        <div class="status-message warning">

            <i class="bi bi-exclamation-circle-fill"></i>

            <div>

                <h5>

                    You haven't voted yet.

                </h5>

                <p>

                    Cast your vote before the election closes.

                </p>

            </div>

        </div>

        <a href="vote.php"

           class="btn btn-primary btn-lg rounded-pill mt-4 px-5">

            <i class="bi bi-check2-square-fill me-2"></i>

            Vote Now

        </a>

    <?php endif; ?>

</div>

<h4 class="section-title mt-5 mb-4">

<i class="bi bi-lightning-charge-fill text-warning me-2"></i>

Quick Actions

</h4>

<div class="row g-4">

    <!-- Vote -->

    <div class="col-md-4">

        <a href="vote.php" class="action-card">

            <div class="action-icon bg-primary">

                <i class="bi bi-check2-square"></i>

            </div>

            <h5>Vote Now</h5>

            <p>

                Cast your official vote in the SSITE Elections.

            </p>

        </a>

    </div>

    <!-- Candidates -->

 <div class="col-md-4">

    <a href="results.php" class="action-card">

        <div class="action-icon bg-warning">

            <i class="bi bi-bar-chart-fill"></i>

        </div>

        <h5>Election Results</h5>

        <p>View the official election results once they are published.</p>

    </a>

</div>

    <!-- Logout -->

    <div class="col-md-4">

        <a href="../auth/logout.php" class="action-card">

            <div class="action-icon bg-danger">

                <i class="bi bi-box-arrow-right"></i>

            </div>

            <h5>Logout</h5>

            <p>

                Securely sign out of your student account.

            </p>

        </a>

    </div>

</div>

</div>

</div>

</div>

</div>

</div>

</section>

<?php include "../includes/footer.php"; ?>

<script>

function initCarousel(){

    const wrapper = document.querySelector(".carousel-wrapper");
    const strip = document.getElementById("candidate-strip");

    if(!wrapper || !strip) return;

    let position = 0;
    let paused = false;

    const SPEED = 0.45;

    const halfWidth = strip.scrollWidth / 2;

    function animate(){

        if(!paused){

            position += SPEED;

if(position >= halfWidth){

    position -= halfWidth;

}

            strip.style.transform =
                `translateX(-${position}px)`;

        }

        requestAnimationFrame(animate);

    }

    wrapper.addEventListener("mouseenter",()=>{

        paused = true;

    });

    wrapper.addEventListener("mouseleave",()=>{

        paused = false;

    });

    animate();

}

document.addEventListener("DOMContentLoaded",function(){

    initCarousel();

});

</script>
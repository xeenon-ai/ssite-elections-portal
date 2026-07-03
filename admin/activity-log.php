<?php

$pageTitle = "Activity Log";

require_once "../config/config.php";
require_once "../config/database.php";
require_once "../includes/session.php";

if(!isset($_SESSION['admin_id'])){
    header("Location: login.php");
    exit();
}

$stmt = $pdo->query("
SELECT

activity_logs.*,

admins.fullname AS admin_name,

students.fullname AS student_name

FROM activity_logs

LEFT JOIN admins
ON activity_logs.user_type='admin'
AND activity_logs.user_id=admins.id

LEFT JOIN students
ON activity_logs.user_type='student'
AND activity_logs.user_id=students.id

ORDER BY activity_logs.created_at DESC
");
$logs = $stmt->fetchAll();

include "admin-header.php";


?>

<section class="py-5" style="background:#f5f7fa;min-height:90vh;">

<div class="container">
<div class="d-flex justify-content-between align-items-center mb-4">

<div>

<h2 class="fw-bold text-primary mb-1">

<i class="bi bi-clock-history me-2"></i>

Activity Log

</h2>

<p class="text-muted mb-0">

View administrator and student activities within the election system.

</p>

</div>

<a
href="dashboard.php"
class="btn btn-secondary rounded-pill">

<i class="bi bi-arrow-left me-1"></i>

Dashboard

</a>

</div>

<div class="card dashboard-card border-0">

<div class="card-body">


    <div class="d-flex justify-content-between align-items-center mb-3">

<h5 class="mb-0">

Recent Activities

</h5>

<span class="badge bg-secondary">

<?= count($logs) ?> Records

</span>

</div>

<table class="table table-hover align-middle">

<thead class="table-dark">

<tr>

<th>Date</th>

<th>User Type</th>

<th>User ID</th>

<th>Activity</th>

<th>IP Address</th>

</tr>

</thead>

<tbody>

<?php foreach($logs as $log): ?>

<tr>

<td>

<?= date("M d, Y h:i A",strtotime($log['created_at'])) ?>

</td>

<td>

<?php if($log['user_type']=="admin"): ?>

<span class="badge bg-danger">

<i class="bi bi-shield-lock-fill me-1"></i>

Administrator

</span>

<?php else: ?>

<span class="badge bg-primary">

<i class="bi bi-person-fill me-1"></i>

Student

</span>

<?php endif; ?>

</td>

<td>

<?php if($log['user_type']=="admin"): ?>

<?= htmlspecialchars($log['admin_name']) ?>

<?php else: ?>

<?= htmlspecialchars($log['student_name']) ?>

<?php endif; ?> 

</td>

<td>

<span class="fw-semibold">

<?= htmlspecialchars($log['activity']) ?>

</span>

</td>
<td>

<code>

<?= htmlspecialchars($log['ip_address']) ?>

</code>

</td>

</tr>

<?php endforeach; ?>

</tbody>

</table>

</div>

</div>

</div>

</section>

<?php include "../includes/footer.php"; ?>
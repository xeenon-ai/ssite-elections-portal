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
SELECT *
FROM activity_logs
ORDER BY created_at DESC
");

$logs = $stmt->fetchAll();

include "../includes/header.php";

?>

<section class="py-5" style="background:#f5f7fa;min-height:90vh;">

<div class="container">

<div class="d-flex justify-content-between align-items-center mb-4">

<h2 class="fw-bold text-primary">

<i class="bi bi-clock-history me-2"></i>

Activity Log

</h2>

<a
href="dashboard.php"
class="btn btn-secondary">

Back

</a>

</div>

<div class="card shadow border-0">

<div class="card-body">

<table class="table table-hover align-middle">

<thead>

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

Administrator

</span>

<?php else: ?>

<span class="badge bg-primary">

Student

</span>

<?php endif; ?>

</td>

<td>

<?= $log['user_id'] ?>

</td>

<td>

<?= htmlspecialchars($log['activity']) ?>

</td>

<td>

<?= htmlspecialchars($log['ip_address']) ?>

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
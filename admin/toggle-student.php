<?php

require_once "../config/config.php";
require_once "../config/database.php";
require_once "../includes/session.php";

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: students.php");
    exit();
}

$id = (int)$_GET['id'];

// Get current status
$stmt = $pdo->prepare("
    SELECT is_active
    FROM students
    WHERE id = ?
");

$stmt->execute([$id]);

$student = $stmt->fetch();

if (!$student) {
    header("Location: students.php");
    exit();
}

// Toggle status
$newStatus = $student['is_active'] ? 0 : 1;

$stmt = $pdo->prepare("
    UPDATE students
    SET is_active = ?
    WHERE id = ?
");

$stmt->execute([$newStatus, $id]);

if ($newStatus) {

    $_SESSION['student_success'] = [
        "title" => "Student Activated!",
        "text"  => "The student account has been activated.",
        "icon"  => "success"
    ];

} else {

    $_SESSION['student_success'] = [
        "title" => "Student Deactivated!",
        "text"  => "The student account has been deactivated.",
        "icon"  => "warning"
    ];

}

header("Location: students.php");
exit();
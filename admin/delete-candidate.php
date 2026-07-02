<?php

require_once "../config/config.php";
require_once "../config/database.php";
require_once "../includes/session.php";

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: candidates.php");
    exit();
}

$id = (int)$_GET['id'];

// Get photo
$stmt = $pdo->prepare("
    SELECT photo
    FROM candidates
    WHERE id = ?
");

$stmt->execute([$id]);

$candidate = $stmt->fetch();

if ($candidate) {

    if (!empty($candidate['photo']) && file_exists("../uploads/" . $candidate['photo'])) {

        unlink("../uploads/" . $candidate['photo']);

    }

    $stmt = $pdo->prepare("
        DELETE FROM candidates
        WHERE id = ?
    ");

    $stmt->execute([$id]);

}

$_SESSION['candidate_success'] = [
    "title" => "Candidate Deleted!",
    "text" => "The candidate has been removed successfully.",
    "icon" => "success"
];

header("Location: candidates.php");
exit();
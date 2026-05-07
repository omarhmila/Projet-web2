<?php
session_start();
require_once('../config/db.php');

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../front/index.php");
    exit();
}

$id = (int)$_GET['id'];

if ($id > 0) {
    $stmt = $conn->prepare("DELETE FROM voitures WHERE id = ?");
    $stmt->execute([$id]);
}

header("Location: dashboard.php");
exit();
?>
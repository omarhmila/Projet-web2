<?php
require_once(__DIR__ . '/user.class.php');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: liste.php');
    exit();
}

$id = trim($_POST['id'] ?? '');
if ($id === '') {
    header('Location: liste.php');
    exit();
}

$us = new utilisateur();
$res = $us->getuser($id);
if (!$res->fetch(PDO::FETCH_ASSOC)) {
    header('Location: liste.php');
    exit();
}

$us->supprimer_user($id);
header('Location: liste.php');
exit();
?>

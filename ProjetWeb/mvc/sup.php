<?php
require_once(__DIR__ . '/user.class.php');

$id = trim($_GET['id'] ?? '');
if ($id === '') {
    header('Location: liste.php');
    exit();
}

$us = new utilisateur();
$us->supprimer_user($id);
header('Location: liste.php');
exit();
?>

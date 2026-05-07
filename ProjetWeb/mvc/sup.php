<?php
require_once(__DIR__ . '/user.class.php');

$id = $_GET['id'] ?? '';
if (!is_numeric($id)) {
    header('Location: liste.php');
    exit();
}

$us = new utilisateur();
$us->supprimer_user((int)$id);
header('Location: liste.php');
exit();
?>

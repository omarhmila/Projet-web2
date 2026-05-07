<?php
require_once(__DIR__ . '/user.class.php');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: inscriptionForm.php');
    exit();
}

$us = new utilisateur();
$us->user_nom = trim($_POST['nomuser'] ?? '');
$us->user_email = trim($_POST['emailuser'] ?? '');

if ($us->user_nom === '' || $us->user_email === '') {
    header('Location: inscriptionForm.php?error=Champs%20obligatoires');
    exit();
}

$row = $us->recherche_user();
$n = $row->fetchColumn(0);

if ($n == 0) {
    $us->insertuser();
    setcookie('last_user_name', $us->user_nom, time() + 86400 * 7, '/');
    header('Location: liste.php');
    exit();
}

header('Location: inscriptionForm.php?error=Email%20deja%20existant');
exit();
?>

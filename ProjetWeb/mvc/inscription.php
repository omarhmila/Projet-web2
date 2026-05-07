<?php
require_once(__DIR__ . '/user.class.php');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: inscriptionForm.php');
    exit();
}

$us = new utilisateur();
$us->user_cin = trim($_POST['cinuser'] ?? '');
$us->user_nom = trim($_POST['nomuser'] ?? '');

if ($us->user_cin === '' || $us->user_nom === '') {
    header('Location: inscriptionForm.php?error=Champs%20obligatoires');
    exit();
}

$row = $us->recherche_user();
$n = $row->fetchColumn(0);

if ((int)$n === 0) {
    try {
        $us->insertuser();
        setcookie('last_user_name', $us->user_nom, time() + 86400 * 7, '/');
        header('Location: liste.php');
        exit();
    } catch (PDOException $e) {
        error_log('Insert user error: ' . $e->getMessage());
        header('Location: inscriptionForm.php?error=Erreur%20lors%20de%20l%27insertion');
        exit();
    }
}

header('Location: inscriptionForm.php?error=CIN%20déjà%20existant');
exit();
?>

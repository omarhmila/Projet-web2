<?php
require_once(__DIR__ . '/user.class.php');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: liste.php');
    exit();
}

$cin = trim($_POST['cin'] ?? '');
if ($cin === '') {
    header('Location: liste.php');
    exit();
}

$us = new utilisateur();
$us->user_nom = trim($_POST['nom'] ?? '');

if ($us->user_nom === '') {
    header('Location: modifForm.php?id=' . urlencode($cin) . '&error=Nom%20obligatoire');
    exit();
}

$us->modifier_user($cin);
setcookie('last_user_name', $us->user_nom, time() + 86400 * 7, '/');
header('Location: liste.php');
exit();
?>

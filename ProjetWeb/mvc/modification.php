<?php
require_once(__DIR__ . '/user.class.php');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: liste.php');
    exit();
}

$id = $_POST['id'] ?? '';
if (!is_numeric($id)) {
    header('Location: liste.php');
    exit();
}

$us = new utilisateur();
$us->user_nom = trim($_POST['nom'] ?? '');
$us->user_email = trim($_POST['email'] ?? '');

if ($us->user_nom === '' || $us->user_email === '') {
    header('Location: modifForm.php?id=' . urlencode((string)$id));
    exit();
}

$us->modifier_user((int)$id);
setcookie('last_user_name', $us->user_nom, time() + 86400 * 7, '/');
header('Location: liste.php');
exit();
?>

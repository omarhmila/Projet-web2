<?php
require_once(__DIR__ . '/user.class.php');

$id = $_GET['id'] ?? '';
if (!is_numeric($id)) {
    header('Location: liste.php');
    exit();
}

$us = new utilisateur();
$res = $us->getuser((int)$id);
$data = $res->fetch(PDO::FETCH_ASSOC);

if (!$data) {
    header('Location: liste.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier utilisateur</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 2rem; }
        .box { max-width: 500px; margin: auto; }
        input { width: 100%; padding: .6rem; margin: .4rem 0 1rem; }
        button { padding: .6rem 1rem; }
        .hint { color: #555; font-size: .9rem; }
    </style>
</head>
<body>
<div class="box">
    <h2>Modifier utilisateur</h2>
    <form method="POST" action="modification.php" id="editForm">
        <input type="hidden" name="id" value="<?= htmlspecialchars((string)$data['id']) ?>">

        <label for="nom">Nom</label>
        <input type="text" id="nom" name="nom" value="<?= htmlspecialchars($data['nom']) ?>" required>

        <label for="email">Email</label>
        <input type="email" id="email" name="email" value="<?= htmlspecialchars($data['email']) ?>" required>

        <p class="hint">DOM : longueur du nom = <span id="nomLength">0</span></p>
        <button type="submit">Enregistrer</button>
    </form>

    <p><a href="liste.php">Retour à la liste</a></p>
</div>

<script>
const nom = document.getElementById('nom');
const nomLength = document.getElementById('nomLength');

function updateLength() {
    nomLength.textContent = nom.value.trim().length;
}

nom.addEventListener('input', updateLength);
updateLength();
</script>
</body>
</html>

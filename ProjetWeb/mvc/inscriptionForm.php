<?php
$lastUserName = $_COOKIE['last_user_name'] ?? '';
$message = $_GET['message'] ?? '';
$error = $_GET['error'] ?? '';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription MVC</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 2rem; }
        .box { max-width: 500px; margin: auto; }
        input { width: 100%; padding: .6rem; margin: .4rem 0 1rem; }
        button { padding: .6rem 1rem; }
        .ok { color: green; }
        .err { color: red; }
        .hint { color: #555; font-size: .9rem; }
    </style>
</head>
<body>
<div class="box">
    <h2>Inscription utilisateur (MVC)</h2>

    <?php if ($lastUserName): ?>
        <p class="hint">Dernier utilisateur enregistré (cookie) : <strong><?= htmlspecialchars($lastUserName) ?></strong></p>
    <?php endif; ?>

    <?php if ($message): ?>
        <p class="ok"><?= htmlspecialchars($message) ?></p>
    <?php endif; ?>

    <?php if ($error): ?>
        <p class="err"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form method="POST" action="inscription.php" id="userForm">
        <label for="cin">CIN</label>
        <input type="text" id="cin" name="cinuser" required>

        <label for="nom">Nom</label>
        <input type="text" id="nom" name="nomuser" required>

        <p class="hint">Aperçu DOM : <span id="preview">Aucun nom saisi</span></p>
        <button type="submit" id="submitBtn" disabled>Ajouter</button>
    </form>

    <p><a href="liste.php">Voir la liste</a></p>
</div>

<script>
const cinInput = document.getElementById('cin');
const nomInput = document.getElementById('nom');
const preview = document.getElementById('preview');
const submitBtn = document.getElementById('submitBtn');

function refreshDomState() {
    const cin = cinInput.value.trim();
    const nom = nomInput.value.trim();

    preview.textContent = nom ? nom : 'Aucun nom saisi';
    submitBtn.disabled = cin.length < 4 || nom.length < 2;
}

cinInput.addEventListener('input', refreshDomState);
nomInput.addEventListener('input', refreshDomState);
cinInput.focus();
</script>
</body>
</html>

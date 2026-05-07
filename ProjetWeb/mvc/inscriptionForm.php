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
        <label for="nom">Nom</label>
        <input type="text" id="nom" name="nomuser" required>

        <label for="email">Email</label>
        <input type="email" id="email" name="emailuser" required>

        <p class="hint">Aperçu DOM : <span id="preview">Aucun nom saisi</span></p>
        <button type="submit" id="submitBtn" disabled>Ajouter</button>
    </form>

    <p><a href="liste.php">Voir la liste</a></p>
</div>

<script>
const nomInput = document.getElementById('nom');
const emailInput = document.getElementById('email');
const preview = document.getElementById('preview');
const submitBtn = document.getElementById('submitBtn');

function refreshDomState() {
    const nom = nomInput.value.trim();
    const email = emailInput.value.trim();

    preview.textContent = nom ? nom : 'Aucun nom saisi';
    submitBtn.disabled = nom.length < 2 || !email.includes('@');
}

nomInput.addEventListener('input', refreshDomState);
emailInput.addEventListener('input', refreshDomState);
nomInput.focus();
</script>
</body>
</html>

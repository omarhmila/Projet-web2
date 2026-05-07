<?php
require_once(__DIR__ . '/user.class.php');
$us = new utilisateur();
$res = $us->listusers();
$lastUserName = $_COOKIE['last_user_name'] ?? '';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste utilisateurs MVC</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 2rem; }
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #ddd; padding: .6rem; }
        th { background: #f2f2f2; }
    </style>
</head>
<body>
    <h2>Liste des utilisateurs (MVC)</h2>

    <?php if ($lastUserName): ?>
        <p>Cookie (dernier nom enregistré) : <strong><?= htmlspecialchars($lastUserName) ?></strong></p>
    <?php endif; ?>

    <p><a href="inscriptionForm.php">Ajouter un utilisateur</a></p>

    <table>
        <tr>
            <th>CIN</th>
            <th>Nom</th>
            <th>Modifier</th>
            <th>Supprimer</th>
        </tr>
        <?php foreach ($res as $row): ?>
            <tr>
                <td><?= htmlspecialchars($row['user_cin']) ?></td>
                <td><?= htmlspecialchars($row['user_nom']) ?></td>
                <td><a href="modifForm.php?id=<?= urlencode($row['user_cin']) ?>">Modifier</a></td>
                <td><a href="sup.php?id=<?= urlencode($row['user_cin']) ?>" onclick="return confirm('Supprimer cet utilisateur ?');">Supprimer</a></td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>

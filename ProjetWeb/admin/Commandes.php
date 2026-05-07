<?php
session_start();
require_once('../config/db.php');

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../front/index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Commandes - Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
</head>
<body class="bg-slate-950 text-white">

<div class="flex min-h-screen">
    <!-- Sidebar identique -->
    <div class="w-72 bg-slate-900 p-6 border-r border-slate-700 fixed h-screen">
        <div class="flex items-center gap-3 mb-12">
            <div class="w-10 h-10 bg-yellow-400 rounded-xl flex items-center justify-center">
                <i class="fa-solid fa-car text-2xl text-slate-950"></i>
            </div>
            <span class="text-3xl font-bold">AutoLux</span>
        </div>
        <nav class="space-y-2">
            <a href="dashboard.php" class="flex items-center gap-3 px-6 py-4 hover:bg-slate-800 rounded-2xl transition">
                <i class="fa-solid fa-table"></i> Voitures
            </a>
            <a href="Utilisateurs.php" class="flex items-center gap-3 px-6 py-4 hover:bg-slate-800 rounded-2xl transition">
                <i class="fa-solid fa-users"></i> Utilisateurs
            </a>
            <a href="Commandes.php" class="flex items-center gap-3 px-6 py-4 bg-yellow-400 text-slate-900 rounded-2xl font-medium">
                <i class="fa-solid fa-list"></i> Commandes
            </a>
        </nav>
        <div class="absolute bottom-8 left-6 right-6">
                <a href="../logout.php" 
                   class="flex items-center justify-center gap-3 bg-red-500/10 hover:bg-red-500/20 text-red-400 hover:text-red-300 py-4 rounded-2xl transition font-medium">
                    <i class="fa-solid fa-right-from-bracket"></i>
                    <span>Déconnexion</span>
                </a>
            </div>
    </div>

    <!-- Contenu principal -->
    <div class="flex-1 ml-72 p-10">
        <h1 class="text-4xl font-bold mb-10">Gestion des Commandes</h1>

        <div class="bg-slate-900 rounded-3xl overflow-hidden">
            <table class="w-full">
                <thead class="bg-slate-800">
                    <tr>
                        <th class="text-left p-6">ID Commande</th>
                        <th class="text-left p-6">Client</th>
                        <th class="text-left p-6">Voiture</th>
                        <th class="text-left p-6">Quantité</th>
                        <th class="text-left p-6">Prix Total</th>
                        <th class="text-left p-6">Date</th>
                        <th class="text-center p-6">Statut</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-700">
                    <?php
                    $stmt = $conn->query("SELECT c.*, u.nom, u.prenom, v.marque, v.modele 
                                         FROM commandes c 
                                         JOIN users u ON c.user_id = u.id 
                                         JOIN voitures v ON c.voiture_id = v.id 
                                         ORDER BY c.date_commande DESC");
                    while($c = $stmt->fetch()):
                    ?>
                    <tr class="hover:bg-slate-800 transition">
                        <td class="p-6">#<?= $c['id'] ?></td>
                        <td class="p-6"><?= htmlspecialchars($c['nom'] . " " . $c['prenom']) ?></td>
                        <td class="p-6"><?= htmlspecialchars($c['marque'] . " " . $c['modele']) ?></td>
                        <td class="p-6 font-medium"><?= $c['quantite'] ?></td>
                        <td class="p-6 text-yellow-400 font-bold"><?= number_format($c['prix_total'], 0, ',', ' ') ?> DT</td>
                        <td class="p-6 text-slate-400"><?= date('d/m/Y H:i', strtotime($c['date_commande'])) ?></td>
                        <td class="p-6 text-center">
                            <span class="px-5 py-2 rounded-full text-sm <?= $c['statut']=='confirmée' ? 'bg-green-500/20 text-green-400' : 'bg-yellow-500/20 text-yellow-400' ?>">
                                <?= ucfirst($c['statut']) ?>
                            </span>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

</body>
</html>
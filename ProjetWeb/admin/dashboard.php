<?php
session_start();
require_once('../config/db.php');



if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../front/index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - AutoLux</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Playfair+Display:wght@700&display=swap');
    </style>
</head>
<body class="bg-slate-950 text-white">

    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <div class="w-72 bg-slate-900 p-6 border-r border-slate-700 fixed h-screen overflow-y-auto">
            <div class="flex items-center gap-3 mb-12">
                <div class="w-10 h-10 bg-yellow-400 rounded-xl flex items-center justify-center">
                    <i class="fa-solid fa-car text-2xl text-slate-950"></i>
                </div>
                <span class="text-3xl font-bold tracking-tighter">AutoLux</span>
            </div>

                <nav class="space-y-2">
                    <a href="dashboard.php" class="flex items-center gap-3 px-6 py-4 bg-yellow-400 text-slate-900 rounded-2xl font-medium">
                        <i class="fa-solid fa-table"></i> 
                        <span>Gestion des Voitures</span>
                    </a>
                    <a href="Utilisateurs.php" class="flex items-center gap-3 px-6 py-4 hover:bg-slate-800 rounded-2xl transition">
                        <i class="fa-solid fa-users"></i> 
                        <span>Utilisateurs</span>
                    </a>
                    <a href="Commandes.php" class="flex items-center gap-3 px-6 py-4 hover:bg-slate-800 rounded-2xl transition">
                        <i class="fa-solid fa-list"></i> 
                        <span>Commandes</span>
                    </a>
                </nav>

            <!-- Déconnexion en bas de sidebar -->
            <div class="absolute bottom-8 left-6 right-6">
                <a href="../logout.php" 
                   class="flex items-center justify-center gap-3 bg-red-500/10 hover:bg-red-500/20 text-red-400 hover:text-red-300 py-4 rounded-2xl transition font-medium">
                    <i class="fa-solid fa-right-from-bracket"></i>
                    <span>Déconnexion</span>
                </a>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1 ml-72 p-10">
            <div class="flex justify-between items-center mb-10">
                <div>
                    <h1 class="text-4xl font-bold">Gestion des Voitures</h1>
                    <p class="text-slate-400 mt-1">Bienvenue, Administrateur</p>
                </div>
                
                <a href="ajouter_voiture.php" 
                   class="bg-yellow-400 text-slate-900 px-8 py-4 rounded-2xl font-bold flex items-center gap-3 hover:bg-yellow-300 transition shadow-lg">
                    <i class="fa-solid fa-plus"></i> 
                    Ajouter une voiture
                </a>
            </div>

            <div class="bg-slate-900 rounded-3xl overflow-hidden border border-slate-700">
                <table class="w-full">
                    <thead class="bg-slate-800">
                        <tr>
                            <th class="text-left p-6">Image</th>
                            <th class="text-left p-6">Marque & Modèle</th>
                            <th class="text-left p-6">Prix</th>
                            <th class="text-left p-6">Stock</th>
                            <th class="text-center p-6">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-700">
                        <?php
                        $stmt = $conn->query("SELECT * FROM voitures ORDER BY marque");
                        while($v = $stmt->fetch()):
                        ?>
                        <tr class="hover:bg-slate-800/70 transition">
                            <td class="p-6">
                                <img src="../images/<?= htmlspecialchars($v['image']) ?>" 
                                     class="w-20 h-14 object-cover rounded-xl" alt="">
                            </td>
                            <td class="p-6 font-medium">
                                <?= htmlspecialchars($v['marque']) ?> <?= htmlspecialchars($v['modele']) ?>
                            </td>
                            <td class="p-6 text-yellow-400 font-bold">
                                <?= number_format($v['prix'], 0, ',', ' ') ?> DT
                            </td>
                            <td class="p-6">
                                <span class="px-4 py-2 rounded-full <?= $v['quantite'] > 0 ? 'bg-green-500/20 text-green-400' : 'bg-red-500/20 text-red-400' ?>">
                                    <?= $v['quantite'] ?> unités
                                </span>
                            </td>
                            <td class="p-6 text-center space-x-6">
                                <a href="modifier_voiture.php?id=<?= $v['id'] ?>" 
                                   class="text-blue-400 hover:text-blue-300 transition">
                                    <i class="fa-solid fa-edit text-xl"></i>
                                </a>
                                <a href="supprimer_voiture.php?id=<?= $v['id'] ?>" 
                                   onclick="return confirm('Voulez-vous vraiment supprimer cette voiture ?')"
                                   class="text-red-400 hover:text-red-300 transition">
                                    <i class="fa-solid fa-trash text-xl"></i>
                                </a>
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
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
    <title>Gestion des Utilisateurs - Admin</title>
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
            <a href="Utilisateurs.php" class="flex items-center gap-3 px-6 py-4 bg-yellow-400 text-slate-900 rounded-2xl font-medium">
                <i class="fa-solid fa-users"></i> Utilisateurs
            </a>
            <a href="Commandes.php" class="flex items-center gap-3 px-6 py-4 hover:bg-slate-800 rounded-2xl transition">
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
        <h1 class="text-4xl font-bold mb-10">Gestion des Utilisateurs</h1>

        <div class="bg-slate-900 rounded-3xl overflow-hidden">
            <table class="w-full">
                <thead class="bg-slate-800">
                    <tr>
                        <th class="text-left p-6">ID</th>
                        <th class="text-left p-6">Nom Complet</th>
                        <th class="text-left p-6">Email</th>
                        <th class="text-left p-6">Téléphone</th>
                        <th class="text-left p-6">Rôle</th>
                        <th class="text-center p-6">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-700">
                    <?php
                    $stmt = $conn->query("SELECT id, nom, prenom, email, telephone, role FROM users ORDER BY id DESC");
                    while($u = $stmt->fetch()):
                    ?>
                    <tr class="hover:bg-slate-800 transition">
                        <td class="p-6"><?= $u['id'] ?></td>
                        <td class="p-6"><?= htmlspecialchars($u['nom'] . " " . $u['prenom']) ?></td>
                        <td class="p-6"><?= htmlspecialchars($u['email']) ?></td>
                        <td class="p-6"><?= htmlspecialchars($u['telephone'] ?? 'Non renseigné') ?></td>
                        <td class="p-6">
                            <span class="px-4 py-2 rounded-full text-sm <?= $u['role']=='admin' ? 'bg-red-500/20 text-red-400' : 'bg-blue-500/20 text-blue-400' ?>">
                                <?= strtoupper($u['role']) ?>
                            </span>
                        </td>
                        <td class="p-6 text-center">
                            <a href="#" class="text-blue-400 hover:text-blue-300 mr-4">
                                <i class="fa-solid fa-edit"></i>
                            </a>
                            <a href="#" onclick="return confirm('Supprimer cet utilisateur ?')" class="text-red-400 hover:text-red-300">
                                <i class="fa-solid fa-trash"></i>
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
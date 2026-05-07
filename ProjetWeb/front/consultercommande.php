<?php
session_start();
require_once('../config/db.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$success = "";

// Annuler une commande
if (isset($_GET['annuler']) && is_numeric($_GET['annuler'])) {
    $commande_id = (int)$_GET['annuler'];

    // Récupérer les infos de la commande
    $stmt = $conn->prepare("SELECT voiture_id, quantite FROM commandes WHERE id = ? AND user_id = ? AND statut = 'en_attente'");
    $stmt->execute([$commande_id, $user_id]);
    $commande = $stmt->fetch();

    if ($commande) {
        // Augmenter la quantité dans la table voitures
        $stmt = $conn->prepare("UPDATE voitures SET quantite = quantite + ? WHERE id = ?");
        $stmt->execute([$commande['quantite'], $commande['voiture_id']]);

        // Changer le statut de la commande
        $stmt = $conn->prepare("UPDATE commandes SET statut = 'annulée' WHERE id = ?");
        $stmt->execute([$commande_id]);

        $success = "Commande annulée avec succès. La quantité a été restituée.";
    }
}

// Récupérer toutes les commandes de l'utilisateur
$stmt = $conn->prepare("SELECT c.*, v.marque, v.modele, v.image 
                       FROM commandes c 
                       JOIN voitures v ON c.voiture_id = v.id 
                       WHERE c.user_id = ? 
                       ORDER BY c.date_commande DESC");
$stmt->execute([$user_id]);
$commandes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes Commandes - AutoLux</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
</head>
<body class="bg-slate-950 text-white">

    <!-- Navbar -->
    <nav class="fixed top-0 left-0 right-0 z-50 bg-black/70 backdrop-blur-md border-b border-slate-800">
        <div class="max-w-6xl mx-auto px-6 py-5 flex justify-between items-center">
            <div class="flex items-center gap-3">
                <i class="fa-solid fa-car text-yellow-400 text-3xl"></i>
                <span class="text-2xl font-bold">Auto<span class="text-yellow-400">Lux</span></span>
            </div>
            <div class="flex gap-8 text-sm uppercase tracking-widest">
                <a href="home.php" class="hover:text-yellow-400">Accueil</a>
                <a href="products.php" class="hover:text-yellow-400">Véhicules</a>
                <a href="consultercommande.php" class="text-yellow-400 font-semibold">Mes Commandes</a>
                <a href="profil.php" class="hover:text-yellow-400">Profil</a>
            </div>
            <a href="logout.php" class="text-red-400 hover:text-red-300">Déconnexion</a>
        </div>
    </nav>

    <div class="pt-28 max-w-6xl mx-auto px-6 py-12">
        <h1 class="text-5xl font-bold text-yellow-400 mb-10">Mes Commandes</h1>

        <?php if($success): ?>
            <div class="bg-green-600 text-white p-5 rounded-2xl mb-8">
                <?= $success ?>
            </div>
        <?php endif; ?>

        <?php if(empty($commandes)): ?>
            <p class="text-slate-400 text-xl">Vous n'avez pas encore passé de commande.</p>
        <?php else: ?>
            <div class="space-y-8">
                <?php foreach($commandes as $c): ?>
                    <div class="bg-slate-900 rounded-3xl p-8 flex gap-8 items-center border border-slate-700">
                        <img src="../images/<?= htmlspecialchars($c['image']) ?>" 
                             class="w-40 h-32 object-cover rounded-2xl" alt="">
                        
                        <div class="flex-1">
                            <h3 class="text-2xl font-semibold">
                                <?= htmlspecialchars($c['marque']) ?> <?= htmlspecialchars($c['modele']) ?>
                            </h3>
                            <p class="text-slate-400">
                                Commande #<?= $c['id'] ?> • <?= date('d/m/Y à H:i', strtotime($c['date_commande'])) ?>
                            </p>
                            <p class="text-3xl font-bold text-yellow-400 mt-2">
                                <?= number_format($c['prix_total'], 0, ',', ' ') ?> DT
                            </p>
                            <p class="text-sm mt-1">
                                Quantité : <strong><?= $c['quantite'] ?></strong> | 
                                Statut : <span class="font-medium <?= $c['statut'] == 'annulée' ? 'text-red-400' : 'text-green-400' ?>">
                                    <?= ucfirst($c['statut']) ?>
                                </span>
                            </p>
                        </div>

                        <?php if($c['statut'] == 'en_attente'): ?>
                            <a href="consultercommande.php?annuler=<?= $c['id'] ?>" 
                               onclick="return confirm('Voulez-vous vraiment annuler cette commande ?')"
                               class="px-8 py-4 bg-red-600 hover:bg-red-700 rounded-2xl font-medium transition">
                                Annuler
                            </a>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
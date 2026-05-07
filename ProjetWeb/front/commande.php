<?php
session_start();
require_once('../config/db.php');
require_once('../classes/Voiture.class.php');

if (!isset($_SESSION['user_id']) || !isset($_GET['voiture_id'])) {
    header("Location: products.php");
    exit();
}

$voiture_id = (int)$_GET['voiture_id'];

// Récupérer les infos de la voiture
$stmt = $conn->prepare("SELECT * FROM voitures WHERE id = ?");
$stmt->execute([$voiture_id]);
$voiture = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$voiture) {
    die("Voiture non trouvée");
}

if ($voiture['quantite'] <= 0) {
    die("Désolé, cette voiture n'est plus en stock.");
}

// Traitement de la commande
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $quantite_commande = (int)$_POST['quantite'];
    
    if ($quantite_commande > $voiture['quantite']) {
        $error = "Quantité demandée non disponible en stock.";
    } else {
        $prix_total = $voiture['prix'] * $quantite_commande;
        $user_id = $_SESSION['user_id'];

        $stmt = $conn->prepare("INSERT INTO commandes (user_id, voiture_id, quantite, prix_total) 
                               VALUES (?, ?, ?, ?)");
        $stmt->execute([$user_id, $voiture_id, $quantite_commande, $prix_total]);

        $new_quantite = $voiture['quantite'] - $quantite_commande;
        $stmt = $conn->prepare("UPDATE voitures SET quantite = ? WHERE id = ?");
        $stmt->execute([$new_quantite, $voiture_id]);

        $success = true;
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Commander - <?= htmlspecialchars($voiture['marque'] . ' ' . $voiture['modele']) ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Playfair+Display:wght@700&display=swap');
    
    .hero-bg {
        background: linear-gradient(rgba(0,0,0,0.85), rgba(0,0,0,0.95)), 
                    url('https://images.unsplash.com/photo-1492144534652-8f4f0a3f6f3d?q=80&w=2070') center/cover fixed;
    }
    
    .glass {
        background: rgba(15, 23, 42, 0.78);
        backdrop-filter: blur(24px);
        border: 1px solid rgba(234, 179, 8, 0.25);
    }
    </style>
</head>
<body class="hero-bg min-h-screen flex items-center justify-center py-12">

    <div class="max-w-4xl w-full mx-6">
        <!-- Header -->
        <div class="text-center mb-12">
            <div class="inline-flex items-center gap-3 mb-4">
                <i class="fa-solid fa-car text-5xl text-yellow-400"></i>
                <span class="text-4xl font-bold tracking-tighter">Auto<span class="text-yellow-400">Lux</span></span>
            </div>
            <h1 class="text-5xl font-bold text-white">Finaliser votre commande</h1>
        </div>

        <div class="glass rounded-3xl overflow-hidden">
            <?php if(isset($success)): ?>
                <div class="p-16 text-center">
                    <div class="mx-auto w-24 h-24 bg-green-500/10 rounded-full flex items-center justify-center mb-8">
                        <i class="fa-solid fa-check text-6xl text-green-400"></i>
                    </div>
                    <h2 class="text-4xl font-bold text-white mb-4">Commande Confirmée !</h2>
                    <p class="text-xl text-slate-300 mb-10">Merci pour votre confiance.</p>
                    <a href="products.php" 
                       class="inline-block bg-yellow-400 text-slate-950 font-bold px-12 py-6 rounded-2xl text-xl hover:bg-amber-300 transition">
                        Retour au Catalogue
                    </a>
                </div>
            <?php else: ?>

            <div class="grid md:grid-cols-2">
                <!-- Left: Car Info -->
                <div class="bg-slate-900 p-12">
                    <img src="../images/<?= htmlspecialchars($voiture['image']) ?>" 
                         class="w-full rounded-2xl shadow-2xl" alt="">
                    
                    <div class="mt-8">
                        <h2 class="text-3xl font-semibold">
                            <?= htmlspecialchars($voiture['marque']) ?> 
                            <span class="text-slate-400"><?= htmlspecialchars($voiture['modele']) ?></span>
                        </h2>
                        <p class="text-5xl font-bold text-yellow-400 mt-6">
                            <?= number_format($voiture['prix'], 0, ',', ' ') ?> <span class="text-2xl">DT</span>
                        </p>
                        <p class="text-slate-400 mt-3">
                            Stock disponible : <span class="text-green-400 font-medium"><?= $voiture['quantite'] ?></span>
                        </p>
                    </div>
                </div>

                <!-- Right: Order Form -->
                <div class="p-12">
                    <h3 class="text-2xl font-semibold mb-8">Détails de la commande</h3>
                    
                    <form method="POST" class="space-y-8">
                        <div>
                            <label class="block text-slate-400 mb-3 text-lg">Quantité souhaitée</label>
                            <input type="number" name="quantite" min="1" max="<?= $voiture['quantite'] ?>" value="1" required
                                   class="w-full bg-slate-900 border border-slate-600 focus:border-yellow-400 rounded-2xl py-6 px-8 text-3xl text-center focus:outline-none transition">
                        </div>

                        <?php if(isset($error)): ?>
                            <div class="bg-red-500/10 border border-red-500 text-red-400 p-4 rounded-2xl text-center">
                                <?= htmlspecialchars($error) ?>
                            </div>
                        <?php endif; ?>

                        <button type="submit" 
                                class="w-full bg-gradient-to-r from-yellow-400 to-amber-300 text-slate-950 font-bold py-7 rounded-2xl text-2xl hover:scale-105 active:scale-95 transition-all duration-300 shadow-xl shadow-yellow-400/30">
                            Confirmer ma commande
                        </button>
                    </form>

                    <p class="text-center text-slate-400 text-sm mt-8">
                        Vous serez contacté pour les détails de paiement et livraison.
                    </p>
                </div>
            </div>

            <?php endif; ?>
        </div>

        <div class="text-center mt-8">
            <a href="products.php" class="text-slate-400 hover:text-white transition flex items-center justify-center gap-2">
                ← Retour au catalogue
            </a>
        </div>
    </div>

</body>
</html>
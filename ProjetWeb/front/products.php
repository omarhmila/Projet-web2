<?php
session_start();
require_once('../config/db.php');
require_once('../classes/Voiture.class.php');

if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catalogue • AutoLux</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Playfair+Display:wght@700&display=swap');
    
    .hero-bg {
        background: linear-gradient(rgba(0,0,0,0.75), rgba(0,0,0,0.85)), 
                    url('https://images.unsplash.com/photo-1492144534652-8f4f0a3f6f3d?q=80&w=2070') center/cover fixed;
    }
    
    .glass {
        background: rgba(15, 23, 42, 0.75);
        backdrop-filter: blur(16px);
        border: 1px solid rgba(234, 179, 8, 0.15);
    }

    .car-card {
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        overflow: hidden;
    }
    
    .car-card:hover {
        transform: translateY(-12px);
        box-shadow: 0 25px 50px -12px rgba(234, 179, 8, 0.3);
    }

    .car-image {
        transition: transform 0.7s ease;
    }
    
    .car-card:hover .car-image {
        transform: scale(1.1);
    }
    </style>
</head>
<body class="bg-slate-950 text-white min-h-screen">

    <!-- Navbar -->
    <nav class="fixed top-0 left-0 right-0 z-50 bg-black/70 backdrop-blur-md border-b border-slate-800">
        <div class="max-w-6xl mx-auto px-6 py-5 flex justify-between items-center">
            <div class="flex items-center gap-3">
                <i class="fa-solid fa-car text-yellow-400 text-3xl"></i>
                <span class="text-2xl font-bold tracking-tighter">Auto<span class="text-yellow-400">Lux</span></span>
            </div>
            
            <div class="flex items-center gap-8 text-sm uppercase tracking-widest">
                <a href="home.php" class="hover:text-yellow-400 transition">Accueil</a>
                <a href="products.php" class="text-yellow-400 font-semibold">Véhicules</a>
                <a href="profil.php" class="hover:text-yellow-400 transition">Profil</a>
                <a href="consultercommande.php" class="hover:text-yellow-400">Mes Commandes</a>
                <a href="#" class="hover:text-yellow-400 transition">Contact</a>
            </div>

            <div class="flex items-center gap-4">
                <span class="text-sm text-slate-400">Bonjour, <span class="text-white font-medium"><?= htmlspecialchars($_SESSION['user']) ?></span></span>
                <a href="../logout.php" 
                   class="text-sm px-5 py-2.5 rounded-full border border-slate-700 hover:border-red-500 hover:text-red-400 transition">
                    Déconnexion
                </a>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="hero-bg h-[70vh] flex items-center justify-center text-center relative">
        <div class="max-w-4xl mx-auto px-6 pt-20">
            <div class="inline-flex items-center gap-2 bg-yellow-400/10 text-yellow-400 text-sm font-medium px-6 py-3 rounded-3xl mb-6 border border-yellow-400/20">
                <i class="fa-solid fa-crown"></i>
                <span>NOTRE COLLECTION EXCLUSIVE</span>
            </div>
            
            <h1 class="text-6xl md:text-7xl font-bold tracking-tighter leading-none mb-6">
                Découvrez nos <span class="text-yellow-400">véhicules d'exception</span>
            </h1>
            
            <p class="text-xl text-slate-300 max-w-xl mx-auto">
                Sélectionnée avec soin pour les amateurs de luxe et de performance
            </p>
        </div>

        <div class="absolute bottom-10 left-1/2 transform -translate-x-1/2 flex gap-8 text-5xl opacity-10">
            <i class="fa-solid fa-car"></i>
            <i class="fa-solid fa-car-side"></i>
            <i class="fa-solid fa-car"></i>
        </div>
    </div>

    <!-- Catalogue -->
    <div class="max-w-7xl mx-auto px-6 py-20">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php
            $voitures = Voiture::getAll();
            foreach($voitures as $v):
                $quantite = $v['quantite'] ?? 0;
                $en_stock = $quantite > 0;
            ?>
                <div class="car-card bg-slate-900 rounded-3xl overflow-hidden border border-slate-700 group">
                    <!-- Image -->
                    <div class="relative h-72 overflow-hidden">
                        <img src="../images/<?= htmlspecialchars($v['image']) ?>" 
                             alt="<?= htmlspecialchars($v['marque'] . ' ' . $v['modele']) ?>" 
                             class="car-image w-full h-full object-cover">
                        
                        <div class="absolute top-5 right-5 flex flex-col gap-2">
                            <div class="bg-black/80 text-yellow-400 text-xs font-medium px-4 py-2 rounded-full">
                                <?= $v['annee'] ?>
                            </div>
                            <?php if($en_stock): ?>
                                <div class="bg-green-600/90 text-white text-xs font-medium px-4 py-1.5 rounded-full text-center">
                                    <?= $quantite ?> en stock
                                </div>
                            <?php else: ?>
                                <div class="bg-red-600/90 text-white text-xs font-medium px-4 py-1.5 rounded-full text-center">
                                    Épuisé
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Content -->
                    <div class="p-8">
                        <h3 class="text-2xl font-semibold">
                            <?= htmlspecialchars($v['marque']) ?> 
                            <span class="text-slate-400"><?= htmlspecialchars($v['modele']) ?></span>
                        </h3>
                        
                        <div class="mt-4 flex items-baseline gap-1">
                            <span class="text-4xl font-bold text-yellow-400">
                                <?= number_format($v['prix'], 0, ',', ' ') ?>
                            </span>
                            <span class="text-slate-400 text-lg">DT</span>
                        </div>

                        <div class="flex gap-8 mt-6 text-sm text-slate-400">
                            <div class="flex items-center gap-2">
                                <i class="fa-solid fa-gas-pump"></i>
                                <span>Essence</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <i class="fa-solid fa-tachometer-alt"></i>
                                <span>Automatique</span>
                            </div>
                        </div>

                        <a href="commande.php?voiture_id=<?= $v['id'] ?>" 
                           class="mt-8 block w-full text-center <?= $en_stock ? 'bg-yellow-400 hover:bg-amber-300' : 'bg-slate-700 cursor-not-allowed' ?> 
                                  text-slate-950 font-bold py-5 rounded-2xl transition-all duration-300 group-hover:scale-105">
                            <?= $en_stock ? 'Acheter ce véhicule' : 'Indisponible' ?>
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

</body>
</html>
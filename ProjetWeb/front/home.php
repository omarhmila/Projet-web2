<?php
session_start();
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
    <title>Accueil - AutoLux</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Playfair+Display:wght@700&display=swap');
    
    .hero-bg {
        background: linear-gradient(rgba(0, 0, 0, 0.75), rgba(0, 0, 0, 0.85)), 
                    url('https://images.unsplash.com/photo-1492144534652-8f4f0a3f6f3d?q=80&w=2070') center/cover no-repeat;
    }
    
    .car-glow {
        filter: drop-shadow(0 0 60px rgb(250 204 21 / 0.3));
    }
    </style>
</head>
<body class="bg-slate-950 text-white min-h-screen overflow-hidden">
    <!-- Navbar -->
    <nav class="fixed top-0 left-0 right-0 z-50 bg-black/70 backdrop-blur-md border-b border-slate-800">
        <div class="max-w-6xl mx-auto px-6 py-5 flex justify-between items-center">
            <div class="flex items-center gap-3">
                <i class="fa-solid fa-car text-yellow-400 text-3xl"></i>
                <span class="text-2xl font-bold tracking-tighter">Auto<span class="text-yellow-400">Lux</span></span>
            </div>
            <div class="flex items-center gap-8 text-sm uppercase tracking-widest">
                <a href="#" class="hover:text-yellow-400 transition">Accueil</a>
                <a href="products.php" class="hover:text-yellow-400 transition">Véhicules</a>
                
                 <a href="profil.php" class="hover:text-yellow-400 transition">Profil</a>
                 <a href="consultercommande.php" class="hover:text-yellow-400">Mes Commandes</a>
                <a href="#" class="hover:text-yellow-400 transition">Contact</a>
            </div>
            <div class="flex items-center gap-4">
                <span class="text-sm text-slate-400">Bonjour, <span class="text-white font-medium"><?= htmlspecialchars($_SESSION['user']) ?></span></span>
                <a href="../logout.php" class="text-sm px-5 py-2.5 rounded-full border border-slate-700 hover:border-red-500 hover:text-red-400 transition">
                    Déconnexion
                </a>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="hero-bg min-h-screen flex items-center relative">
        <div class="max-w-6xl mx-auto px-6 pt-24 text-center relative z-10">
            <div class="inline-flex items-center gap-2 bg-yellow-400/10 text-yellow-400 text-sm font-medium px-6 py-3 rounded-3xl mb-6 border border-yellow-400/20">
                <i class="fa-solid fa-crown"></i>
                <span>LUXE AUTOMOBILE</span>
            </div>
            
            <h1 class="text-7xl md:text-8xl font-bold mb-6 tracking-tighter leading-none">
                Bienvenue chez <span class="text-yellow-400">AutoLux</span>
            </h1>
            
            <p class="text-3xl text-slate-300 mb-4 font-light">
                Bonjour <span class="text-white font-medium"><?= htmlspecialchars($_SESSION['user']) ?></span> !
            </p>
            
            <p class="max-w-lg mx-auto text-xl text-slate-400 mb-16">
                Découvrez l'excellence automobile. Des véhicules d'exception pour des passionnés d'exception.
            </p>

            <!-- CTA Button -->
            <a href="products.php" 
               class="group inline-flex items-center gap-4 bg-yellow-400 hover:bg-amber-300 text-slate-950 font-bold text-2xl px-14 py-7 rounded-3xl transition-all duration-300 hover:scale-105 active:scale-95 shadow-2xl shadow-yellow-400/30">
                <span>Voir le Catalogue</span>
                <i class="fa-solid fa-arrow-right text-xl group-active:rotate-45 transition"></i>
            </a>

            <!-- Trust badges -->
            <div class="flex justify-center gap-10 mt-20 text-sm opacity-75">
                <div class="flex items-center gap-3">
                    <i class="fa-solid fa-shield-halved text-2xl"></i>
                    <div>
                        <div class="font-medium">Garantie Premium</div>
                        <div class="text-xs text-slate-400">2 ans inclus</div>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <i class="fa-solid fa-truck text-2xl"></i>
                    <div>
                        <div class="font-medium">Livraison Express</div>
                        <div class="text-xs text-slate-400">Partout en Tunisie</div>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <i class="fa-solid fa-star text-2xl"></i>
                    <div>
                        <div class="font-medium">Service 5 Étoiles</div>
                        <div class="text-xs text-slate-400">Satisfaction garantie</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Decorative elements -->
        <div class="absolute bottom-12 left-1/2 transform -translate-x-1/2 flex gap-8 text-4xl opacity-10">
            <i class="fa-solid fa-car"></i>
            <i class="fa-solid fa-car-side"></i>
            <i class="fa-solid fa-car"></i>
        </div>
    </div>

    <script>
        // Tailwind script already included
    </script>
</body>
</html>
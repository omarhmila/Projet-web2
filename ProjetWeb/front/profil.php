<?php
session_start();
require_once('../config/db.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$success = "";
$error = "";

// Récupérer les informations actuelles de l'utilisateur
$stmt = $conn->prepare("SELECT nom, prenom, email, telephone, date_naissance, genre 
                       FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $nom           = trim($_POST['nom']);
    $prenom        = trim($_POST['prenom']);
    $email         = trim($_POST['email']);
    $telephone     = trim($_POST['telephone']);
    $date_naissance= $_POST['date_naissance'];
    $genre         = $_POST['genre'];

    try {
        $stmt = $conn->prepare("UPDATE users 
                               SET nom = ?, prenom = ?, email = ?, 
                                   telephone = ?, date_naissance = ?, genre = ? 
                               WHERE id = ?");
        
        $stmt->execute([$nom, $prenom, $email, $telephone, $date_naissance, $genre, $user_id]);

        $success = "Profil mis à jour avec succès !";
        
        // Mise à jour du nom affiché dans la session
        $_SESSION['user'] = $nom . " " . $prenom;

        // Recharger les données
        $stmt = $conn->prepare("SELECT nom, prenom, email, telephone, date_naissance, genre 
                               FROM users WHERE id = ?");
        $stmt->execute([$user_id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

    } catch (PDOException $e) {
        $error = "Erreur lors de la mise à jour : " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Profil - AutoLux</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Playfair+Display:wght@700&display=swap');
        
        .hero-bg {
            background: linear-gradient(rgba(0, 0, 0, 0.85), rgba(0, 0, 0, 0.9)), 
                      url('https://images.unsplash.com/photo-1492144534652-8f4f0a3f6f3d?q=80&w=2070') center/cover no-repeat;
        }
        .card-lux {
            background: rgba(15, 23, 42, 0.95);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(234, 179, 8, 0.2);
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
                <a href="products.php" class="hover:text-yellow-400 transition">Véhicules</a>
                <a href="profil.php" class="text-yellow-400 font-medium">Mon Profil</a>
                <a href="consultercommande.php" class="hover:text-yellow-400">Mes Commandes</a>
            </div>
            <div class="flex items-center gap-4">
                <span class="text-sm text-slate-400">
                    Bonjour, <span class="text-white"><?= htmlspecialchars($_SESSION['user']) ?></span>
                </span>
                <a href="../logout.php" class="text-sm px-5 py-2.5 rounded-full border border-slate-700 hover:border-red-500 hover:text-red-400 transition">
                    Déconnexion
                </a>
            </div>
        </div>
    </nav>

    <div class="pt-28 pb-16 min-h-screen hero-bg">
        <div class="max-w-2xl mx-auto px-6">
            <div class="text-center mb-12">
                <i class="fa-solid fa-user-circle text-7xl text-yellow-400 mb-6"></i>
                <h1 class="text-5xl font-bold tracking-tighter">Mon Profil</h1>
                <p class="text-slate-400 mt-3">Mettez à jour vos informations personnelles</p>
            </div>

            <div class="card-lux rounded-3xl p-10 shadow-2xl">
                <?php if($success): ?>
                    <div class="bg-green-900/50 border border-green-500 text-green-300 p-4 rounded-2xl mb-8 text-center">
                        <?= $success ?>
                    </div>
                <?php endif; ?>

                <?php if($error): ?>
                    <div class="bg-red-900/50 border border-red-500 text-red-300 p-4 rounded-2xl mb-8 text-center">
                        <?= $error ?>
                    </div>
                <?php endif; ?>

                <form method="POST" class="space-y-6">
                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <label class="block text-yellow-400 text-sm mb-2 font-medium">Prénom</label>
                            <input type="text" name="prenom" value="<?= htmlspecialchars($user['prenom'] ?? '') ?>" required
                                   class="w-full bg-slate-900 border border-slate-700 focus:border-yellow-400 rounded-2xl px-6 py-5 outline-none">
                        </div>
                        <div>
                            <label class="block text-yellow-400 text-sm mb-2 font-medium">Nom</label>
                            <input type="text" name="nom" value="<?= htmlspecialchars($user['nom'] ?? '') ?>" required
                                   class="w-full bg-slate-900 border border-slate-700 focus:border-yellow-400 rounded-2xl px-6 py-5 outline-none">
                        </div>
                    </div>

                    <div>
                        <label class="block text-yellow-400 text-sm mb-2 font-medium">Email</label>
                        <input type="email" name="email" value="<?= htmlspecialchars($user['email'] ?? '') ?>" required
                               class="w-full bg-slate-900 border border-slate-700 focus:border-yellow-400 rounded-2xl px-6 py-5 outline-none">
                    </div>

                    <div>
                        <label class="block text-yellow-400 text-sm mb-2 font-medium">Téléphone</label>
                        <input type="tel" name="telephone" value="<?= htmlspecialchars($user['telephone'] ?? '') ?>" 
                               class="w-full bg-slate-900 border border-slate-700 focus:border-yellow-400 rounded-2xl px-6 py-5 outline-none">
                    </div>

                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <label class="block text-yellow-400 text-sm mb-2 font-medium">Date de naissance</label>
                            <input type="date" name="date_naissance" value="<?= htmlspecialchars($user['date_naissance'] ?? '') ?>"
                                   class="w-full bg-slate-900 border border-slate-700 focus:border-yellow-400 rounded-2xl px-6 py-5 outline-none">
                        </div>
                        <div>
                            <label class="block text-yellow-400 text-sm mb-2 font-medium">Genre</label>
                            <select name="genre" 
                                    class="w-full bg-slate-900 border border-slate-700 focus:border-yellow-400 rounded-2xl px-6 py-5 outline-none">
                                <option value="Homme" <?= ($user['genre'] ?? '') == 'Homme' ? 'selected' : '' ?>>Homme</option>
                                <option value="Femme" <?= ($user['genre'] ?? '') == 'Femme' ? 'selected' : '' ?>>Femme</option>
                                <option value="Autre" <?= ($user['genre'] ?? '') == 'Autre' ? 'selected' : '' ?>>Autre</option>
                            </select>
                        </div>
                    </div>

                    <button type="submit"
                            class="w-full bg-yellow-400 hover:bg-amber-300 text-slate-950 font-bold py-6 rounded-3xl text-xl transition-all duration-300 hover:scale-[1.02]">
                        Enregistrer les modifications
                    </button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
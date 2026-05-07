<?php
session_start();
require_once('../config/db.php');

if (isset($_SESSION['user'])) {
    header("Location: home.php");
    exit();
}

$success = $error = "";

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $nom = trim($_POST['nom']);
    $prenom = trim($_POST['prenom']);
    $email = trim($_POST['email']);
    $telephone = trim($_POST['telephone']);
    $date_naissance = $_POST['date_naissance'];
    $genre = $_POST['genre'];
    $pass = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);

    if ($stmt->rowCount() > 0) {
        $error = "Cet email existe déjà !";
    } else {
        $stmt = $conn->prepare("INSERT INTO users (nom, prenom, email, telephone, date_naissance, genre, password) 
                                VALUES (?, ?, ?, ?, ?, ?, ?)");
        
        if ($stmt->execute([$nom, $prenom, $email, $telephone, $date_naissance, $genre, $pass])) {
            $success = "Compte créé avec succès ! Vous pouvez maintenant vous connecter.";
        } else {
            $error = "Erreur lors de la création du compte.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription - AutoLux</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Playfair+Display:wght@700&display=swap');
    
    .hero-bg {
        background: linear-gradient(135deg, rgba(0,0,0,0.85), rgba(0,0,0,0.95)),
                    url('https://images.unsplash.com/photo-1492144534652-8f4f0a3f6f3d?q=80&w=2070') center/cover fixed;
    }
    
    .glass {
        background: rgba(15, 23, 42, 0.75);
        backdrop-filter: blur(24px);
        border: 1px solid rgba(234, 179, 8, 0.25);
        box-shadow: 0 25px 50px -12px rgb(0 0 0 / 0.5);
    }

    .floating-label {
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    input:focus ~ label,
    input:not(:placeholder-shown) ~ label,
    select:focus ~ label {
        transform: translateY(-28px) scale(0.85);
        color: #fbbf24;
        background: rgba(15, 23, 42, 0.95);
        padding: 0 8px;
    }
    
    .luxury-glow {
        box-shadow: 0 0 35px rgba(234, 179, 8, 0.3);
    }
    </style>
</head>
<body class="hero-bg min-h-screen flex items-center justify-center">
    <div class="max-w-lg w-full mx-6">
        <!-- Brand Header -->
        <div class="text-center mb-12">
            <div class="flex justify-center items-center gap-4 mb-5">
                <div class="w-16 h-16 bg-yellow-400 rounded-2xl flex items-center justify-center">
                    <i class="fa-solid fa-car text-4xl text-slate-950"></i>
                </div>
                <span class="text-6xl font-bold tracking-tighter text-white">Auto<span class="text-yellow-400">Lux</span></span>
            </div>
            <p class="text-xl text-slate-300 tracking-widest">REJOIGNEZ L'EXCELLENCE</p>
        </div>

        <!-- Main Card -->
        <div class="glass rounded-3xl p-12 luxury-glow">
            <h2 class="text-4xl font-semibold text-white text-center mb-10">Créer un compte</h2>

            <?php if($success): ?>
                <div class="mb-8 bg-green-900/30 border border-green-400 text-green-400 text-center py-4 px-6 rounded-2xl">
                    <?= htmlspecialchars($success) ?>
                </div>
            <?php endif; ?>

            <?php if($error): ?>
                <div class="mb-8 bg-red-900/30 border border-red-400 text-red-400 text-center py-4 px-6 rounded-2xl">
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <form method="POST" class="space-y-8">
                <div class="grid grid-cols-2 gap-6">
                    <!-- Prénom -->
                    <div class="relative">
                        <i class="fa-solid fa-user absolute left-5 top-5 text-slate-400"></i>
                        <input type="text" name="prenom" id="prenom" required placeholder=" "
                               class="w-full bg-slate-900/80 border border-slate-600 focus:border-yellow-400 rounded-2xl py-5 pl-14 pr-5 text-lg text-white outline-none transition-all">
                        <label for="prenom" class="floating-label absolute left-14 top-5 text-slate-400 pointer-events-none">
                            Prénom
                        </label>
                    </div>

                    <!-- Nom -->
                    <div class="relative">
                        <i class="fa-solid fa-user absolute left-5 top-5 text-slate-400"></i>
                        <input type="text" name="nom" id="nom" required placeholder=" "
                               class="w-full bg-slate-900/80 border border-slate-600 focus:border-yellow-400 rounded-2xl py-5 pl-14 pr-5 text-lg text-white outline-none transition-all">
                        <label for="nom" class="floating-label absolute left-14 top-5 text-slate-400 pointer-events-none">
                            Nom
                        </label>
                    </div>
                </div>

                <!-- Email -->
                <div class="relative">
                    <i class="fa-solid fa-envelope absolute left-5 top-5 text-slate-400"></i>
                    <input type="email" name="email" id="email" required placeholder=" "
                           class="w-full bg-slate-900/80 border border-slate-600 focus:border-yellow-400 rounded-2xl py-5 pl-14 pr-5 text-lg text-white outline-none transition-all">
                    <label for="email" class="floating-label absolute left-14 top-5 text-slate-400 pointer-events-none">
                        Adresse email
                    </label>
                </div>

                <!-- Téléphone -->
                <div class="relative">
                    <i class="fa-solid fa-phone absolute left-5 top-5 text-slate-400"></i>
                    <input type="tel" name="telephone" id="telephone" required placeholder=" "
                           class="w-full bg-slate-900/80 border border-slate-600 focus:border-yellow-400 rounded-2xl py-5 pl-14 pr-5 text-lg text-white outline-none transition-all">
                    <label for="telephone" class="floating-label absolute left-14 top-5 text-slate-400 pointer-events-none">
                        Numéro de téléphone
                    </label>
                </div>

                <div class="grid grid-cols-2 gap-6">
                    <!-- Date de naissance -->
                    <div class="relative">
                        <i class="fa-solid fa-calendar absolute left-5 top-5 text-slate-400"></i>
                        <input type="date" name="date_naissance" id="date_naissance" required
                               class="w-full bg-slate-900/80 border border-slate-600 focus:border-yellow-400 rounded-2xl py-5 pl-14 pr-5 text-lg text-white outline-none transition-all">
                        <label for="date_naissance" class="floating-label absolute left-14 top-5 text-slate-400 pointer-events-none">
                           
                        </label>
                    </div>

                    <!-- Genre -->
                    <div class="relative">
                        <i class="fa-solid fa-venus-mars absolute left-5 top-5 text-slate-400"></i>
                        <select name="genre" id="genre" required
                                class="w-full bg-slate-900/80 border border-slate-600 focus:border-yellow-400 rounded-2xl py-5 pl-14 pr-5 text-lg text-white outline-none transition-all">
                            <option value="" disabled selected></option>
                            <option value="Homme">Homme</option>
                            <option value="Femme">Femme</option>
                            <option value="Autre">Autre</option>
                        </select>
                        <label for="genre" class="floating-label absolute left-14 top-5 text-slate-400 pointer-events-none">
                            Genre
                        </label>
                    </div>
                </div>

                <!-- Mot de passe -->
                <div class="relative">
                    <i class="fa-solid fa-lock absolute left-5 top-5 text-slate-400"></i>
                    <input type="password" name="password" id="password" required placeholder=" "
                           class="w-full bg-slate-900/80 border border-slate-600 focus:border-yellow-400 rounded-2xl py-5 pl-14 pr-12 text-lg text-white outline-none transition-all">
                    <label for="password" class="floating-label absolute left-14 top-5 text-slate-400 pointer-events-none">
                        Mot de passe
                    </label>
                    <button type="button" onclick="togglePassword()" 
                            class="absolute right-6 top-5 text-slate-400 hover:text-yellow-400 transition">
                        <i class="fa-solid fa-eye text-xl" id="eyeIcon"></i>
                    </button>
                </div>

                <button type="submit"
                        class="w-full mt-6 bg-gradient-to-r from-yellow-400 to-amber-300 hover:from-yellow-300 hover:to-amber-200 text-slate-950 font-bold py-6 rounded-2xl text-xl tracking-wider transition-all duration-300 hover:scale-[1.03] active:scale-95 shadow-2xl shadow-yellow-400/50">
                    CRÉER MON COMPTE
                </button>
            </form>

            <div class="mt-10 text-center">
                <p class="text-slate-400">
                    Déjà un compte ? 
                    <a href="index.php" class="text-yellow-400 hover:text-amber-300 font-semibold transition">
                        Se connecter
                    </a>
                </p>
            </div>
        </div>

        <p class="text-center text-slate-500 text-sm mt-8">
            © 2026 AutoLux • Concessionnaire Premium
        </p>
    </div>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eyeIcon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                eyeIcon.classList.replace('fa-eye-slash', 'fa-eye');
            }
        }

        // Auto focus
        document.getElementById('prenom').focus();
    </script>
</body>
</html>
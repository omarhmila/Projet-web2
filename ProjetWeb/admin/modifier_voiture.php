<?php
session_start();
require_once('../config/db.php');

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../front/index.php");
    exit();
}

$id = (int)$_GET['id'];

$stmt = $conn->prepare("SELECT * FROM voitures WHERE id = ?");
$stmt->execute([$id]);
$voiture = $stmt->fetch();

if (!$voiture) {
    die("Voiture non trouvée");
}

$success = $error = "";

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $marque    = trim($_POST['marque']);
    $modele    = trim($_POST['modele']);
    $prix      = (float)$_POST['prix'];
    $annee     = (int)$_POST['annee'];
    $quantite  = (int)$_POST['quantite'];
    $image_name = $voiture['image']; // Par défaut garder l'ancienne image

    // Gestion de l'upload d'une nouvelle image
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $file = $_FILES['image'];
        $allowed = ['jpg', 'jpeg', 'png', 'webp'];
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

        if (in_array($ext, $allowed)) {
            $new_name = time() . '_' . $file['name'];
            $destination = "../images/" . $new_name;

            if (move_uploaded_file($file['tmp_name'], $destination)) {
                $image_name = $new_name;
            } else {
                $error = "Erreur lors de l'upload de la nouvelle image.";
            }
        } else {
            $error = "Format d'image non autorisé.";
        }
    }

    if (empty($error)) {
        $stmt = $conn->prepare("UPDATE voitures 
                               SET marque=?, modele=?, prix=?, annee=?, quantite=?, image=? 
                               WHERE id=?");
        
        if ($stmt->execute([$marque, $modele, $prix, $annee, $quantite, $image_name, $id])) {
            $success = "Voiture modifiée avec succès !";
        } else {
            $error = "Erreur lors de la mise à jour.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier Voiture</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-950 text-white">

<div class="max-w-2xl mx-auto mt-12 p-10 bg-slate-900 rounded-3xl">
    <h1 class="text-4xl font-bold text-yellow-400 mb-8">Modifier la voiture</h1>

    <?php if($success): ?>
        <div class="bg-green-600 p-4 rounded-2xl mb-6"><?= $success ?></div>
    <?php endif; ?>

    <?php if($error): ?>
        <div class="bg-red-600 p-4 rounded-2xl mb-6"><?= $error ?></div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data" class="space-y-6">
        <div class="grid grid-cols-2 gap-6">
            <div>
                <label class="block mb-2 text-slate-400">Marque</label>
                <input type="text" name="marque" value="<?= htmlspecialchars($voiture['marque']) ?>" required 
                       class="w-full p-4 bg-slate-800 rounded-2xl">
            </div>
            <div>
                <label class="block mb-2 text-slate-400">Modèle</label>
                <input type="text" name="modele" value="<?= htmlspecialchars($voiture['modele']) ?>" required 
                       class="w-full p-4 bg-slate-800 rounded-2xl">
            </div>
        </div>

        <div class="grid grid-cols-3 gap-6">
            <div>
                <label class="block mb-2 text-slate-400">Prix (DT)</label>
                <input type="number" name="prix" value="<?= $voiture['prix'] ?>" required 
                       class="w-full p-4 bg-slate-800 rounded-2xl">
            </div>
            <div>
                <label class="block mb-2 text-slate-400">Année</label>
                <input type="number" name="annee" value="<?= $voiture['annee'] ?>" required 
                       class="w-full p-4 bg-slate-800 rounded-2xl">
            </div>
            <div>
                <label class="block mb-2 text-slate-400">Quantité</label>
                <input type="number" name="quantite" value="<?= $voiture['quantite'] ?>" required 
                       class="w-full p-4 bg-slate-800 rounded-2xl">
            </div>
        </div>

        <!-- Image actuelle -->
        <div class="mb-4">
            <p class="text-slate-400 mb-2">Image actuelle :</p>
            <img src="../images/<?= htmlspecialchars($voiture['image']) ?>" 
                 class="w-48 h-32 object-cover rounded-xl border border-slate-600">
        </div>

        <!-- Upload nouvelle image -->
        <div>
            <label class="block mb-2 text-slate-400">Changer l'image (optionnel)</label>
            <input type="file" name="image" accept="image/*" 
                   class="w-full p-4 bg-slate-800 rounded-2xl border border-slate-700">
            <p class="text-xs text-slate-500 mt-1">Laisser vide pour garder l'image actuelle</p>
        </div>

        <button type="submit" class="w-full bg-yellow-400 text-slate-900 font-bold py-5 rounded-2xl text-xl">
            Enregistrer les modifications
        </button>
    </form>

    <a href="dashboard.php" class="block text-center mt-6 text-slate-400 hover:text-white">← Retour au Dashboard</a>
</div>

</body>
</html>
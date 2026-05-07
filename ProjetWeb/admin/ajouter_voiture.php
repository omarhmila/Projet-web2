<?php
session_start();
require_once('../config/db.php');

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../front/index.php");
    exit();
}

$success = $error = "";

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $marque    = trim($_POST['marque']);
    $modele    = trim($_POST['modele']);
    $prix      = (float)$_POST['prix'];
    $annee     = (int)$_POST['annee'];
    $quantite  = (int)$_POST['quantite'];

    // Gestion de l'upload d'image
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $file = $_FILES['image'];
        $allowed = ['jpg', 'jpeg', 'png', 'webp'];
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

        if (in_array($ext, $allowed)) {
            $new_name = time() . '_' . $file['name'];
            $destination = "../images/" . $new_name;

            if (move_uploaded_file($file['tmp_name'], $destination)) {
                $image_name = $new_name;

                // Insertion dans la base
                $stmt = $conn->prepare("INSERT INTO voitures (marque, modele, prix, annee, quantite, image) 
                                       VALUES (?, ?, ?, ?, ?, ?)");
                
                if ($stmt->execute([$marque, $modele, $prix, $annee, $quantite, $image_name])) {
                    $success = "Voiture ajoutée avec succès avec l'image !";
                } else {
                    $error = "Erreur lors de l'enregistrement dans la base.";
                }
            } else {
                $error = "Erreur lors de l'upload de l'image.";
            }
        } else {
            $error = "Seuls les formats jpg, jpeg, png, webp sont autorisés.";
        }
    } else {
        $error = "Veuillez sélectionner une image.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter une Voiture - Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-950 text-white">

<div class="max-w-2xl mx-auto mt-12 p-10 bg-slate-900 rounded-3xl">
    <h1 class="text-4xl font-bold text-yellow-400 mb-8">Ajouter une nouvelle voiture</h1>

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
                <input type="text" name="marque" required class="w-full p-4 bg-slate-800 rounded-2xl">
            </div>
            <div>
                <label class="block mb-2 text-slate-400">Modèle</label>
                <input type="text" name="modele" required class="w-full p-4 bg-slate-800 rounded-2xl">
            </div>
        </div>

        <div class="grid grid-cols-3 gap-6">
            <div>
                <label class="block mb-2 text-slate-400">Prix (DT)</label>
                <input type="number" name="prix" required class="w-full p-4 bg-slate-800 rounded-2xl">
            </div>
            <div>
                <label class="block mb-2 text-slate-400">Année</label>
                <input type="number" name="annee" required class="w-full p-4 bg-slate-800 rounded-2xl">
            </div>
            <div>
                <label class="block mb-2 text-slate-400">Quantité en stock</label>
                <input type="number" name="quantite" value="5" required class="w-full p-4 bg-slate-800 rounded-2xl">
            </div>
        </div>

        <!-- Upload d'image -->
        <div>
            <label class="block mb-2 text-slate-400">Photo de la voiture</label>
            <input type="file" name="image" accept="image/*" required
                   class="w-full p-4 bg-slate-800 rounded-2xl border border-slate-700">
            <p class="text-xs text-slate-500 mt-2">Formats acceptés : jpg, jpeg, png, webp</p>
        </div>

        <button type="submit" class="w-full bg-yellow-400 text-slate-900 font-bold py-5 rounded-2xl text-xl">
            Ajouter la voiture
        </button>
    </form>

    <a href="dashboard.php" class="block text-center mt-6 text-slate-400 hover:text-white">← Retour au Dashboard</a>
</div>

</body>
</html>
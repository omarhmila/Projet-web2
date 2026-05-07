<?php
$host = "localhost";
$dbname = "vente_voiture";
$user = "root";
$pass = "";

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
} catch (PDOException $e) {
    die("Erreur : " . $e->getMessage());
}
?>
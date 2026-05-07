<?php
require_once('../config/db.php');

class Voiture {
    
    public static function getAll() {
        global $conn;
        $stmt = $conn->query("SELECT * FROM voitures ORDER BY marque, modele");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getById($id) {
        global $conn;
        $stmt = $conn->prepare("SELECT * FROM voitures WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>
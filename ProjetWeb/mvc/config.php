<?php
class connexion
{
    public function CNXbase()
    {
        $host = getenv('DB_HOST') ?: 'localhost';
        $db = getenv('DB_NAME') ?: 'vente_voiture';
        $user = getenv('DB_USER') ?: 'root';
        $pass = getenv('DB_PASS') ?: '';

        try {
            $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $pdo;
        } catch (PDOException $e) {
            error_log('DB connection error: ' . $e->getMessage());
            die('Erreur de connexion à la base de données.');
        }
    }
}
?>

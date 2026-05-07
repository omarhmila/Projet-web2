<?php
require_once(__DIR__ . '/config.php');

class utilisateur
{
    public $user_cin;
    public $user_nom;
    private static $tableInitialized = false;

    private function getPdo()
    {
        $cnx = new connexion();
        $pdo = $cnx->CNXbase();

        if (!self::$tableInitialized) {
            $pdo->exec("CREATE TABLE IF NOT EXISTS utilisateur (
                user_cin VARCHAR(20) PRIMARY KEY,
                user_nom VARCHAR(100) NOT NULL
            )");
            self::$tableInitialized = true;
        }

        return $pdo;
    }

    public function insertuser()
    {
        $pdo = $this->getPdo();
        $req = "INSERT INTO utilisateur (user_cin, user_nom) VALUES (:cin, :nom)";
        $stmt = $pdo->prepare($req);
        $stmt->execute([
            ':cin' => $this->user_cin,
            ':nom' => $this->user_nom,
        ]);
    }

    public function listusers()
    {
        $pdo = $this->getPdo();
        return $pdo->query("SELECT user_cin, user_nom FROM utilisateur ORDER BY user_nom ASC");
    }

    public function getuser($id)
    {
        $pdo = $this->getPdo();
        $req = "SELECT user_cin, user_nom FROM utilisateur WHERE user_cin = :cin";
        $stmt = $pdo->prepare($req);
        $stmt->execute([':cin' => $id]);
        return $stmt;
    }

    public function modifier_user($id)
    {
        $pdo = $this->getPdo();
        $req = "UPDATE utilisateur SET user_nom = :nom WHERE user_cin = :cin";
        $stmt = $pdo->prepare($req);
        $stmt->execute([
            ':nom' => $this->user_nom,
            ':cin' => $id,
        ]);
    }

    public function supprimer_user($id)
    {
        $pdo = $this->getPdo();
        $req = "DELETE FROM utilisateur WHERE user_cin = :cin";
        $stmt = $pdo->prepare($req);
        $stmt->execute([':cin' => $id]);
    }

    public function recherche_user()
    {
        $pdo = $this->getPdo();
        $req = "SELECT count(*) FROM utilisateur WHERE user_cin = :cin";
        $stmt = $pdo->prepare($req);
        $stmt->execute([':cin' => $this->user_cin]);
        return $stmt;
    }
}
?>

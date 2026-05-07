<?php
require_once(__DIR__ . '/config.php');

class utilisateur
{
    public $user_id;
    public $user_nom;
    public $user_email;

    public function insertuser()
    {
        $cnx = new connexion();
        $pdo = $cnx->CNXbase();

        $req = "INSERT INTO users (nom, prenom, email, telephone, date_naissance, genre, password, role)
                VALUES (:nom, :prenom, :email, :telephone, :date_naissance, :genre, :password, :role)";
        $stmt = $pdo->prepare($req);
        $stmt->execute([
            ':nom' => $this->user_nom,
            ':prenom' => '',
            ':email' => $this->user_email,
            ':telephone' => '',
            ':date_naissance' => '2000-01-01',
            ':genre' => 'Autre',
            ':password' => password_hash('123456', PASSWORD_DEFAULT),
            ':role' => 'client'
        ]);
    }

    public function listusers()
    {
        $cnx = new connexion();
        $pdo = $cnx->CNXbase();
        $req = "SELECT id, nom, email FROM users ORDER BY id DESC";
        return $pdo->query($req);
    }

    public function getuser($id)
    {
        $cnx = new connexion();
        $pdo = $cnx->CNXbase();
        $req = "SELECT id, nom, email FROM users WHERE id = :id";
        $stmt = $pdo->prepare($req);
        $stmt->execute([':id' => $id]);
        return $stmt;
    }

    public function modifier_user($id)
    {
        $cnx = new connexion();
        $pdo = $cnx->CNXbase();
        $req = "UPDATE users SET nom = :nom, email = :email WHERE id = :id";
        $stmt = $pdo->prepare($req);
        $stmt->execute([
            ':nom' => $this->user_nom,
            ':email' => $this->user_email,
            ':id' => $id
        ]);
    }

    public function supprimer_user($id)
    {
        $cnx = new connexion();
        $pdo = $cnx->CNXbase();
        $req = "DELETE FROM users WHERE id = :id";
        $stmt = $pdo->prepare($req);
        $stmt->execute([':id' => $id]);
    }

    public function recherche_user()
    {
        $cnx = new connexion();
        $pdo = $cnx->CNXbase();
        $req = "SELECT count(*) FROM users WHERE email = :email";
        $stmt = $pdo->prepare($req);
        $stmt->execute([':email' => $this->user_email]);
        return $stmt;
    }
}
?>

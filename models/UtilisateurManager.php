<?php
require_once __DIR__ . '/Utilisateur.php';


class UtilisateurManager
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function findByEmail(string $email): ?Utilisateur
    {
        $stmt = $this->db->prepare("SELECT * FROM utilisateurs WHERE email = :email");
        $stmt->execute([':email' => $email]);
        $stmt->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Utilisateur');
        $result = $stmt->fetch();
        return $result ?: null;
    }

    public function findById(int $id): ?Utilisateur
    {
        $stmt = $this->db->prepare("SELECT * FROM utilisateurs WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $stmt->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Utilisateur');
        $result = $stmt->fetch();
        return $result ?: null;
    }

    public function insert(Utilisateur $user): bool
    {
        $sql  = "INSERT INTO utilisateurs (nom, email, mot_de_passe, role)
                 VALUES (:nom, :email, :mot_de_passe, :role)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':nom'          => $user->getNom(),
            ':email'        => $user->getEmail(),
            ':mot_de_passe' => $user->getMotDePasse(),
            ':role'         => $user->getRole(),
        ]);
    }

    
    public function authenticate(string $email, string $password): ?Utilisateur
    {
        $user = $this->findByEmail($email);
        if ($user && password_verify($password, $user->getMotDePasse())) {
            return $user;
        }
        return null;
    }

    public function getAllUsers(): array
    {
        $stmt = $this->db->query("SELECT * FROM utilisateurs ORDER BY nom");
        return $stmt->fetchAll(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Utilisateur');
    }
}
?>
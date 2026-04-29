<?php
require_once __DIR__ . '/Emprunt.php';

class EmpruntManager
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

  

    /** Crée un nouvel emprunt (retour prévu dans 14 jours) */
    public function createEmprunt(int $utilisateurId, int $livreId): bool
    {
        $sql  = "INSERT INTO emprunts
                     (utilisateur_id, livre_id, date_emprunt, date_retour_prevue, statut)
                 VALUES
                     (:user_id, :livre_id, NOW(), DATE_ADD(NOW(), INTERVAL 14 DAY), 'en_cours')";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':user_id'  => $utilisateurId,
            ':livre_id' => $livreId,
        ]);
    }

    public function getEmpruntsByUser(int $utilisateurId): array
    {
        $sql  = "SELECT e.*,
                        l.titre  AS livre_titre,
                        l.auteur AS livre_auteur
                   FROM emprunts e
                   JOIN livres l ON e.livre_id = l.id
                  WHERE e.utilisateur_id = :user_id
                  ORDER BY e.date_emprunt DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':user_id' => $utilisateurId]);
        return $this->hydrateAll($stmt->fetchAll(PDO::FETCH_ASSOC));
    }

    /** Tous les emprunts actifs – vue admin */
    public function getEmpruntsActifs(): array
    {
        $sql  = "SELECT e.*,
                        l.titre  AS livre_titre,
                        l.auteur AS livre_auteur,
                        u.nom    AS utilisateur_nom
                   FROM emprunts e
                   JOIN livres      l ON e.livre_id       = l.id
                   JOIN utilisateurs u ON e.utilisateur_id = u.id
                  WHERE e.statut = 'en_cours'
                  ORDER BY e.date_retour_prevue ASC";
        $stmt = $this->db->query($sql);
        return $this->hydrateAll($stmt->fetchAll(PDO::FETCH_ASSOC));
    }

    /* ══════════════════════════════════════════════════════════════
     *  UPDATE
     * ══════════════════════════════════════════════════════════════ */

    /** Marque un emprunt comme rendu */
    public function retournerLivre(int $empruntId): bool
    {
        $sql  = "UPDATE emprunts
                    SET date_retour_reelle = NOW(),
                        statut             = 'rendu'
                  WHERE id = :id
                    AND statut IN ('en_cours', 'en_retard')";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $empruntId]);
    }

    /** Passe en 'en_retard' tous les emprunts dont l'échéance est dépassée */
    public function updateRetards(): int
    {
        $sql  = "UPDATE emprunts
                    SET statut = 'en_retard'
                  WHERE statut = 'en_cours'
                    AND date_retour_prevue < CURDATE()";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->rowCount();
    }

    /* ══════════════════════════════════════════════════════════════
     *  HELPERS / CHECKS
     * ══════════════════════════════════════════════════════════════ */

    /** Vérifie si l'utilisateur a déjà un emprunt actif pour ce livre */
    public function hasActiveLoan(int $utilisateurId, int $livreId): bool
    {
        $sql  = "SELECT COUNT(*) FROM emprunts
                  WHERE utilisateur_id = :user_id
                    AND livre_id       = :livre_id
                    AND statut IN ('en_cours', 'en_retard')";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':user_id' => $utilisateurId, ':livre_id' => $livreId]);
        return (int) $stmt->fetchColumn() > 0;
    }

    public function countActiveLoans(int $utilisateurId): int
    {
        $sql  = "SELECT COUNT(*) FROM emprunts
                  WHERE utilisateur_id = :user_id
                    AND statut IN ('en_cours', 'en_retard')";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':user_id' => $utilisateurId]);
        return (int) $stmt->fetchColumn();
    }


    private function hydrateAll(array $rows): array
    {
        $result = [];
        foreach ($rows as $row) {
            $result[] = $this->hydrateOne($row);
        }
        return $result;
    }

    private function hydrateOne(array $row): Emprunt
    {
        $e = new Emprunt();
    
        foreach ([
            'id', 'utilisateur_id', 'livre_id',
            'date_emprunt', 'date_retour_prevue', 'date_retour_reelle', 'statut',
        ] as $prop) {
            if (array_key_exists($prop, $row)) {
                $ref = new ReflectionProperty(Emprunt::class, $prop);
                $ref->setAccessible(true);
                $ref->setValue($e, $row[$prop]);
            }
        }
      
        if (!empty($row['livre_titre']))      $e->setLivreTitre($row['livre_titre']);
        if (!empty($row['livre_auteur']))     $e->setLivreAuteur($row['livre_auteur']);
        if (!empty($row['utilisateur_nom']))  $e->setUtilisateurNom($row['utilisateur_nom']);
        return $e;
    }
}
?>
<?php
require_once __DIR__ . '/Livre.php';

class LivreManager
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }




    public function getAllLivres(): array
    {
        $stmt = $this->db->query("SELECT * FROM livres ORDER BY titre");
        return $stmt->fetchAll(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Livre');
    }


    public function getLivresDisponibles(): array
    {
        $stmt = $this->db->query("SELECT * FROM livres WHERE stock > 0 ORDER BY titre");
        return $stmt->fetchAll(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Livre');
    }

    
    public function findByCategory(string $cat): array
    {
        $stmt = $this->db->prepare("SELECT * FROM livres WHERE categorie = :cat ORDER BY titre");
        $stmt->execute([':cat' => $cat]);
        return $stmt->fetchAll(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Livre');
    }

    public function findByName(string $name): array
    {
        $stmt = $this->db->prepare("SELECT * FROM livres WHERE titre LIKE :name ORDER BY titre");
        $stmt->execute([':name' => "%$name%"]);
        return $stmt->fetchAll(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Livre');
    }

    
    public function search(string $titre = '', string $categorie = '', string $auteur = ''): array
    {
        $sql    = "SELECT * FROM livres WHERE 1=1";
        $params = [];

        if (!empty($titre)) {
            $sql .= " AND titre LIKE :titre";
            $params[':titre'] = "%$titre%";
        }
        if (!empty($categorie)) {
            $sql .= " AND categorie = :categorie";
            $params[':categorie'] = $categorie;
        }
        if (!empty($auteur)) {
            $sql .= " AND auteur LIKE :auteur";
            $params[':auteur'] = "%$auteur%";
        }

        $sql .= " ORDER BY titre";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Livre');
    }

   
    public function getLivreByCode(string $code): ?Livre
    {
        $stmt = $this->db->prepare("SELECT * FROM livres WHERE code = :code");
        $stmt->execute([':code' => $code]);
        $stmt->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Livre');
        $result = $stmt->fetch();
        return $result ?: null;
    }

   
    public function getLivreById(int $id): ?Livre
    {
        $stmt = $this->db->prepare("SELECT * FROM livres WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $stmt->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Livre');
        $result = $stmt->fetch();
        return $result ?: null;
    }

   
    public function findAllCategories(): array
    {
        $stmt = $this->db->query(
            "SELECT DISTINCT categorie FROM livres
              WHERE categorie IS NOT NULL AND categorie != ''
              ORDER BY categorie"
        );
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }


    public function insert(Livre $livre): bool
    {
        $sql = "INSERT INTO livres
                    (code, titre, auteur, edition, annee_publication,
                     categorie, description, prix, stock, image)
                VALUES
                    (:code, :titre, :auteur, :edition, :annee,
                     :categorie, :description, :prix, :stock, :image)";

        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':code'        => $livre->getCode(),
            ':titre'       => $livre->getTitre(),
            ':auteur'      => $livre->getAuteur(),
            ':edition'     => $livre->getEdition(),
            ':annee'       => $livre->getAnneePublication(),
            ':categorie'   => $livre->getCategorie(),
            ':description' => $livre->getDescription(),
            ':prix'        => $livre->getPrix(),
            ':stock'       => $livre->getStock(),
            ':image'       => $livre->getImage(),
        ]);
    }

    public function update(Livre $livre): bool
    {
        $sql = "UPDATE livres
                   SET titre             = :titre,
                       auteur            = :auteur,
                       edition           = :edition,
                       annee_publication = :annee,
                       categorie         = :categorie,
                       description       = :description,
                       prix              = :prix,
                       stock             = :stock,
                       image             = :image
                 WHERE code = :code";

        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':titre'       => $livre->getTitre(),
            ':auteur'      => $livre->getAuteur(),
            ':edition'     => $livre->getEdition(),
            ':annee'       => $livre->getAnneePublication(),
            ':categorie'   => $livre->getCategorie(),
            ':description' => $livre->getDescription(),
            ':prix'        => $livre->getPrix(),
            ':stock'       => $livre->getStock(),
            ':image'       => $livre->getImage(),
            ':code'        => $livre->getCode(),
        ]);
    }

  
    public function delete(string $code): bool
    {
        $stmt = $this->db->prepare("DELETE FROM livres WHERE code = :code");
        return $stmt->execute([':code' => $code]);
    }


    public function decrementStock(int $livreId): bool
    {
        $stmt = $this->db->prepare(
            "UPDATE livres SET stock = stock - 1 WHERE id = :id AND stock > 0"
        );
        return $stmt->execute([':id' => $livreId]);
    }

    public function incrementStock(int $livreId): bool
    {
        $stmt = $this->db->prepare(
            "UPDATE livres SET stock = stock + 1 WHERE id = :id"
        );
        return $stmt->execute([':id' => $livreId]);
    }
}
?>
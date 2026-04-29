<?php

class Emprunt
{
    private int     $id                  = 0;
    private int     $utilisateur_id      = 0;
    private int     $livre_id            = 0;
    private string  $date_emprunt        = '';
    private string  $date_retour_prevue  = '';
    private ?string $date_retour_reelle  = null;
    private string  $statut              = 'en_cours';

    private ?string $livre_titre         = null;
    private ?string $livre_auteur        = null;
    private ?string $utilisateur_nom     = null;

    public function __construct(
        int $utilisateur_id = 0,
        int $livre_id       = 0
    ) {
        $this->utilisateur_id     = $utilisateur_id;
        $this->livre_id           = $livre_id;
        $this->date_emprunt       = date('Y-m-d H:i:s');
        $this->date_retour_prevue = date('Y-m-d', strtotime('+14 days'));
        $this->statut             = 'en_cours';
    }

   
    public function getId(): int                   { return $this->id; }
    public function getUtilisateurId(): int        { return $this->utilisateur_id; }
    public function getLivreId(): int              { return $this->livre_id; }
    public function getDateEmprunt(): string       { return $this->date_emprunt; }
    public function getDateRetourPrevue(): string  { return $this->date_retour_prevue; }
    public function getDateRetourReelle(): ?string { return $this->date_retour_reelle; }
    public function getStatut(): string            { return $this->statut; }
    public function getLivreTitre(): ?string       { return $this->livre_titre; }
    public function getLivreAuteur(): ?string      { return $this->livre_auteur; }
    public function getUtilisateurNom(): ?string   { return $this->utilisateur_nom; }
    public function isEnCours(): bool  { return $this->statut === 'en_cours'; }
    public function isEnRetard(): bool { return $this->statut === 'en_retard'; }
    public function isRendu(): bool    { return $this->statut === 'rendu'; }

    public function getJoursRestants(): int
    {
        if ($this->isRendu()) return 0;

        $today   = new DateTime();
        $dueDate = new DateTime($this->date_retour_prevue);
        $diff    = (int) $today->diff($dueDate)->days;

        return ($today > $dueDate) ? -$diff : $diff;
    }

    public function isOverdue(): bool
    {
        if ($this->isRendu()) return false;
        return (new DateTime()) > (new DateTime($this->date_retour_prevue));
    }


    public function setLivreTitre(string $titre): void     { $this->livre_titre      = $titre; }
    public function setLivreAuteur(string $auteur): void   { $this->livre_auteur     = $auteur; }
    public function setUtilisateurNom(string $nom): void   { $this->utilisateur_nom  = $nom; }
}
?>
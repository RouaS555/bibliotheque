<?php

class Livre
{
    public const DEVISE = "TND";

    private int     $id            = 0;
    private string  $code          = '';
    private string  $titre         = '';
    private string  $auteur        = '';
    private ?string $edition       = null;
    private ?int    $annee_publication = null;
    private ?string $categorie     = null;
    private ?string $description   = null;
    private float   $prix          = 0.0;
    private int     $stock         = 0;
    private string  $image         = 'default.jpg';
    private string  $date_ajout    = '';

    public function __construct(
        string  $code        = '',
        string  $titre       = '',
        string  $auteur      = '',
        float   $prix        = 0.0,
        int     $stock       = 0,
        string  $categorie   = '',
        ?string $edition     = null,
        ?int    $annee       = null,
        ?string $description = null,
        string  $image       = 'default.jpg'
    ) {
        $this->code        = $code;
        $this->titre       = $titre;
        $this->auteur      = $auteur;
        $this->prix        = $prix;
        $this->stock       = $stock;
        $this->categorie   = $categorie ?: null;
        $this->edition     = $edition;
        $this->annee_publication = $annee;
        $this->description = $description;
        $this->image       = $image;
    }

 
    public function getId(): int              { return $this->id; }
    public function getCode(): string         { return $this->code; }
    public function getTitre(): string        { return $this->titre; }
    public function getAuteur(): string       { return $this->auteur; }
    public function getEdition(): ?string     { return $this->edition; }
    public function getAnneePublication(): ?int { return $this->annee_publication; }
    public function getCategorie(): ?string   { return $this->categorie; }
    public function getDescription(): ?string { return $this->description; }
    public function getPrix(): float          { return $this->prix; }
    public function getStock(): int           { return $this->stock; }
    public function getImage(): string        { return $this->image; }
    public function getDateAjout(): string    { return $this->date_ajout; }


    public function isDisponible(): bool
    {
        return $this->stock > 0;
    }

    public function getPrixFormate(): string
    {
        return number_format($this->prix, 2, '.', ' ') . ' ' . self::DEVISE;
    }

    
    public function getImageUrl(): string
    {
        return '../../public/uploads/livres/' . $this->image;
    }


    public function setCode(string $code): void        { $this->code  = $code; }
    public function setTitre(string $titre): void      { $this->titre = $titre; }
    public function setAuteur(string $auteur): void    { $this->auteur = $auteur; }
    public function setEdition(?string $e): void       { $this->edition = $e; }
    public function setCategorie(?string $c): void     { $this->categorie = $c; }
    public function setDescription(?string $d): void   { $this->description = $d; }
    public function setAnneePublication(?int $a): void { $this->annee_publication = $a; }
    public function setImage(string $image): void      { $this->image = $image; }
    public function setPrix(float $prix): void
    {
        if ($prix >= 0) $this->prix = $prix;
    }
    public function setStock(int $stock): void
    {
        if ($stock >= 0) $this->stock = $stock;
    }

    public function __toString(): string
    {
        return sprintf(
            'Livre [%s] %s | Auteur : %s | Prix : %s | Stock : %d',
            $this->code, $this->titre, $this->auteur,
            $this->getPrixFormate(), $this->stock
        );
    }
}
?>
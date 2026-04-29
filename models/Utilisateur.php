<?php

class Utilisateur
{
    private int    $id               = 0;
    private string $nom              = '';
    private string $email            = '';
    private string $mot_de_passe     = '';
    private string $role             = 'membre';
    private string $date_inscription = '';

    public function __construct(
        string $nom          = '',
        string $email        = '',
        string $mot_de_passe = '',
        string $role         = 'membre'
    ) {
        $this->nom          = $nom;
        $this->email        = $email;
        $this->mot_de_passe = $mot_de_passe;
        $this->role         = $role;
    }

    public function getId(): int              { return $this->id; }
    public function getNom(): string          { return $this->nom; }
    public function getEmail(): string        { return $this->email; }
    public function getMotDePasse(): string   { return $this->mot_de_passe; }
    public function getRole(): string         { return $this->role; }
    public function getDateInscription(): string { return $this->date_inscription; }


    public function isAdmin(): bool  { return $this->role === 'admin'; }
    public function isMembre(): bool { return $this->role === 'membre'; }

    public function setNom(string $nom): void              { $this->nom          = $nom; }
    public function setEmail(string $email): void          { $this->email        = $email; }
    public function setMotDePasse(string $mdp): void       { $this->mot_de_passe = $mdp; }
    public function setRole(string $role): void            { $this->role         = $role; }
}
?>
<?php
/**
 * FileUploader – Upload sécurisé d'images de couverture
 */
class FileUploader
{
    private string $targetDirectory;
    private array  $allowedExtensions;
    private int    $maxSize;
    private array  $errors = [];

    public function __construct(
        string $targetDirectory    = '',           // chemin absolu fourni par le contrôleur
        array  $allowedExtensions  = ['jpg', 'jpeg', 'png', 'gif', 'webp'],
        int    $maxSize            = 2097152        // 2 Mo
    ) {
        // Si aucun chemin fourni, on utilise le dossier public/uploads/livres/ du projet
        if (empty($targetDirectory)) {
            $targetDirectory = dirname(__DIR__) . '/public/uploads/livres/';
        }
        $this->targetDirectory    = rtrim($targetDirectory, '/') . '/';
        $this->allowedExtensions  = array_map('strtolower', $allowedExtensions);
        $this->maxSize            = $maxSize;

        if (!is_dir($this->targetDirectory)) {
            mkdir($this->targetDirectory, 0777, true);
        }
    }

    /** Upload un fichier ; retourne le nouveau nom ou false */
    public function upload(array $fileData): string|false
    {
        $this->errors = [];

        if ($fileData['error'] !== UPLOAD_ERR_OK) {
            $this->addErrorByCode($fileData['error']);
            return false;
        }

        $extension = strtolower(pathinfo($fileData['name'], PATHINFO_EXTENSION));

        if (!in_array($extension, $this->allowedExtensions)) {
            $this->errors[] = "Extension non autorisée : .$extension";
            return false;
        }

        if ($fileData['size'] > $this->maxSize) {
            $maxMo = round($this->maxSize / 1048576, 1);
            $this->errors[] = "Fichier trop lourd (max $maxMo Mo).";
            return false;
        }

        if (!is_dir($this->targetDirectory)) {
            $this->errors[] = "Dossier de destination inaccessible.";
            return false;
        }

        $newFileName = uniqid('livre_', true) . '.' . $extension;
        $destination = $this->targetDirectory . $newFileName;

        if (move_uploaded_file($fileData['tmp_name'], $destination)) {
            return $newFileName;
        }

        $this->errors[] = "Erreur interne lors du déplacement du fichier.";
        return false;
    }

    public function getErrors(): array { return $this->errors; }

    private function addErrorByCode(int $code): void
    {
        match ($code) {
            UPLOAD_ERR_INI_SIZE, UPLOAD_ERR_FORM_SIZE
                => $this->errors[] = "Le fichier dépasse la taille limite.",
            UPLOAD_ERR_PARTIAL
                => $this->errors[] = "Téléchargement partiel.",
            UPLOAD_ERR_NO_FILE
                => $this->errors[] = "Aucun fichier sélectionné.",
            default
                => $this->errors[] = "Erreur inconnue lors de l'upload (code $code).",
        };
    }

    public function setAllowedExtensions(array $ext): void
    {
        $this->allowedExtensions = array_map('strtolower', $ext);
    }
    public function setMaxSize(int $bytes): void { $this->maxSize = $bytes; }
}
?>
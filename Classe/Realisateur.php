<?php

require_once __DIR__ . "/Entite.php";

class Realisateur extends Entite
{
    private ?string $prenom;
    private ?string $nom;

    // Constructeur
    public function __construct(PDO $pdo, ?int $id = null, ?string $prenom = null, ?string $nom = null)
    {
        parent::__construct($pdo, $id);

        $this->prenom = $prenom;
        $this->nom = $nom;
    }

    // Affiche tous les réalisateurs
    public function afficherTous(): array
    {
        $sql = "SELECT id, prenom, nom FROM realisateurs ORDER BY nom";

        $requete = $this->pdo->query($sql);

        return $requete->fetchAll(PDO::FETCH_ASSOC);
    }

    // Supprime un réalisateur
    public function supprimer(int $id): bool
    {
        $sql = "DELETE FROM realisateurs WHERE id = :id";

        $requete = $this->pdo->prepare($sql);

        return $requete->execute(["id" => $id]);
    }
}
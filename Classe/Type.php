<?php

require_once __DIR__ . "/Entite.php";

class Type extends Entite
{
    private ?string $nom;

    // Constructeur
    public function __construct(PDO $pdo, ?int $id = null, ?string $nom = null)
    {
        parent::__construct($pdo, $id);
        $this->nom = $nom;
    }

    // Affiche tous les types
    public function afficherTous(): array
    {
        $sql = "SELECT id, nom FROM types ORDER BY nom";

        $requete = $this->pdo->query($sql);

        return $requete->fetchAll(PDO::FETCH_ASSOC);
    }

    // Supprime un type
    public function supprimer(int $id): bool
    {
        $sql = "DELETE FROM types WHERE id = :id";

        $requete = $this->pdo->prepare($sql);

        return $requete->execute(["id" => $id]);
    }
}
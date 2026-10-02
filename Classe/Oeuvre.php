<?php

require_once __DIR__ . "/Entite.php";

class Oeuvre extends Entite
{
    private ?string $titre;
    private ?int $annee;
    private ?string $description;
    private ?int $realisateurId;
    private ?int $typeId;

    // Constructeur
    public function __construct(
        PDO $pdo,
        ?int $id = null,
        ?string $titre = null,
        ?int $annee = null,
        ?string $description = null,
        ?int $realisateurId = null,
        ?int $typeId = null
    ) {
        parent::__construct($pdo, $id);

        $this->titre = $titre;
        $this->annee = $annee;
        $this->description = $description;
        $this->realisateurId = $realisateurId;
        $this->typeId = $typeId;
    }

    // Affiche toutes les œuvres
    public function afficherTous(): array
    {
        $sql = "SELECT oeuvres.*, realisateurs.prenom,
                realisateurs.nom AS nom_realisateur,
                types.nom AS type,
                GROUP_CONCAT(genres.nom SEPARATOR ', ') AS genres
                FROM oeuvres
                INNER JOIN realisateurs ON oeuvres.realisateur_id = realisateurs.id
                INNER JOIN types ON oeuvres.type_id = types.id
                LEFT JOIN oeuvres_genres ON oeuvres.id = oeuvres_genres.oeuvre_id
                LEFT JOIN genres ON oeuvres_genres.genre_id = genres.id
                GROUP BY oeuvres.id
                ORDER BY oeuvres.titre";

        $requete = $this->pdo->query($sql);

        return $requete->fetchAll(PDO::FETCH_ASSOC);
    }

    // Ajoute une œuvre
    public function ajouter(
        string $titre,
        int $annee,
        string $description,
        int $realisateurId,
        int $typeId
    ): int {
        $sql = "INSERT INTO oeuvres
                (titre, annee, description, realisateur_id, type_id)
                VALUES
                (:titre, :annee, :description, :realisateur_id, :type_id)";

        $requete = $this->pdo->prepare($sql);

        $requete->execute([
            "titre" => $titre,
            "annee" => $annee,
            "description" => $description,
            "realisateur_id" => $realisateurId,
            "type_id" => $typeId
        ]);

        return (int) $this->pdo->lastInsertId();
    }

    // Ajoute les genres d'une œuvre
    public function ajouterGenres(int $oeuvreId, array $genres): bool
    {
        $sql = "INSERT INTO oeuvres_genres (oeuvre_id, genre_id)
                VALUES (:oeuvre_id, :genre_id)";

        $requete = $this->pdo->prepare($sql);

        foreach ($genres as $genreId) {
            $requete->execute([
                "oeuvre_id" => $oeuvreId,
                "genre_id" => $genreId
            ]);
        }

        return true;
    }

    // Trouve une œuvre avec son ID
    public function trouverParId(int $id): array|false
    {
        $sql = "SELECT * FROM oeuvres WHERE id = :id";

        $requete = $this->pdo->prepare($sql);
        $requete->execute(["id" => $id]);

        return $requete->fetch(PDO::FETCH_ASSOC);
    }

    // Modifie une œuvre
    public function modifier(
        int $id,
        string $titre,
        int $annee,
        string $description,
        int $realisateurId,
        int $typeId
    ): bool {
        $sql = "UPDATE oeuvres
                SET titre = :titre,
                    annee = :annee,
                    description = :description,
                    realisateur_id = :realisateur_id,
                    type_id = :type_id
                WHERE id = :id";

        $requete = $this->pdo->prepare($sql);

        return $requete->execute([
            "id" => $id,
            "titre" => $titre,
            "annee" => $annee,
            "description" => $description,
            "realisateur_id" => $realisateurId,
            "type_id" => $typeId
        ]);
    }

    // Récupère les genres d'une œuvre
    public function obtenirGenres(int $oeuvreId): array
    {
        $sql = "SELECT genre_id FROM oeuvres_genres
                WHERE oeuvre_id = :oeuvre_id";

        $requete = $this->pdo->prepare($sql);
        $requete->execute(["oeuvre_id" => $oeuvreId]);

        return $requete->fetchAll(PDO::FETCH_COLUMN);
    }

    // Modifie les genres d'une œuvre
    public function modifierGenres(int $oeuvreId, array $genres): bool
    {
        $sql = "DELETE FROM oeuvres_genres WHERE oeuvre_id = :oeuvre_id";

        $requete = $this->pdo->prepare($sql);
        $requete->execute(["oeuvre_id" => $oeuvreId]);

        if (!empty($genres)) {
            $this->ajouterGenres($oeuvreId, $genres);
        }

        return true;
    }

    // Supprime une œuvre
    public function supprimer(int $id): bool
    {
        $sql = "DELETE FROM oeuvres WHERE id = :id";

        $requete = $this->pdo->prepare($sql);

        return $requete->execute(["id" => $id]);
    }
}
<?php

require_once "Classe/Database.php";
require_once "Classe/Oeuvre.php";
require_once "Classe/Realisateur.php";
require_once "Classe/Type.php";
require_once "Classe/Genre.php";

// Connexion à la base de données
$database = new Database();
$pdo = $database->getConnection();

// Création des objets
$oeuvre = new Oeuvre($pdo);
$realisateur = new Realisateur($pdo);
$type = new Type($pdo);
$genre = new Genre($pdo);

// Données pour les listes du formulaire
$realisateurs = $realisateur->afficherTous();
$types = $type->afficherTous();
$genres = $genre->afficherTous();

// Lorsque le formulaire est envoyé
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $titre = $_POST["titre"];
    $annee = (int) $_POST["annee"];
    $description = $_POST["description"];
    $realisateurId = (int) $_POST["realisateur_id"];
    $typeId = (int) $_POST["type_id"];
    $genresChoisis = $_POST["genres"] ?? [];

    // Ajoute l'œuvre
    $nouvelId = $oeuvre->ajouter(
        $titre,
        $annee,
        $description,
        $realisateurId,
        $typeId
    );

    // Ajoute les genres choisis
    if (!empty($genresChoisis)) {
        $oeuvre->ajouterGenres($nouvelId, $genresChoisis);
    }

    header("Location: index.php");
    exit;
}

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Ajouter une œuvre</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>

<div class="container">

    <h1>Ajouter une œuvre</h1>

    <form method="POST" class="formulaire">

        <label>Titre</label>
        <input type="text" name="titre" required>

        <label>Année</label>
        <input type="number" name="annee" required>

        <label>Description</label>
        <textarea name="description" required></textarea>

        <label>Réalisateur / Créateur</label>

        <select name="realisateur_id">

            <?php foreach ($realisateurs as $realisateur): ?>

                <option value="<?= $realisateur["id"] ?>">
                    <?= htmlspecialchars(
                        $realisateur["prenom"] . " " . $realisateur["nom"]
                    ) ?>
                </option>

            <?php endforeach; ?>

        </select>


        <label>Type</label>

        <select name="type_id">

            <?php foreach ($types as $type): ?>

                <option value="<?= $type["id"] ?>">
                    <?= htmlspecialchars($type["nom"]) ?>
                </option>

            <?php endforeach; ?>

        </select>


        <fieldset>

            <legend>Genres</legend>

            <?php foreach ($genres as $genre): ?>

                <label>
                    <input
                        type="checkbox"
                        name="genres[]"
                        value="<?= $genre["id"] ?>"
                    >

                    <?= htmlspecialchars($genre["nom"]) ?>
                </label>

                <br>

            <?php endforeach; ?>

        </fieldset>


        <button type="submit" class="bouton bouton-principal">
            Ajouter
        </button>

        <a href="index.php">
            Annuler
        </a>

    </form>

</div>

</body>
</html>
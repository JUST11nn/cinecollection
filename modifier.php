<?php

require_once "Classe/Database.php";
require_once "Classe/Oeuvre.php";
require_once "Classe/Realisateur.php";
require_once "Classe/Type.php";
require_once "Classe/Genre.php";

// Connexion
$database = new Database();
$pdo = $database->getConnection();

$oeuvreObjet = new Oeuvre($pdo);
$realisateurObjet = new Realisateur($pdo);
$typeObjet = new Type($pdo);
$genreObjet = new Genre($pdo);

// Prendre le id dans le URL
$id = $_GET["id"];

// Trouver l'oeuvre
$oeuvre = $oeuvreObjet->trouverParId($id);

// Les listes pour le formulaire
$realisateurs = $realisateurObjet->afficherTous();
$types = $typeObjet->afficherTous();
$genres = $genreObjet->afficherTous();

$genresSelectionnes = $oeuvreObjet->obtenirGenres($id);

// Si on clique sur enregistrer
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $titre = $_POST["titre"];
    $annee = $_POST["annee"];
    $description = $_POST["description"];
    $realisateur = $_POST["realisateur_id"];
    $type = $_POST["type_id"];

    if (isset($_POST["genres"])) {
        $genresChoisis = $_POST["genres"];
    } else {
        $genresChoisis = [];
    }

    $oeuvreObjet->modifier(
        $id,
        $titre,
        $annee,
        $description,
        $realisateur,
        $type
    );

    $oeuvreObjet->modifierGenres($id, $genresChoisis);

    header("Location: index.php");
    exit;
}

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Modifier</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>

<div class="container">

    <h1>Modifier une oeuvre</h1>

    <form method="POST" class="formulaire">

        <label>Titre</label>
        <input type="text" name="titre"
        value="<?= htmlspecialchars($oeuvre["titre"]) ?>" required>

        <label>Année</label>
        <input type="number" name="annee"
        value="<?= $oeuvre["annee"] ?>" required>

        <label>Description</label>
        <textarea name="description"><?= htmlspecialchars($oeuvre["description"]) ?></textarea>

        <label>Réalisateur</label>

        <select name="realisateur_id">

            <?php foreach ($realisateurs as $r): ?>

                <option value="<?= $r["id"] ?>"
                <?php if ($r["id"] == $oeuvre["realisateur_id"]) echo "selected"; ?>>

                    <?= htmlspecialchars($r["prenom"] . " " . $r["nom"]) ?>

                </option>

            <?php endforeach; ?>

        </select>

        <label>Type</label>

        <select name="type_id">

            <?php foreach ($types as $t): ?>

                <option value="<?= $t["id"] ?>"
                <?php if ($t["id"] == $oeuvre["type_id"]) echo "selected"; ?>>

                    <?= htmlspecialchars($t["nom"]) ?>

                </option>

            <?php endforeach; ?>

        </select>

        <fieldset>

            <legend>Genres</legend>

            <?php foreach ($genres as $g): ?>

                <label>

                    <input type="checkbox"
                    name="genres[]"
                    value="<?= $g["id"] ?>"
                    <?php if (in_array($g["id"], $genresSelectionnes)) echo "checked"; ?>>

                    <?= htmlspecialchars($g["nom"]) ?>

                </label>

                <br>

            <?php endforeach; ?>

        </fieldset>

        <br>

        <button type="submit" class="bouton bouton-principal">
            Enregistrer
        </button>

        <a href="index.php">Annuler</a>

    </form>

</div>

</body>
</html>
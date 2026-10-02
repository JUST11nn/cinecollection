<?php

require_once "Classe/Database.php";
require_once "Classe/Oeuvre.php";

// Connexion à la base de données
$database = new Database();
$pdo = $database->getConnection();

// Récupère toutes les œuvres
$oeuvre = new Oeuvre($pdo);
$oeuvres = $oeuvre->afficherTous();

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>CinéCollection</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>

<div class="container">

    <h1>CinéCollection</h1>
    <p>Gestion de ma collection de films et séries</p>

    <nav>
        <a href="index.php">Accueil</a> |
        <a href="ajouter.php">Ajouter une œuvre</a>
    </nav>

    <hr>

    <h2>Films et séries</h2>

    <?php if (empty($oeuvres)): ?>

        <p>Aucune œuvre dans la collection.</p>

    <?php else: ?>

        <?php foreach ($oeuvres as $item): ?>

            <div class="carte">

                <h3><?= htmlspecialchars($item["titre"]) ?></h3>

                <p>
                    <strong>Type :</strong>
                    <?= htmlspecialchars($item["type"]) ?>
                </p>

                <p>
                    <strong>Année :</strong>
                    <?= htmlspecialchars($item["annee"]) ?>
                </p>

                <p>
                    <strong>Réalisateur :</strong>
                    <?= htmlspecialchars($item["prenom"] . " " . $item["nom_realisateur"]) ?>
                </p>

                <p>
                    <strong>Genres :</strong>
                    <?= htmlspecialchars($item["genres"] ?? "Aucun genre") ?>
                </p>

                <p>
                    <strong>Description :</strong>
                    <?= htmlspecialchars($item["description"]) ?>
                </p>

                <a href="modifier.php?id=<?= $item["id"] ?>">Modifier</a> |
                <a href="supprimer.php?id=<?= $item["id"] ?>">Supprimer</a>

            </div>

            <br>

        <?php endforeach; ?>

    <?php endif; ?>

</div>

</body>
</html>
<?php

require_once "Classe/Database.php";
require_once "Classe/Oeuvre.php";

$database = new Database();
$pdo = $database->getConnection();

$oeuvre = new Oeuvre($pdo);

// Récupère l'id dans l'URL
$id = (int) $_GET["id"];

// Cherche l'œuvre
$oeuvreExistante = $oeuvre->trouverParId($id);

// Supprime l'œuvre lorsque le formulaire est envoyé
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $oeuvre->supprimer($id);

    header("Location: index.php");
    exit;
}

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Supprimer une œuvre</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>

<div class="container">

    <h1>Supprimer une œuvre</h1>

    <p>
        Voulez-vous vraiment supprimer
        <strong><?= htmlspecialchars($oeuvreExistante["titre"]) ?></strong> ?
    </p>

    <form method="POST">
        <button type="submit" class="bouton bouton-supprimer">
            Supprimer
        </button>
    </form>

    <br>

    <a href="index.php">Annuler</a>

</div>

</body>

</html>
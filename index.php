<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
// Inclusion des classes
require_once('class/GuestBook.php');
require_once('class/Message.php');

// Création d'un objet GuestBook avec le chemin vers le fichier JSON
$guestBook = new GuestBook("data/messages.json");

// Récupération de tous les messages
$messages = $guestBook->getMessages();

// Vérification si le formulaire a été soumis (méthode POST)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Vérification de l'existence des fichiers GuestBook.php et Message.php avant de les inclure
    if (file_exists('class/GuestBook.php') && file_exists('class/Message.php')) {
        // Inclusion des fichiers

        // Récupération des données du formulaire
        $username = $_POST["username"];
        $messageText = $_POST["message"];

        // Création d'un nouvel objet Message avec les données du formulaire
        $newMessage = new Message($username, $messageText);

        // Ajout du nouveau message à l'objet GuestBook
        $guestBook->addMessage($newMessage);

        // Redirection vers la même page pour éviter la duplication du dernier commentaire lors du rafraîchissement
        header("Location: {$_SERVER['REQUEST_URI']}");
        exit();
    } else {
        // Gestion de l'erreur si l'un des fichiers requis est manquant
        die("Erreur: Fichier manquant.");
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="book-creative.png" type="image/x-icon">
    <link rel="stylesheet" href="style.css">
    <title>Guest Book</title>
</head>
<body>

<?php include("elements/header.php"); ?>

<strong><h1>Votre avis nous intéresse</h1></strong>
<strong><h2>Merci de prendre le temps de nous donner votre avis.</h2></strong>

<!-- Formulaire -->
<form action="" method="post">
    <strong><label for="username">Votre pseudo :</label></strong>
    <input type="text" id="username" name="username" required placeholder="BAKA">

    <strong><label for="message">Votre message :</label></strong>
    <textarea id="message" name="message" required placeholder="Test 1-2 1-2"></textarea>

    </br>
    <button class="btn btn--stripe btn--radius" type="submit">VALIDER</button>
</form>

<!-- Affichage des messages -->
<h2>Vos messages</h2>
<div id="comments">
    <?php
    // Vérification de l'existence de messages
    if ($messages) {
        // Inversion de l'ordre des commentaires pour afficher les plus récents en premier
        $reversedMessages = array_reverse($messages);

        // Affichage des messages
        foreach ($reversedMessages as $message) {
            echo $message->toHTML();
        }
    }
    ?>
</div>

<?php include("elements/footer.php"); ?>

</body>
</html>
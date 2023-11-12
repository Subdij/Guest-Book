<?php

// Vérifier si le formulaire a été soumis (méthode POST)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Vérifier l'existence des fichiers GuestBook.php et Message.php avant de les inclure
    if (file_exists('class/GuestBook.php') && file_exists('class/Message.php')) {
        // Inclure les fichiers nécessaires
        require_once('class/GuestBook.php');
        require_once('class/Message.php');

        // Récupérer les données du formulaire
        $username = $_POST["username"];
        $messageText = $_POST["message"];

        // Créer un nouvel objet Message avec les données du formulaire
        $newMessage = new Message($username, $messageText);

        // Initialiser un objet GuestBook avec le chemin vers le fichier JSON
        $guestBook = new GuestBook("data/messages.json");

        // Ajouter le nouveau message
        $guestBook->addMessage($newMessage);

        // Rediriger vers la même page pour éviter la duplication du dernier commentaire lors du rafraîchissement
        header("Location: {$_SERVER['REQUEST_URI']}");
        exit();
    } else {
        // Gérer l'erreur si l'un des fichiers requis est manquant
        die("Erreur: Fichier manquant.");
    }
}

// Vérifier l'existence du fichier GuestBook.php avant de l'inclure
if (file_exists('class/GuestBook.php')) {
    require_once('class/GuestBook.php');
} else {
    echo "Le fichier GuestBook.php n'existe pas.";
}

// Vérifier l'existence du fichier Message.php avant de l'inclure
if (file_exists('class/Message.php')) {
    require_once('class/Message.php');
} else {
    echo "Le fichier Message.php n'existe pas.";
}

// Initialiser un objet GuestBook avec le chemin vers le fichier JSON
$guestBook = new GuestBook("data/messages.json");

// Si le formulaire n'a pas été soumis, vérifier à nouveau s'il a été soumis et traiter les données
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $messageText = $_POST["message"];

    // Créer un nouvel objet Message avec les données du formulaire
    $newMessage = new Message($username, $messageText);

    // Ajouter le nouveau message
    $guestBook->addMessage($newMessage);
}

// Récupérer tous les messages
$messages = $guestBook->getMessages();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Guest Book</title>
</head>
<body>

<?php include("elements/header.php"); ?>

<strong><h3>Merci de prendre le temps de nous donner votre avis.</h3></strong>

<!-- Formulaire -->
<form action="" method="post">
    <strong><label for="username">Username :</label></strong>
    <input type="text" id="username" name="username" required>

    <strong><label for="message">Message :</label></strong>
    <textarea id="message" name="message" required></textarea>


    </br>
    <button type="submit">SUBMIT</button>
</form>

<h2>Messages</h2>
<div id="comments">
    <?php
    // Inverser l'ordre des commentaires
    $reversedMessages = array_reverse($messages);

    // Afficher les messages
    foreach ($reversedMessages as $message) {
        echo $message->toHTML();
    }
    ?>
</div>

<?php include("elements/footer.php"); ?>

</body>
</html>

<?php

class GuestBook {
    // Propriétés privées de la classe
    private $messages;
    private $filePath;

    // Constructeur de la classe
    public function __construct($filePath) {
        // Initialisation du chemin du fichier
        $this->filePath = $filePath;

        // Si le fichier n'existe pas, le créer avec un tableau JSON vide
        if (!file_exists($filePath)) {
            file_put_contents($filePath, '[]');
        }

        // Charger les messages depuis le fichier
        $this->messages = $this->loadMessages();
    }

    // Méthode pour obtenir la liste des messages
    public function getMessages() {
        return $this->messages;
    }

    // Méthode pour ajouter un nouveau message
    public function addMessage(Message $message) {
        // Ajouter le message à la liste et sauvegarder les messages mis à jour
        $this->messages[] = $message;
        $this->saveMessages();
    }

    // Méthode privée pour charger les messages depuis le fichier
    private function loadMessages() {
        // Lire le contenu du fichier
        $content = file_get_contents($this->filePath);

        // Décoder le contenu JSON en un tableau associatif
        $messagesData = json_decode($content, true);

        // Vérifier les erreurs de décodage JSON
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception('Erreur lors du décodage JSON : ' . json_last_error_msg());
        }

        // Initialiser un tableau pour stocker les objets Message
        $messages = [];

        // Convertir les données du message en objets Message et les ajouter au tableau
        foreach ($messagesData as $messageData) {
            $messages[] = Message::fromJSON(json_encode($messageData));
        }

        // Retourner le tableau des messages
        return $messages;
    }

    // Méthode privée pour sauvegarder les messages dans le fichier
    private function saveMessages() {
        // Initialiser un tableau pour stocker les données du message en format JSON
        $messagesData = [];

        // Convertir chaque objet Message en tableau associatif et l'ajouter au tableau
        foreach ($this->messages as $message) {
            $messagesData[] = json_decode($message->toJSON(), true);
        }

        // Convertir le tableau des données du message en JSON avec une mise en forme lisible
        $content = json_encode($messagesData, JSON_PRETTY_PRINT);

        // Écrire le contenu JSON dans le fichier
        file_put_contents($this->filePath, $content);
    }
}
?>

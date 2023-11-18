<?php

class Message {
    // Propriétés privées de la classe
    private $username;
    private $message;
    private $date;

    // Constructeur de la classe
    public function __construct($username, $message, $date = null) {
        // Initialisation des propriétés avec les valeurs fournies
        $this->username = $username;
        $this->message = $message;

        // Vérification de la date fournie et utilisation de la date actuelle si elle n'est pas spécifiée
        if ($date instanceof DateTime) {
            $this->date = $date;
        } else {
            $this->date = new DateTime();
        }
    }

    // Méthode pour obtenir le nom d'utilisateur
    public function getUsername() {
        return $this->username;
    }

    // Méthode pour obtenir le message
    public function getMessage() {
        return $this->message;
    }

    // Méthode pour obtenir la date du message
    public function getDate() {
        return $this->date;
    }

    // Méthode pour convertir le message en HTML
    public function toHTML() {
        $escapedUsername = htmlspecialchars($this->username);
        $escapedDate = htmlspecialchars($this->date->format('d/m/Y à H:i'));
        $escapedMessage = htmlspecialchars($this->message);
    
        return "<p><strong>{$escapedUsername}</strong> <em>{$escapedDate}</em><br>{$escapedMessage}</p>";
    }

    // Méthode pour convertir le message en format JSON
    public function toJSON() {
        return json_encode([
            'username' => $this->username,
            'message' => $this->message,
            'date' => $this->date->format('Y-m-d H:i:s')
        ]);
    }

    // Méthode statique pour créer un objet Message à partir d'une chaîne JSON
    public static function fromJSON(string $json): Message {
        // Décodage de la chaîne JSON
        $data = json_decode($json, true);

        // Vérification des erreurs de décodage JSON
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception('Erreur lors du décodage JSON : ' . json_last_error_msg());
        }

        // Récupération des valeurs du tableau associatif JSON
        $username = isset($data['username']) ? $data['username'] : null;
        $message = isset($data['message']) ? $data['message'] : null;
        $date = isset($data['date']) ? DateTime::createFromFormat('Y-m-d H:i:s', $data['date']) : new DateTime();

        // Vérification des erreurs de création de l'objet DateTime
        if ($date === false) {
            throw new Exception('Erreur lors de la création de l\'objet DateTime.');
        }

        // Retour de l'objet Message nouvellement créé
        return new Message($username, $message, $date);
    }
}
?>

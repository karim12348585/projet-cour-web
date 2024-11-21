<?php


class Database {
    private $connection;

    // Constructeur : établit la connexion avec la base de données
    public function __construct($host, $user, $password, $dbname) {
        
        $this->connection = new mysqli($host, $user, $password, $dbname);

        if ($this->connection->connect_error) {
            die("Erreur de connexion : " . $this->connection->connect_error);
        }
    }

    // Méthode pour vérifier si un utilisateur existe déjà
    public function userExists($email) {
        $sql = "SELECT id FROM utilisateurs WHERE email = ?";
        $stmt = $this->connection->prepare($sql);
        if (!$stmt) {
            die("Erreur de préparation de la requête : " . $this->connection->error);
        }

        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        // Retourne true si un utilisateur existe, sinon false
        return $result->num_rows > 0;
    }

    // Méthode pour enregistrer un utilisateur (Sign Up)
    public function saveUser($name, $email, $password) {
        $sql = "INSERT INTO utilisateurs (nom, email, mot_de_passe) VALUES (?, ?, ?)";

        $stmt = $this->connection->prepare($sql);
        if (!$stmt) {
            die("Erreur de préparation de la requête : " . $this->connection->error);
        }

        $stmt->bind_param("sss", $name, $email, $password);

        if (!$stmt->execute()) {
            die("Erreur lors de l'exécution de la requête : " . $stmt->error);
        }

        $stmt->close();
    }
    

    // Méthode pour fermer la connexion
    public function closeConnection() {
        $this->connection->close();
    }
}




class Hyperparameters {
    private $learningRate;
    private $epochs;
    private $patience;
    private $monitor;
    private $optimizer;
    private $activationFunction;
    private $validationSplit;
    private $testSplit;

    // Constructeur
    public function __construct($params) {
        $this->learningRate = $params['learning_rate'];
        $this->epochs = $params['epochs'];
        $this->patience = $params['patience'];
        $this->monitor = $params['monitor'];
        $this->optimizer = $params['optimizer'];
        $this->activationFunction = $params['activation_function'];
        $this->validationSplit = $params['validation_split'];
        $this->testSplit = $params['test_split'];
    }

    // Méthode pour valider les hyperparamètres
    public function validate() {
        if ($this->learningRate <= 0 || $this->learningRate > 1) {
            throw new Exception("Le taux d'apprentissage doit être compris entre 0 et 1.");
        }
        if ($this->epochs < 1) {
            throw new Exception("Le nombre d'époques doit être supérieur à 0.");
        }
        if (!in_array($this->monitor, ['val_loss', 'val_accuracy'])) {
            throw new Exception("Le monitor n'est pas valide.");
        }
        // Ajoutez d'autres validations si nécessaire
    }

    // Getter pour récupérer les hyperparamètres sous forme de tableau
    public function toArray() {
        return [
            'learning_rate' => $this->learningRate,
            'epochs' => $this->epochs,
            'patience' => $this->patience,
            'monitor' => $this->monitor,
            'optimizer' => $this->optimizer,
            'activation_function' => $this->activationFunction,
            'validation_split' => $this->validationSplit,
            'test_split' => $this->testSplit,
        ];
    }
}

?>

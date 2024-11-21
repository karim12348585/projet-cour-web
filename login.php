<?php
session_start(); // Démarrer la session

// Paramètres de connexion à la base de données
$host = 'localhost';
$user = 'root';
$password = '';
$dbname = 'image_classification';

// Connexion à la base de données
$connection = new mysqli($host, $user, $password, $dbname);

// Vérifier la connexion
if ($connection->connect_error) {
    die("Erreur de connexion : " . $connection->connect_error);
}

// Vérifier si le formulaire de connexion a été soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérer l'email et le mot de passe soumis par l'utilisateur
    $email = htmlspecialchars($_POST['email']);
    $password = htmlspecialchars($_POST['password']);
    
    // Validation de base
    if (empty($email) || empty($password)) {
        die("Tous les champs sont obligatoires.");
    }

    // Requête pour vérifier si l'utilisateur existe dans la base de données
    $sql = "SELECT * FROM utilisateurs WHERE email = ? LIMIT 1";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("s", $email); // Lier l'email à la requête préparée
    $stmt->execute();
    $result = $stmt->get_result();

    // Vérifier si un utilisateur a été trouvé
    if ($result->num_rows > 0) {
        // L'utilisateur existe, récupérer les informations
        $user = $result->fetch_assoc();

        // Comparer le mot de passe en texte clair
        if ($password === $user['mot_de_passe']) { // Utilisez mot_de_passe pour le champ non-haché
            // Démarrer la session et enregistrer l'utilisateur
            $_SESSION['user_id'] = $user['id']; // Exemple : enregistrer l'ID utilisateur
            $_SESSION['email'] = $user['email']; // Enregistrer l'email de l'utilisateur

            // Redirection vers la page privée (interface.html)
            header("Location: http://localhost/projet%20cour/html/interface.html");
            exit(); // Terminer le script ici après la redirection
        } else {
            echo "Mot de passe incorrect.";
        }
    } else {
        echo "Aucun utilisateur trouvé avec cet email.";
    }

    // Fermer la requête
    $stmt->close();
}

// Fermer la connexion
$connection->close();
?>

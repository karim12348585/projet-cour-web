<?php
// Inclure la classe Database
include 'classes.php';


// Paramètres de connexion à la base de données
$host = 'localhost';
$user = 'root';
$password = '';
$dbname = 'image_classification';

// Initialiser la connexion
$db = new Database($host, $user, $password, $dbname);

// Vérifier si le formulaire a été soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $password = htmlspecialchars($_POST['password']);

    // Validation de base
    if (empty($name) || empty($email) || empty($password)) {
        die("Tous les champs sont obligatoires.");
    }

    // Vérifier si l'utilisateur existe déjà
    if ($db->userExists($email)) {
        die("Erreur : Un utilisateur avec cet email existe déjà.");
    }

    // Appeler la méthode pour sauvegarder l'utilisateur
    try {
        $db->saveUser($name, $email, $password);
        echo "Inscription réussie ! Vous pouvez maintenant vous connecter.";
    } catch (Exception $e) {
        echo "Erreur : " . $e->getMessage();
    }
}

// Fermer la connexion
$db->closeConnection();
?>
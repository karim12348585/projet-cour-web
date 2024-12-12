<?php
include 'classes.php';
session_start(); // Start the session to access session variables

// Paramètres de connexion à la base de données
$host = 'localhost';
$user = 'root';
$password = '';
$dbname = 'image_classification';

// Connexion à la base de données
$connection = new mysqli($host, $user, $password, $dbname);

// Check for connection errors
if ($connection->connect_error) {
    die("Database connection failed: " . $connection->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupération des données du formulaire
    $directory_path = $_POST['directory_path'];
    $learning_rate = $_POST['learning_rate'];
    $epochs = $_POST['epochs'];
    $patience = $_POST['patience'];
    $monitor = $_POST['monitor'];
    $optimizer = $_POST['optimizer'];
    $activation_function = $_POST['activation_function'];
    $validation_split = $_POST['validation_split'];
    $test_split = $_POST['test_split'];

    // Get the user's name or ID from the session
    $user = $_SESSION['nom'] ?? 'Guest'; // Fallback to 'Guest' if not logged in
    // Save parameters in session
    
    $_SESSION['parameters'] = [
        
        'directory_path' => $directory_path,
        'learning_rate' => $learning_rate,
        'epochs' => $epochs,
        'patience' => $patience,
        'monitor' => $monitor,
        'optimizer' => $optimizer,
        'activation_function' => $activation_function,
        'validation_split' => $validation_split,
        'test_split' => $test_split
];
    // Prepare the SQL query with positional placeholders
    $sql = "INSERT INTO treatments 
            (utilisateur, directory_path, learning_rate, epochs, patience, monitor, optimizer, activation_function, validation_split, test_split) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    // Prepare the statement
    if ($stmt = $connection->prepare($sql)) {
        // Bind parameters (s = string, d = double, i = integer)
        $stmt->bind_param(
            'ssdiiissdd',
            $user,
            $directory_path,
            $learning_rate,
            $epochs,
            $patience,
            $monitor,
            $optimizer,
            $activation_function,
            $validation_split,
            $test_split
        );

        // Execute the statement
        if ($stmt->execute()) {
            header("Location: result.php");
            
        } 

        // Close the statement
        $stmt->close();
    } else {
        echo "Erreur de préparation : " . $connection->error;
    }
}
?>

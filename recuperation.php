<?php
include 'classes.php';

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

    // Requête d'insertion
    $sql = "INSERT INTO treatments 
            (directory_path, learning_rate, epochs, patience, monitor, optimizer, activation_function, validation_split, test_split) 
            VALUES (:directory_path, :learning_rate, :epochs, :patience, :monitor, :optimizer, :activation_function, :validation_split, :test_split)";
    
    $stmt = $pdo->prepare($sql);

    try {
        $stmt->execute([
            ':directory_path' => $directory_path,
            ':learning_rate' => $learning_rate,
            ':epochs' => $epochs,
            ':patience' => $patience,
            ':monitor' => $monitor,
            ':optimizer' => $optimizer,
            ':activation_function' => $activation_function,
            ':validation_split' => $validation_split,
            ':test_split' => $test_split
        ]);
        echo "Traitement enregistré avec succès !";
    } catch (Exception $e) {
        echo "Erreur lors de l'enregistrement : " . $e->getMessage();
    }
}
?>

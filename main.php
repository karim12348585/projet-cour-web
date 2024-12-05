<?php

// Inclusion des fichiers nécessaires
require_once 'classes.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupération des données du formulaire
    $params = [
        'learning_rate' => $_POST['learning_rate'],
        'epochs' => $_POST['epochs'],
        'patience' => $_POST['patience'],
        'monitor' => $_POST['monitor'],
        'optimizer' => $_POST['optimizer'],
        'activation_function' => $_POST['activation_function'],
        'validation_split' => $_POST['validation_split'],
        'test_split' => $_POST['test_split'],
    ];

    try {
        // Création de l'objet Hyperparameters
        $hyperparameters = new Hyperparameters($params);

        // Validation des hyperparamètres
        $hyperparameters->validate();

        // Récupération des hyperparamètres sous forme de tableau
        $hyperparametersArray = $hyperparameters->toArray();

        // Exemple : affichage des hyperparamètres (ou traitement supplémentaire)
        echo "<h2>Hyperparamètres validés :</h2>";
        echo "<pre>" . print_r($hyperparametersArray, true) . "</pre>";

    } catch (Exception $e) {
        // Gestion des erreurs
        echo "<h2>Erreur :</h2>";
        echo "<p>" . $e->getMessage() . "</p>";
    }
} else {
    echo "<h2>Accès non autorisé.</h2>";
}

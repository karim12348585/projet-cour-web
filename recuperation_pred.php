<?php
session_start();

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




if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Vérifier si le nom du dossier est envoyé via le formulaire
    if (isset($_POST['directory-name'])) {
        $directoryName = $_POST['directory-name'];

        // The argument you want to pass to the Python script
        $argument = $directoryName;

        // Path to your Python script
        $pythonScriptPath = "predict.py";

        // Command to execute the Python script with the argument
        $command = "conda activate myenv && python $pythonScriptPath '$argument'";


        // Execute the command and capture the output
        $output = shell_exec($command);

        header("location: result_prediction.php");


        //header("Location: result_prediction.php");


        


        


        
}}






?>

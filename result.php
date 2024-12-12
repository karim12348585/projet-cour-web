<?php
session_start();

// Assuming you have the session parameters
if (isset($_SESSION['parameters'])) {
    // Get the parameters from the session
    $parameters = $_SESSION['parameters'];

    // Convert the parameters to JSON
    $json_params = json_encode($parameters);

    // Ensure the JSON is correctly encoded
    

    // Save the JSON data to a file (params.json)
    file_put_contents('params.json', $json_params);

    // Construct the shell command to execute the Python script without passing arguments
    $command = "conda activate myenv && python model.py
";  // No need to pass the JSON string in the command line

    // Output the command for debugging
   


    // Execute the command and capture the output
    $output = shell_exec($command);


} 
    

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Classification d'Images - Graphiques</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="html/modele_result.css">
</head>
<body>
    <header class="text-white text-center py-4 shadow-lg">
        <h1><i class="fas fa-camera"></i> Modèle de Classification d'Images</h1>
        <a href="logout.php" class="btn btn-disconnect position-absolute top-0 end-0 mt-3 me-4">Se Déconnecter</a>
    </header>

    <main class="container my-5">
        <!-- Buttons for Testing and Creating Model -->
        <div class="text-center mb-4">
            <a href="historique.php" class="btn btn-test-model"><i class="fas fa-play"></i> Voir historique </a>
            <!-- Create New Model Link -->
            <a href="interface.php" class="btn btn-create-model"><i class="fas fa-plus-circle"></i> Créer un Nouveau Modèle</a>
        </div>

        <!-- Graphs Section -->
        <section id="graphs" class="mb-5">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-gradient-primary text-white">
                    <h2><i class="fas fa-chart-line"></i> Graphiques d'Entraînement</h2>
                </div>
                <div class="card-body">
                    <!-- Accuracy Graph -->
                    <h3>Graphique de Précision et de perte</h3>
                    <img src="graphs/graph_acc.png." alt="Graphique de Précision" class="img-fluid mb-4">
                    


                    <!-- Entropy by Class Graph -->
                    <h3>Entropie par Classe</h3>
                    <img src="graphs/Average Entropy by Class.png" alt="Entropie par Classe" class="img-fluid mb-4">

                    <!-- Overall Entropy Graph -->
                    <h3>Entropie Globale</h3>
                    <img src="graphs/mean entropy.png" alt="Entropie moy de chaque classe " class="img-fluid mb-4">
                </div>
            </div>
        </section>
    </main>

    <footer class="bg-dark text-white text-center py-3">
        <p>&copy; 2024 Projet web dynamique Classification d'Images</p>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

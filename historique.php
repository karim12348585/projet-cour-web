<?php
// Démarrer la session
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['nom'])) {
    // Rediriger vers la page de connexion si l'utilisateur n'est pas connecté
    header("Location: login.html");
    exit();
}

// Récupérer le nom d'utilisateur de la session
$username = $_SESSION['nom'];

// Paramètres de connexion à la base de données
$host = 'localhost';
$user = 'root';
$password = '';
$dbname = 'image_classification';

// Connexion à la base de données
$conn = new mysqli($host, $user, $password, $dbname);

// Vérification des erreurs de connexion
if ($conn->connect_error) {
    die("Échec de la connexion à la base de données: " . $conn->connect_error);
}

// Requête SQL pour récupérer les données filtrées par l'utilisateur
$sql = "SELECT directory_path, learning_rate, epochs, patience, monitor, optimizer, activation_function, validation_split, test_split 
        FROM treatments 
        WHERE utilisateur = ?";  // Filtrage basé sur le nom d'utilisateur

// Préparer la requête
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);  // Lier le nom d'utilisateur à la requête préparée

// Exécuter la requête
$stmt->execute();
$result = $stmt->get_result();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historique des Modèles</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="html/historique.css">
</head>
<body>
    <header class="text-white text-center py-4 shadow-lg">
        <h1><i class="fas fa-history"></i> Historique des Modèles</h1>
        <a href="logout.php" class="btn btn-disconnect position-absolute top-0 end-0 mt-3 me-4">Se Déconnecter</a>
    </header>

    <main class="container my-5">
        <div class="card shadow-lg border-0">
            <div class="card-header bg-gradient-primary text-white">
                <h2><i class="fas fa-table"></i> Historique des Paramètres du Modèle</h2>
            </div>
            <div class="card-body">
                <!-- Tableau des résultats -->
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Répertoire</th>
                            <th>Taux d'Apprentissage</th>
                            <th>Époques</th>
                            <th>Patience</th>
                            <th>Moniteur</th>
                            <th>Optimiseur</th>
                            <th>Fonction d'Activation</th>
                            <th>Validation Split</th>
                            <th>Test Split</th>
                            <th>Exécuter</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($result->num_rows > 0) {
                            while($row = $result->fetch_assoc()) {
                                echo "<tr>
                                    <td>{$row['directory_path']}</td>
                                    <td>{$row['learning_rate']}</td>
                                    <td>{$row['epochs']}</td>
                                    <td>{$row['patience']}</td>
                                    <td>{$row['monitor']}</td>
                                    <td>{$row['optimizer']}</td>
                                    <td>{$row['activation_function']}</td>
                                    <td>{$row['validation_split']}</td>
                                    <td>{$row['test_split']}</td>
                                    <td><a href='result.php?directory_path={$row['directory_path']}&learning_rate={$row['learning_rate']}&epochs={$row['epochs']}&patience={$row['patience']}&monitor={$row['monitor']}&optimizer={$row['optimizer']}&activation_function={$row['activation_function']}&validation_split={$row['validation_split']}&test_split={$row['test_split']}' class='btn btn-primary'>Exécuter</a></td>
                                </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='10' class='text-center'>Aucun modèle trouvé.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <footer class="bg-dark text-white text-center py-3">
        <p>&copy; 2024 Projet web dynamique Classification d'Images</p>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
$conn->close();
?>

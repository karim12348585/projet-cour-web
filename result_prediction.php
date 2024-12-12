<?php
// Supposons que vous avez un dossier 'predictions' contenant les images de prédiction
$prediction_dir = 'result_pred/';

// Récupérer les fichiers d'images dans le dossier
$images = array_diff(scandir($prediction_dir), array('..', '.'));
 
// Filtrer uniquement les fichiers image
$image_files = array_filter($images, function($file) {
    return preg_match('/\.(jpg|jpeg|png|gif)$/i', $file);
});
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Résultats de Prédiction - Classification d'Images</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="html/result_prediction.css">
</head>
<body>
    <header class="text-white text-center py-4 shadow-lg">
        <h1><i class="fas fa-camera"></i> Résultats de Prédiction des Images</h1>
        <a href="logout.php" class="btn btn-disconnect position-absolute top-0 end-0 mt-3 me-4">Se Déconnecter</a>
    </header>

    <main class="container my-5">
        <section id="results" class="mb-5">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-gradient-primary text-white">
                    <h2><i class="fas fa-images"></i> Images de Prédiction</h2>
                </div>
                <div class="card-body">
                    <!-- Vérifier si des images sont présentes dans le dossier de prédiction -->
                    <?php if (count($image_files) > 0): ?>
                        <div class="row">
                            <?php foreach ($image_files as $image): ?>
                                <div class="col-md-4 mb-4">
                                    <div class="card">
                                        <img src="<?php echo $prediction_dir . $image; ?>" alt="Image de Prédiction" class="card-img-top">
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <p>Aucune image de prédiction disponible.</p>
                    <?php endif; ?>
                    
                    <!-- Bouton retour vers historique.php -->
                    <a href="historique.php" class="btn btn-secondary mt-4">
                        <i class="fas fa-history"></i> Retour à l'Historique
                    </a>
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

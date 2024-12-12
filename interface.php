<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Classification d'Images</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="html/styles.css">
</head>
<body>
    <header class="text-white text-center py-4 shadow-lg"> 
        <h1><i class="fas fa-camera"></i> Modèle de Classification d'Images</h1> 
        <a href="logout.php" class="btn btn-disconnect position-absolute top-0 end-0 mt-3 me-4">Se Déconnecter</a>
    </header>

    <main class="container my-5">
<!-- Sélection du répertoire d'images -->
        <section id="image-selection" class="mb-5">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-gradient-primary text-white">
                    <h2><i class="fas fa-folder-open"></i> Sélection du Répertoire d'Images</h2>
                </div>
                <div class="card-body">
                    <input type="file" id="directory-input" webkitdirectory directory multiple class="form-control mb-4">
                    <div id="image-preview" class="d-flex flex-wrap gap-4" style="max-height: 300px; overflow-y: auto;"></div> <!-- Prévisualisation des images avec scroll -->
                </div>
            </div>
        </section>

        <!-- Saisie des hyperparamètres -->
        <section id="hyperparameters">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-gradient-primary text-white">
                    <h2><i class="fas fa-sliders-h"></i> Saisie des Hyperparamètres</h2>
                </div>
                <div class="card-body">
                    <form id="hyperparameters-form" action="recuperation.php" method="POST" class="row g-4">
                        <!-- Ajouter un champ caché pour le chemin du répertoire -->
                        <input type="hidden" id="directory-path" name="directory_path">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Taux d'apprentissage</label>
                            <input type="number" step="0.0001" min="0.0001" max="1" name="learning_rate" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Nombre d'époques</label>
                            <input type="number" min="1" max="50" name="epochs" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Patience</label>
                            <select name="patience" class="form-select" required>
                                <option value="3">3</option>
                                <option value="5">5</option>
                                <option value="7">7</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Monitor</label>
                            <select name="monitor" class="form-select" required>
                                <option value="val_loss">val_loss</option>
                                <option value="val_accuracy">val_accuracy</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Optimiseur</label>
                            <select name="optimizer" class="form-select" required>
                                <option value="Adam">Adam</option>
                                <option value="SGD">SGD</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Fonction d'activation</label>
                            <select name="activation_function" class="form-select" required>
                                <option value="Sigmoid">Sigmoid</option>
                                <option value="ReLU">ReLU</option>
                                <option value="Tanh">Tanh</option>
                                <option value="Softmax">Softmax</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Validation Split</label>
                            <select name="validation_split" class="form-select" required>
                                <option value="0.1">0.1</option>
                                <option value="0.2">0.2</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Test Split</label>
                            <select name="test_split" class="form-select" required>
                                <option value="0.1">0.1</option>
                                <option value="0.2">0.2</option>
                            </select>
                        </div>
                        <div class="col-12 text-center">
                            <button type="submit" class="btn btn-gradient-primary"><i class="fas fa-play"></i> Lancer le Traitement</button>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </main>

    <footer class="bg-dark text-white text-center py-3">
        <p>&copy; 2024 Projet web dynamique Classification d'Images</p>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
document.getElementById('directory-input').addEventListener('change', (event) => {
    const files = event.target.files;
    if (files.length > 0) {
        const directoryPath = files[0].webkitRelativePath.split('/')[0]; // Modify this part if necessary
        document.getElementById('directory-path').value = directoryPath || '';
    }
});

        // Sélection et affichage des images avec une limite de 10 images maximum
        document.getElementById('directory-input').addEventListener('change', (event) => {
            const imagePreview = document.getElementById('image-preview');
            imagePreview.innerHTML = ''; // Réinitialise l'affichage
            const files = event.target.files;
        
            if (files.length === 0) {
                alert('Aucune image sélectionnée.');
                return;
            }
        
            // Limiter à 10 images
            const maxImages = 10;
            let imageCount = 0;

            Array.from(files).forEach((file) => {
                if (file.type.startsWith('image/')) {
                    if (imageCount < maxImages) {
                        const reader = new FileReader();
                        reader.onload = (e) => {
                            const img = document.createElement('img');
                            img.src = e.target.result;
                            img.style.maxWidth = '150px';
                            img.style.maxHeight = '150px';
                            img.style.objectFit = 'cover';
                            imagePreview.appendChild(img);
                            imageCount++;
                        };
                        reader.readAsDataURL(file);
                    }
                }
            });
        });
    </script>
</body>
</html>

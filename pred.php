<!DOCTYPE html>
<html lang="fr">
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
    <style>
        /* Global Styles */
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f4f7fc;
            color: #333;
            margin: 0;
        }

        /* Header */
        header {
            background: linear-gradient(90deg, #3b668a, #6bb2db);
            color: white;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        /* Section Cards */
        .card {
            border-radius: 10px;
            overflow: hidden;
        }

        .card-header {
            background: linear-gradient(90deg, #3b668a, #6bb2db);
            font-size: 1.25rem;
            font-weight: 500;
        }

        .card-body {
            padding: 2rem;
            background: #ffffff;
            border: 1px solid #e3e6f0;
        }

        /* Buttons */
        .btn-gradient-primary {
            background: linear-gradient(90deg, #3b668a, #6bb2db);
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 30px;
            transition: all 0.3s ease;
        }

        .btn-gradient-primary:hover {
            background: linear-gradient(90deg, #6bb2db, #3b668a);
            transform: scale(1.05);
        }

        /* Image Preview */
        #image-preview {
            display: flex;
            flex-wrap: wrap;
            gap: 4px;
            max-height: 300px;
            overflow-y: auto;
            padding-right: 10px;
        }

        #image-preview img {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border: 2px solid #ddd;
            border-radius: 8px;
            transition: transform 0.2s ease;
        }

        #image-preview img:hover {
            transform: scale(1.1);
        }

    </style>
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
                    <input type="file" id="directory-input" webkitdirectory directory multiple name="images[]" class="form-control mb-4">
                    <div id="image-preview" class="d-flex flex-wrap gap-4" style="max-height: 300px; overflow-y: auto;"></div> <!-- Prévisualisation des images -->
                </div>
            </div>
        </section>

        <!-- Formulaire pour envoyer le nom du dossier -->
        <section id="hyperparameters">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-gradient-primary text-white">
                    <h2><i class="fas fa-sliders-h"></i> Prediction</h2>
                </div>
                <div class="card-body">
                    <form id="hyperparameters-form" action="recuperation_pred.php" method="POST" class="row g-4">
                        <!-- Champ caché pour envoyer le nom du dossier -->
                        <input type="hidden" id="directory-name" name="directory-name">

                        <div class="col-12 text-center">
                            <button type="submit" class="btn btn-gradient-primary"><i class="fas fa-play"></i> Lancer le Traitement</button>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </main>



    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.getElementById('directory-input').addEventListener('change', (event) => {
            const files = event.target.files;
            if (files.length > 0) {
                // Récupérer le chemin relatif du premier fichier
                const relativePath = files[0].webkitRelativePath;

                // Extraire le nom du dossier
                const directoryName = relativePath.split('/')[0];

                // Placer le nom du dossier dans le champ caché
                document.getElementById('directory-name').value = directoryName;

                // Prévisualisation des images (maximum 10 images)
                const imagePreview = document.getElementById('image-preview');
                imagePreview.innerHTML = ''; // Réinitialiser la prévisualisation
                let imageCount = 0;

                Array.from(files).forEach((file) => {
                    if (file.type.startsWith('image/') && imageCount < 10) {
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
                });
            }
        });
    </script>
</body>
</html>

// Sélection et affichage des images
document.getElementById('directory-input').addEventListener('change', (event) => {
    const imagePreview = document.getElementById('image-preview');
    imagePreview.innerHTML = ''; // Réinitialise l'affichage
    const files = event.target.files;

    if (files.length === 0) {
        alert('Aucune image sélectionnée.');
        return;
    }

    Array.from(files).forEach((file) => {
        if (file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = (e) => {
                const img = document.createElement('img');
                img.src = e.target.result;
                imagePreview.appendChild(img);
            };
            reader.readAsDataURL(file);
        }
    });
});

// Validation et soumission du formulaire
document.getElementById('hyperparameters-form').addEventListener('submit', (event) => {
    event.preventDefault();

    const formData = new FormData(event.target);
    const data = Object.fromEntries(formData.entries());

    // Bootstrap alert
    alert(`
        Traitement lancé avec succès!
        Taux d'apprentissage: ${data.learning_rate}
        Époques: ${data.epochs}
    `);
});





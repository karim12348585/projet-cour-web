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




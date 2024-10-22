function changeColor(input) {
    var fileUploadInput = document.getElementById('file-upload-input-text');
    if (input.files && input.files[0]) {
        fileUploadInput.style.backgroundColor = '#D8FFCB'; // Cambia el color a verde
        fileUploadInput.textContent = input.files[0].name; // Muestra el nombre del archivo
    } else {
        fileUploadInput.style.backgroundColor = '#FF9898'; // Vuelve al color predeterminado
        fileUploadInput.textContent = 'Archivo no seleccionado';
    }
}
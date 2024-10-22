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

function cancelarEnvio() {
    var formulario = document.getElementById('form');
    formulario.addEventListener('submit', function (event) {
        event.preventDefault();
        var categoria = "<?php echo isset($_SESSION['categoria']) ? htmlspecialchars(urlencode($_SESSION['categoria'])) : ''; ?>";
        window.location.href = "../main.php?categoria=" + categoria;
        return false;
    });
}
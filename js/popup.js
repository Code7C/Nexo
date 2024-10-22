document.addEventListener('DOMContentLoaded', function() {
    const popup = document.getElementById('popup');
    const closeBtn = document.querySelector('.close-btn');
    const popupFrame = document.getElementById('popup-frame');

    // Función para abrir el pop-up
    function openPopup(url) {
        popupFrame.src = url;
        popup.style.display = 'block';
    }

    // Función para cerrar el pop-up
    function closePopup() {
        popup.style.display = 'none';
        popupFrame.src = '';
    }

    // Asignar evento de clic a los enlaces con la clase 'popup-link'
    document.querySelectorAll('.popup-link').forEach(function(link) {
        link.addEventListener('click', function(event) {
            event.preventDefault(); // Prevenir la navegación estándar
            openPopup(this.href);
        });
    });

    // Asignar evento de clic al botón de cerrar
    closeBtn.addEventListener('click', closePopup);

    // Cerrar el pop-up si el usuario hace clic fuera del contenido
    window.addEventListener('click', function(event) {
        if (event.target == popup) {
            closePopup();
        }
    });
});
window.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closePopup('popup');
        closePopup('popup2');
    }
});

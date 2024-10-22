function increaseQuantity(inputId) {
    let input = document.getElementById(inputId);
    if (input) {
        let currentValue = parseInt(input.value, 10);
        input.value = currentValue + 1;
    }
}

function decreaseQuantity(inputId) {
    let input = document.getElementById(inputId);
    if (input) {
        let currentValue = parseInt(input.value, 10);
        if (currentValue > 1) {
            input.value = currentValue - 1;
        }
    }
}



document.addEventListener('DOMContentLoaded', function () {
    // Selecciona todos los botones de menú
    const menuButtons = document.querySelectorAll('.menu-toggle');

    menuButtons.forEach(function (button) {
        button.addEventListener('click', function (event) {
            // Obtener el ID del menú asociado
            const targetMenu = document.getElementById(event.currentTarget.dataset.target);

            // Alternar la visibilidad del menú seleccionado
            if (targetMenu.style.display === 'block') {
                targetMenu.style.display = 'none';
            } else {
                // Cerrar cualquier otro menú abierto antes de abrir el nuevo
                closeAllMenus();
                targetMenu.style.display = 'block';
            }

            // Detener la propagación del evento para evitar que se cierre el menú inmediatamente
            event.stopPropagation();
        });
    });

    // Cerrar los menús al hacer clic fuera de ellos
    document.addEventListener('click', function (event) {
        closeAllMenus();
    });

    // Función para cerrar todos los menús desplegables
    function closeAllMenus() {
        document.querySelectorAll('.dropdown-menu').forEach(function (menu) {
            menu.style.display = 'none';
        });
    }
});

<?php
// Incluye el archivo para verificar si hay una sesión activa
include '../users/ifSession.php';
// Incluye el archivo para establecer la conexión a la base de datos
include '../conexion.php';

// Verificación de nombre de categoría en tiempo real
if (isset($_GET['checkName'])) {
    // Obtiene el nombre de la categoría desde la solicitud GET
    $name = $_GET['checkName'];

    // Consulta para seleccionar el ID de la categoría si el nombre ya existe
    $sql = "SELECT ID FROM categories WHERE CATEGORY_NAME = ?";
    // Prepara la consulta
    $stmt = $cnx->prepare($sql);
    // Vincula el parámetro de nombre a la consulta
    $stmt->bind_param("s", $name);
    // Ejecuta la consulta
    $stmt->execute();
    // Almacena el resultado para contar las filas
    $stmt->store_result();

    // Si existe una fila con ese nombre, devolver true
    if ($stmt->num_rows > 0) {
        echo "true"; // Nombre existe
    } else {
        echo "false"; // Nombre no existe
    }

    // Cierra la declaración
    $stmt->close();
    // Cierra la conexión a la base de datos
    mysqli_close($cnx);
    // Sale del script para que no procese el resto si es una solicitud AJAX
    exit(); 
}

// Mostrar el formulario para nueva categoría
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nueva Categoría</title>
    <style>
        /* Estilos para los elementos de entrada y botones */
        .input { padding: 5px; margin: 5px 0; }
        .button { margin: 5px; padding: 5px 10px; cursor: pointer; }
        .error-message { color: red; display: none; } // Mensaje de error oculto por defecto
    </style>
</head>
<body>
<div>
    <h1>Nueva Categoría</h1>
    <form class="form" method="POST" action="" onsubmit="return validarFormulario();" enctype="multipart/form-data">
        <table>
            <tr>
                <td>Nombre de la categoría:</td>
                <td>
                    <!-- Campo de texto para ingresar el nombre de la categoría -->
                    <input type="text" name="cat" id="cat" class="input" placeholder="Nombre de la Categoría" required onkeyup="checkCategoryName();">
                    <!-- Mensaje de error que se mostrará si el nombre ya existe -->
                    <span id="category-error" class="error-message">El nombre de la categoría ya existe.</span>
                </td>
            </tr>
            <tr>
                <td></td>
                <td>
                    <!-- Botón para enviar el formulario, inicialmente deshabilitado -->
                    <input type="submit" name="enviar" value="Guardar Categoría" id="submit-button" disabled>
                    <!-- Botón para cancelar y volver a la lista de categorías -->
                    <button type="button" class="button" onclick="location.href='listCategories.php'">Cancelar</button>
                </td>
            </tr>
        </table>
    </form>
</div>

<script>
// Función para verificar si el nombre de la categoría ya existe
function checkCategoryName() {
    // Obtiene el valor del campo de entrada de categoría
    var categoryName = document.getElementById('cat').value;
    // Obtiene el elemento del mensaje de error
    var categoryError = document.getElementById('category-error');
    // Obtiene el botón de envío
    var submitButton = document.getElementById('submit-button');

    // Restablecer el estado antes de la verificación
    categoryError.style.display = 'none';
    submitButton.disabled = true;

    // Si el campo está vacío, no hacer nada
    if (categoryName.trim() === '') {
        return;
    }

    // Realizar la solicitud AJAX para verificar si el nombre ya existe
    var xhr = new XMLHttpRequest();
    // Configura la solicitud para llamar al mismo archivo con el nombre de la categoría
    xhr.open('GET', 'newCategory.php?checkName=' + encodeURIComponent(categoryName), true);
    xhr.onreadystatechange = function () {
        // Cuando la solicitud ha finalizado
        if (xhr.readyState === 4 && xhr.status === 200) {
            // Si la respuesta es "true", el nombre ya existe
            if (xhr.responseText.trim() === 'true') {
                categoryError.style.display = 'inline'; // Mostrar mensaje de error
                submitButton.disabled = true; // Deshabilitar el botón de enviar
            } else {
                // Si la respuesta es "false", el nombre no existe
                categoryError.style.display = 'none'; // Ocultar mensaje de error
                submitButton.disabled = false; // Habilitar el botón de enviar
            }
        }
    };
    // Envía la solicitud
    xhr.send();
}

// Función para validar el formulario antes de enviarlo
function validarFormulario() {
    var categoryError = document.getElementById('category-error');
    // Prevenir el envío si hay un error (mensaje visible)
    return categoryError.style.display !== 'inline'; 
}
</script>

<?php
// Si el método de solicitud es POST, significa que se está enviando el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Procesar el formulario de nueva categoría
    $name = $_POST['cat']; // Obtiene el nombre de la categoría desde el formulario

    // Asegúrate de usar consultas preparadas aquí para evitar inyecciones SQL
    $sql = "INSERT INTO categories (CATEGORY_NAME) VALUES (?)"; // Consulta para insertar la nueva categoría
    // Prepara la consulta
    $stmt = $cnx->prepare($sql);
    // Vincula el parámetro de nombre a la consulta
    $stmt->bind_param("s", $name);

    // Ejecuta la consulta e imprime un mensaje según el resultado
    if ($stmt->execute()) {
        echo "<script>alert('Categoría guardada con éxito.'); window.location.href='listCategories.php';</script>";
    } else {
        // Muestra un mensaje de error si hay un problema al guardar la categoría
        echo "Error al guardar la categoría: " . mysqli_error($cnx);
    }
    
    // Cierra la declaración
    $stmt->close();
    // Cierra la conexión a la base de datos
    mysqli_close($cnx);
}
?>

</body>
</html>

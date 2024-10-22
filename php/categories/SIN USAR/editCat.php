<?php
//EXPLICACION
//PHP al inicio:

//La sección de PHP verifica si la solicitud tiene el parámetro checkName. Si está presente, se realiza una consulta a la base de datos para verificar si el nombre de la categoría ya existe.
//Si la solicitud es una verificación (checkName), el script responde con true o false y termina la ejecución con exit().

//Formulario de edición:
//Si no hay parámetro checkName, se muestra el formulario para editar la categoría, con los valores actuales cargados.

//JavaScript:
//checkCategoryName(): Envía el nombre ingresado al mismo archivo (editCat.php) usando AJAX. Si el nombre ya existe, muestra un //mensaje de error y deshabilita el botón de envío.
//validarFormulario(): Verifica si hay errores antes de enviar el formulario.


include '../users/ifSession.php';
include '../conexion.php';

// Verificación de nombre de categoría en tiempo real
if (isset($_GET['checkName'])) {
    $name = $_GET['checkName'];

    $sql = "SELECT ID FROM categories WHERE CATEGORY_NAME = ?";
    $stmt = $cnx->prepare($sql);
    $stmt->bind_param("s", $name);
    $stmt->execute();
    $stmt->store_result();

    // Si existe una fila con ese nombre, devolver true
    if ($stmt->num_rows > 0) {
        echo "true";
    } else {
        echo "false";
    }

    $stmt->close();
    mysqli_close($cnx);
    exit(); // Salir para que no procese el resto del script si es una solicitud AJAX
}

// Mostrar el formulario de edición
$id = htmlspecialchars($_REQUEST['id']);
$name = htmlspecialchars(urldecode($_REQUEST['name']));
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Categoría</title>
    <style>
        .input { padding: 5px; margin: 5px 0; }
        .button { margin: 5px; padding: 5px 10px; cursor: pointer; }
        .error-message { color: red; display: none; }
    </style>
</head>
<body>
<div>
    <h1>Editar Categoría: <?php echo $name; ?></h1>
    <form class="form" method="POST" action="editCat_DB.php" enctype="multipart/form-data" onsubmit="return validarFormulario();">
        <table>
            <tr>
                <td>Nombre de la categoría:</td>
                <td>
                    <input type="text" name="cat" id="cat" class="input" value="<?php echo $name; ?>" onkeyup="checkCategoryName();">
                    <span id="category-error" class="error-message">El nombre de la categoría ya existe.</span>
                </td>
            </tr>
            <tr>
                <input type="hidden" name="id" value="<?php echo $id; ?>">
                <input type="hidden" name="nameCat" value="<?php echo $name; ?>">
                <td></td>
                <td>
                    <input type="submit" name="enviar" value="Modificar" id="submit-button">
                    <button type="button" class="button" onclick="location.href='listCategories.php'">Cancelar</button>
                </td>
            </tr>
        </table>
    </form>
</div>

<script>
function checkCategoryName() {
    var categoryName = document.getElementById('cat').value;
    var categoryError = document.getElementById('category-error');
    var submitButton = document.getElementById('submit-button');

    if (categoryName.trim() === '') {
        categoryError.style.display = 'none';
        submitButton.disabled = false;
        return;
    }

    // Realizar la solicitud AJAX para verificar si el nombre ya existe
    var xhr = new XMLHttpRequest();
    xhr.open('GET', 'editCat.php?checkName=' + encodeURIComponent(categoryName), true);
    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4 && xhr.status == 200) {
            if (xhr.responseText.trim() === 'true') {
                // Si la respuesta es "true", el nombre ya existe
                categoryError.style.display = 'inline';
                submitButton.disabled = true; // Deshabilitar el botón de enviar
            } else {
                // Si la respuesta es "false", el nombre no existe
                categoryError.style.display = 'none';
                submitButton.disabled = false; // Habilitar el botón de enviar
            }
        }
    };
    xhr.send();
}

function validarFormulario() {
    var categoryError = document.getElementById('category-error');
    if (categoryError.style.display === 'inline') {
        return false; // Prevenir el envío si hay un error
    }
    return true;
}
</script>
</body>
</html>

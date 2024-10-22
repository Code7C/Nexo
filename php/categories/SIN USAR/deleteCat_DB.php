<?php include '../users/ifSession.php'?>
<?php
// Recepción de los datos
$id = $_REQUEST['cat'];
$name = $_REQUEST['nameCat'];

// Conexión a la BD
include '../conexion.php';

// Crear la consulta para obtener los productos con la categoría a eliminar
$sqlProductos = "SELECT * FROM categories WHERE ID = '$id'";
$resultadoProductos = mysqli_query($cnx, $sqlProductos);

// Verificar si hay productos con la categoría a eliminar
if (mysqli_num_rows($resultadoProductos) > 0) {
    // Cambiar la categoría de los productos a 'Ferreteria'
    $sqlActualizar = "UPDATE products SET CATEGORY = 'Prueba' WHERE CATEGORY = '$name'";
    mysqli_query($cnx, $sqlActualizar) or die("Error al intentar actualizar la categoría de los productos");
    echo "La categoría de los productos se actualizó correctamente.";
}

// Crear la consulta para eliminar la categoría
$sqlEliminar = "DELETE FROM categories WHERE ID = '$id'";

// Ejecutar la consulta
mysqli_query($cnx, $sqlEliminar) or die("Error al intentar eliminar la categoría");
echo "La categoría se eliminó con éxito.";

// Cerrar la conexión
mysqli_close($cnx);

// Redirigir a la página de nueva categoría
header("Location: listCategories.php");
?>
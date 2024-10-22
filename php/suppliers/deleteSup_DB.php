<?php include '../users/ifSession.php'?>
<?php
// Recepción de los datos
$d = $_REQUEST['prove'];

// Conexión a la BD
include '../conexion.php';

// Crear la consulta para eliminar la categoría
$sqlEliminar = "DELETE FROM suppliers WHERE COMPANY = '$d'";

// Ejecutar la consulta
mysqli_query($cnx, $sqlEliminar) or die("Error al intentar eliminar el proveedor");
echo "El proveedor se eliminó con éxito.";

// Cerrar la conexión
mysqli_close($cnx);

// Redirigir a la página de nueva categoría
header("Location: listSuppliers.php");
?>
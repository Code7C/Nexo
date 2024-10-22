<?php include '../users/ifSession.php'?>
<?php
//RECEPCION DE LOS DATOS
$d=$_REQUEST['id'];
//CONEXION A BD
include '../conexion.php';
//CREAR LA CONSULTA
$sql="DELETE FROM products WHERE ID='$d'";
//EJECUTAR LA CONSULTA
mysqli_query($cnx,$sql) or die("Error al intentar eliminar el producto");
//CERRAR LA CONEXION
mysqli_close($cnx);
echo "El producto se eliminó con éxito";
exit();
?>
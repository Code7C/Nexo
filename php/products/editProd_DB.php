<?php include '../users/ifSession.php'?>
<?php
$id = $_REQUEST['id'];
$prod = $_REQUEST['prod'];
$costo = floatval($_REQUEST['costo']);
$porc = floatval($_REQUEST['porc']);
$cantMin = $_REQUEST['cantMin'];
$prov = $_REQUEST['prov'];
$cod = $_REQUEST['cod'];
$categoria = $_REQUEST['categoria'];
$unidad = $_REQUEST['unidad'];

// Manejo de la imagen
if ($_FILES['img']['name']) {
    $archivo = $_FILES['img']['tmp_name'];
    $nombre = $_FILES['img']['name'];
    $ruta_subir = "images/" . $nombre;

    // Mover el archivo subido a la ubicación deseada
    if (move_uploaded_file($archivo, $ruta_subir)) {
        // Actualizar la imagen y la categoría en la base de datos
        include '../conexion.php';
        $sql = "UPDATE products SET PRODUCT_NAME='$prod', COST='$costo', PERCENT='$porc', CODE='$cod', SUPPLIER='$prov', IMAGE='$ruta_subir', UNIT='$unidad', CATEGORY='$categoria', MIN_QUANTITY='$cantMin' WHERE ID='$id'";
        mysqli_query($cnx, $sql) or die("Error al intentar actualizar producto: " . mysqli_error($cnx));
        echo "El producto se actualizó con éxito";
        mysqli_close($cnx);
        
        $categoria = isset($_SESSION['categoria']) ? urlencode($_SESSION['categoria']) : '';
        //header("location: newProduct.php");
        exit();
    } else {
        die("Error al subir el archivo de imagen.");
    }
} else {
    // Si no se cargó una nueva imagen, actualiza solo los otros campos sin tocar la imagen
    include '../conexion.php';
    $sql = "UPDATE products SET PRODUCT_NAME='$prod', COST='$costo', PERCENT='$porc', CODE='$cod', SUPPLIER='$prov', UNIT='$unidad', CATEGORY='$categoria', MIN_QUANTITY='$cantMin' WHERE ID='$id'";
    mysqli_query($cnx, $sql) or die("Error al intentar actualizar producto: " . mysqli_error($cnx));
    echo "El producto se actualizó con éxito";
    mysqli_close($cnx);
    
    $categoria = isset($_SESSION['categoria']) ? urlencode($_SESSION['categoria']) : '';
    //header("location: newProduct.php");
    exit();
}
?>

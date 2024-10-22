<?php include '../users/ifSession.php'?>
<?php
    $id = $_REQUEST['id'];
    $name = $_REQUEST['cat'];
    $nameCat = $_REQUEST['nameCat'];
    include '../conexion.php';
    echo "$name,
    $nameCat";
    $sqlProductos = "SELECT ID FROM categories WHERE ID = '$id'";
    $resultadoProductos = mysqli_query($cnx, $sqlProductos);
    // Verificar si hay productos con la categoría a modificar
    if (mysqli_num_rows($resultadoProductos) > 0) {
    // Cambiar la categoría de los productos a 'Ferreteria'
    $sqlActualizar = "UPDATE products SET CATEGORY = '$name' WHERE CATEGORY = '$nameCat'";
    mysqli_query($cnx, $sqlActualizar) or die("Error al intentar actualizar la categoría de los productos");
    echo "La categoría de los productos se actualizó correctamente.";
    }
    $sql = "UPDATE categories SET CATEGORY_NAME='$name' WHERE ID='$id'";
    mysqli_query($cnx, $sql) or die("Error al intentar actualizar producto: " . mysqli_error($cnx));
    echo "La categoría se actualizó con éxito";
    mysqli_close($cnx);
    header("location: listCategories.php");
    exit();

?>

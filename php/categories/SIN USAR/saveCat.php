<?php include '../users/ifSession.php'?>
<?php 
    $cat = $_REQUEST['cat'];
    include '../conexion.php'; 

    // Modificamos la consulta SQL para incluir la categoría
    $sql="INSERT INTO categories(CATEGORY_NAME) 
          VALUES('$cat')";
    
    mysqli_query($cnx,$sql) or die("Error al intentar guardar la nueva categoría");
    header('location:listCategories.php');
?>
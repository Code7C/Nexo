<?php include '../users/ifSession.php'?>
<?php 
    include '../conexion.php'; 

    $prod = $_REQUEST['prod'];
    $costo = floatval($_REQUEST['costo']);
    $prov = $_REQUEST['prov'];
    $porcentaje = floatval($_REQUEST['porc']);
    
    $ubi_img = $_FILES['img']['tmp_name'];
   
    $cod = $_REQUEST['cod'];
    $uni = $_REQUEST['unidad'];
    $cantMin = $_REQUEST['cantMin'];
    $categoria = $_REQUEST['categoria']; // Nueva línea para obtener la categoría
    $ind = $_REQUEST['ind'];
    if ($_FILES['img']['name'] <> ""){
      $imagen = $_FILES['img']['name'];
      $img = 'images/'.$imagen;
      copy($ubi_img, 'images/'.$imagen);  
      $sql="INSERT INTO products(PRODUCT_NAME, COST, IMAGE, PERCENT, CODE, SUPPLIER, UNIT, CATEGORY, MIN_QUANTITY)
          VALUES('$prod','$costo','$img','$porcentaje','$cod','$prov','$uni','$categoria','$cantMin')";
    }
    else {
      $sql="INSERT INTO products(PRODUCT_NAME, COST, PERCENT, CODE, SUPPLIER, UNIT, CATEGORY, MIN_QUANTITY)
          VALUES('$prod','$costo','$porcentaje','$cod','$prov','$uni','$categoria','$cantMin')";
    
    }
    mysqli_query($cnx,$sql) or die("Error al intentar guardar el nuevo producto");
    mysqli_close($cnx);
    header("location:newProduct.php");
    exit();
?>
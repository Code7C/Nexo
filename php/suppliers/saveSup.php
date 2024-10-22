<?php include '../users/ifSession.php'?>
<?php 
    $prov = strtoupper($_REQUEST['prov']);
    $contc = $_REQUEST['cont'];
    $name = $_REQUEST['name'];
    $ind = $_REQUEST['ind'];
    include '../conexion.php'; 

    $sql="INSERT INTO suppliers(COMPANY, NAME, CONTACT)
          VALUES('$prov','$name','$contc')";
    
    mysqli_query($cnx,$sql) or die("Error al intentar guardar el nuevo proveedor");
    
    header('Location: listSuppliers.php?ind=' . urlencode($ind));
    exit();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Proveedores Disponiblesr</title>
    <link rel="stylesheet" href="../../css/suppliers/listSuppliers.css">
    <script src="../../js/suppliers/newSupplier.js"></script>
</head>
<body>
    <div>
        <h1 class="proveedores-title">Proveedores Disponibles</h1>
        <ul class="lista-proveedores">
            <?php
            include '../conexion.php';
            $sqlProveedores = "SELECT ID, COMPANY FROM suppliers";
            $resultadoProveedores = mysqli_query($cnx, $sqlProveedores);

            while ($filaProveedores = mysqli_fetch_assoc($resultadoProveedores)) {
                echo '<li class="proveedor-item"> 
                        <a href="supplier.php?prov=' . $filaProveedores['ID'] . '" class="proveedor-link">' . $filaProveedores['COMPANY'] . '</a>
                      </li>';
            }

            mysqli_close($cnx);
            ?>
        </ul>
    </div>
</body>
</html>
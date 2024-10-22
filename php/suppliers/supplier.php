<?php include '../users/ifSession.php'?>
<div>
    <ul class="listaProveedores">
        <?php
        // Consulta para obtener los proveedores
        include '../conexion.php';
        $prov = $_REQUEST['prov'];
        $sqlProveedores = "SELECT * FROM suppliers WHERE ID = '$prov'";
        $resultadoProveedores = mysqli_query($cnx, $sqlProveedores);

        // Imprime la lista de proveedores
        $sup = ''; // Inicializa $sup
        while ($filaProveedores = mysqli_fetch_assoc($resultadoProveedores)) {
            echo '<li class="proveedorItem"> ' . htmlspecialchars($filaProveedores['COMPANY']) . '</li>';
            echo '<li class="proveedorItem"> ' . htmlspecialchars($filaProveedores['NAME']) . '</li>';
            echo '<li class="proveedorItem"> ' . htmlspecialchars($filaProveedores['CONTACT']) . '</li>';

            $sup = htmlspecialchars($filaProveedores['COMPANY']); // Asigna el valor de $sup
        }

        // Cierra la conexión
        mysqli_close($cnx);
        ?>
    </ul>
    <!-- Formulario para cargar archivo Excel -->
    <form action="update_products.php" method="post" enctype="multipart/form-data">
        <h1>Actualizar productos</h1>
        <label for="excelFile2">Subir archivo Excel:</label>
        <br><input type="file" name="excelFile2" id="excelFile2" accept=".xlsx, .xls" required>
        <br><br><input type="submit" value="Subir Archivo" name="submit">
        <input type="hidden" name="supplier" value="<?php echo $sup; ?>">
    </form><br><br>
     <form action="load_products.php" method="post" enctype="multipart/form-data">
        <h1>Cargar productos    </h1>
        <label for="excelFile">Subir archivo Excel:</label>
        <br><input type="file" name="excelFile" id="excelFile" accept=".xlsx, .xls" required>
        <br><br><input type="submit" value="Subir Archivo" name="submit">
        <input type="hidden" name="supplier" value="<?php echo $sup; ?>"> 
    </form>
    </div>
</div>
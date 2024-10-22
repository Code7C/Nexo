<?php include '../users/ifSession.php'?>
<div>
    <div class="img-container">
        <img class="product-img" id="product-img" src="<?php echo $_REQUEST['img']; ?>" alt="<?php echo $_REQUEST['prod']; ?>">
    </div>
    <h1><?php echo $_REQUEST['prod']; ?></h1>
    <form class="form" id="form" method="POST" action="editProd_DB.php" enctype="multipart/form-data">
        <table>
            <tr>
                <td>Nombre del producto:</td>
                <td><input type="text" name="prod" class="input" value="<?php echo urldecode($_REQUEST['prod']); ?>"></td>
            </tr>
            <tr>
                <td>Código:</td>
                <td><input type="text" name="cod" class="input" value="<?php echo urldecode($_REQUEST['cod']); ?>"></td>
            </tr>
            <tr>
                <td>Precio Costo: $</td>
                <td><input type="text" name="costo" class="input" value="<?php echo $_REQUEST['costo']; ?>"></td>
            </tr>
            <tr>
                <td>Porcentaje de ganancia: %</td>
                <td><input type="text" name="porc" class="input" value="<?php echo $_REQUEST['porc']; ?>"></td>
            </tr>
            <tr>
                <td>Cantidad Mínima</td>
                <td><input type="number" name="cantMin" class="input" value="<?php echo $_REQUEST['cantMin']; ?>"></td>
                <input type="hidden" name="id" class="input" value="<?php echo $_REQUEST['id']; ?>"></td>
            </tr>
            <tr>
                <td>Unidad:</td>
                <td>
                    <select name="unidad">
                        <?php
                        $unidades = array("Unidad", "Metro", "Kilo", "Litro"); // Agrega más unidades según sea necesario
                        foreach ($unidades as $unidad) {
                            echo '<option value="' . $unidad . '"';

                            // Verifica si la unidad actual coincide con la almacenada en la base de datos
                            if ($unidad == $_REQUEST['uni']) {
                                echo ' selected="selected"';
                            }
                            echo '>' . $unidad . '</option>';
                        }
                        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Proveedor:</td>
                <td>
                    <select name="prov">
                        <?php
                        // Incluye el archivo de conexión a la base de datos
                        include '../conexion.php';

                        // Realiza la consulta SQL para obtener los proveedores
                        $sqlProveedores = "SELECT COMPANY FROM suppliers";
                        $resultadoProveedores = mysqli_query($cnx, $sqlProveedores);

                        // Verifica si la consulta se ejecutó correctamente
                        if (!$resultadoProveedores) {
                            die("Error en la consulta: " . mysqli_error($cnx));
                        }

                        // Obtiene el proveedor seleccionado (si existe)
                        $proveedorSeleccionado = isset($_REQUEST['prov']) ? $_REQUEST['prov'] : '';

                        // Itera sobre los resultados y construye las opciones del menú desplegable
                        while ($filaProveedor = mysqli_fetch_assoc($resultadoProveedores)) {
                            echo '<option value="' . $filaProveedor['COMPANY'] . '"';

                            // Verifica si el proveedor actual coincide con el almacenado en la base de datos
                            if ($filaProveedor['COMPANY'] == $proveedorSeleccionado) {
                                echo ' selected="selected"';
                            }
                            echo '>' . $filaProveedor['COMPANY'] . '</option>';
                        }
                        // Cierra la conexión
                        mysqli_close($cnx);
                        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Categoría:</td>
                <td>
                    <select name="categoria">
                        <?php
                       
                        // Incluye el archivo de conexión a la base de datos
                        include '../conexion.php';

                        // Realiza la consulta SQL para obtener las categorías
                        $sqlCategorias = "SELECT CATEGORY_NAME FROM categories";
                        $resultadoCategorias = mysqli_query($cnx, $sqlCategorias);

                        // Verifica si la consulta se ejecutó correctamente
                        if (!$resultadoCategorias) {
                            die("Error en la consulta: " . mysqli_error($cnx));
                        }

                        // Obtiene la categoría seleccionada (si existe)
                        $categoriaSeleccionada = isset($_SESSION['categoria']) ? $_SESSION['categoria'] : '';

                        // Itera sobre los resultados y construye las opciones del menú desplegable
                        while ($filaCategoria = mysqli_fetch_assoc($resultadoCategorias)) {
                            echo '<option value="' . $filaCategoria['CATEGORY_NAME'] . '"';

                            // Verifica si la categoría actual coincide con la almacenada en la base de datos
                            if ($filaCategoria['CATEGORY_NAME'] == $categoriaSeleccionada) {
                                echo ' selected="selected"';
                            }
                            echo '>' . $filaCategoria['CATEGORY_NAME'] . '</option>';
                        }
                        // Cierra la conexión
                        mysqli_close($cnx);
                        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Imágen:</td>
                <td>
                    <div class="file-upload">
                        <label for="img-upload" class="file-upload-btn">Seleccionar Archivo</label>
                        <input class="img-upload" id="img-upload" type="file" name="img" onchange="changeColor(this);">
                        <div class="file-upload-input" id="file-upload-input-text">Archivo no seleccionado</div>
                    </div>
                </td>
            </tr>
            <tr>
                <td></td>
                <td>
                    <input type="submit" name="enviar" value="Modificar">
                    <button type="button" onclick="cancelarEnvio()">Cancelar</button>
                </td>
            </tr>
        </table>
    </form>
</div>
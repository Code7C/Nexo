<?php include '../users/ifSession.php'?>
<div>
    <script type="text/javascript" src="../js/popup.js"></script>
    <h1 id="title">NUEVO PRODUCTO</h1>
    <div class="container">
        <form method="POST" action="saveProd.php" enctype="multipart/form-data">
            <table id='tabla'>
                <tr>
                    <td>
                        <input type="text" name="prod" class="input" placeholder="Nombre del Producto" required>
                    </td>   
                </tr>
                <tr>
                    <td>
                        <input type="text" name="cod" class="input" placeholder="Código" required>
                    </td>   
                </tr>
                <tr>
                    <td>
                        <input type="text" name="costo" class="input" placeholder="Precio Costo" required>
                    </td>   
                </tr>
                <tr>
                    <td>
                        <input type="text" name="porc" class="input" placeholder="Porcentaje de Ganancia" required>
                    </td>   
                </tr>
                <tr>
                    <td>
                        <input type="number" name="cantMin" class="input" placeholder="Cantidad Mínima" required>
                    </td>   
                </tr>
                <tr>
                    <td>
                        <select name="unidad">
                            <option>Unidad</option>
                            <option>Metro</option>
                            <option>Metro Cuadrado</option>
                            <option>Metro Cúbico</option>
                            <option>Kilo</option>
                            <option>Litro</option>
                        </select>
                    </td>   
                </tr>
                <tr>
                    <td>
                        <select name="prov">
                    <?php  // Incluye el archivo de conexión a la base de datos
                    include '../conexion.php';

                    // Realiza la consulta SQL para obtener las categorías
                    $sqlProveedores = "SELECT COMPANY FROM suppliers";
                    $resultadoProveedores = mysqli_query($cnx, $sqlProveedores);

                    // Verifica si la consulta se ejecutó correctamente
                    if (!$resultadoProveedores) {
                        die("Error en la consulta: " . mysqli_error($cnx));
                    }

                    // Obtiene la categoría seleccionada (si existe)
                    $proveedorSeleccionado = isset($_REQUEST['prov']) ? $_REQUEST['prov'] : '';

                    // Itera sobre los resultados y construye las opciones del menú desplegable
                    while ($filaProveedor = mysqli_fetch_assoc($resultadoProveedores)) {
                        // Imprime información para debug
                        echo 'Proveedor Seleccionado: ' . $proveedorSeleccionado . '<br>';
                        echo 'Proveedor Actual: ' . $filaProveedor['COMPANY'] . '<br>';

                        // Imprime la opción en el menú desplegable
                        echo '<option value="' . $filaProveedor['COMPANY'] . '"';

                        // Verifica si la categoría actual coincide con la almacenada en la base de datos
                        if ($filaProveedor['COMPANY'] == $proveedorSeleccionado) {
                            echo ' selected="selected"';
                        }

                        echo '>' . $filaProveedor['COMPANY']  . '</option>';
                        }
                        // Cierra la conexión
                        mysqli_close($cnx);
                        ?>                    
                    </select>
                    </td>
                </tr>
                <tr>
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

                            // Itera sobre los resultados y construye las opciones del menú desplegable
                            while ($filaCategoria = mysqli_fetch_array($resultadoCategorias)) {
                                echo '<option value="' . $filaCategoria['CATEGORY_NAME'] . '">' . $filaCategoria['CATEGORY_NAME'] . '</option>';

                            }

                            // Cierra la conexión
                            mysqli_close($cnx);
                            ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="file-upload">
                            <label for="img-upload" class="file-upload-btn">Seleccionar Archivo</label>
                            <input class='img-upload' id="img-upload" type="file" name="img" onchange="changeColor(this);">
                            <div class="file-upload-input" id="file-upload-input-text">Archivo no seleccionado</div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td id="load_products">
                        <a href="products_load.php" class="popup-link">
                            <img src="#" alt="Cargar productos desde Excel">
                        </a>
                    </td>
                </tr>
                <tr>
                    <td id="boton">
                        <br>
                        <input type="submit" name="enviar" value="Guardar Producto">
                    </td>
                </tr>
            </table>    
            <button  class="button" onclick="location.href='XXXXX.php'">Cancelar</button>
        </form>
    </div>
</div>
<?php include '../users/ifSession.php'; ?>
<div>
    <button onclick="openPopup('addSupplierPopup')">Agregar Proveedor</button>
    <table>
        <thead>
            <tr>
                <th>Nombre de la Empresa</th>
                <th>Nombre del Representante</th>
                <th>Contacto</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php
            include '../conexion.php';
            $sqlProveedores = "SELECT * FROM suppliers";
            $resultadoProveedores = mysqli_query($cnx, $sqlProveedores);

            // Verificar si la consulta se ejecutó correctamente
            if (!$resultadoProveedores) {
                die("Error en la consulta: " . mysqli_error($cnx));
            }

            // Comprobar si $resultadoProveedores tiene filas
            if (mysqli_num_rows($resultadoProveedores) > 0) {
                while ($filaProveedores = mysqli_fetch_assoc($resultadoProveedores)) {
                    echo '<tr class="proveedor-item"> 
                <td>' . htmlspecialchars($filaProveedores['COMPANY']) . '</td>
                <td>' . htmlspecialchars($filaProveedores['NAME']) . '</td>
                <td>' . htmlspecialchars($filaProveedores['CONTACT']) . '</td>
                <td>
                    <button onclick="openPopup(\'editSupplierPopup' . $filaProveedores['ID'] . '\')">Editar</button>
                    <form method="POST" action="" style="display:inline;">
                        <input type="hidden" name="supplier_id" value="' . htmlspecialchars($filaProveedores['ID']) . '">
                        <button type="submit" name="delete_supplier" onclick="return confirmDelete(\'Eliminar Proveedor?\')">Eliminar</button>
                    </form>
                    </td>
                </tr>';


                  // Popup para editar el proveedor
                    echo '<div class="popup" id="editSupplierPopup' . $filaProveedores['ID'] . '">
                    <div class="popup-content">
                        <button class="close-btn" onclick="closePopup(\'editSupplierPopup' . $filaProveedores['ID'] . '\')">X</button>
                        <h2>Editar Proveedor</h2>
                        <form method="POST" action="">
                            <input type="hidden" name="supplier_id" value="' . htmlspecialchars($filaProveedores['ID']) . '">
                            <label for="supplier_name">Nombre de la Empresa:</label>
                            <input type="text" name="supplier_name" value="' . htmlspecialchars($filaProveedores['COMPANY']) . '" required>
                            <label for="supplier_represent">Nombre del Representante:</label>
                            <input type="text" name="supplier_represent" value="' . htmlspecialchars($filaProveedores['NAME']) . '" required>
                            <label for="supplier_contact">Contacto:</label>
                            <input type="text" name="supplier_contact" value="' . htmlspecialchars($filaProveedores['CONTACT']) . '" required>
                            <button type="submit" name="edit_supplier">Guardar Cambios</button>
                        </form>
                    </div>
                    </div>';


                }
            } else {
                echo "<tr><td colspan='2'>No hay proveedores disponibles.</td></tr>";
            }

            mysqli_close($cnx);
            ?>
        </tbody>
    </table>
    
    <!-- Popup para agregar proveedor -->
<div class="popup" id="addSupplierPopup">
    <div class="popup-content">
        <button class="close-btn" onclick="closePopup('addSupplierPopup')">X</button>
        <h2>Agregar Proveedor</h2>
        <form method="POST" action="">
            <label for="new_supplier_name">Nombre de la Empresa:</label>
            <input type="text" name="new_supplier_name" required>
            <label for="new_supplier_represent">Nombre del Representante:</label>
            <input type="text" name="new_supplier_represent" required>
            <label for="new_supplier_contact">Contacto:</label>
            <input type="text" name="new_supplier_contact" required>
            <button type="submit" name="add_supplier">Guardar Proveedor</button>
        </form>
    </div>
</div>


</div>

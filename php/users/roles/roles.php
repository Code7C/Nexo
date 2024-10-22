<?php
include '../users/ifSession.php';
include 'permissions.php';
$mysqli = $cnx;
$query = "SELECT * FROM roles"; 
$result_roles = $mysqli->query($query);

// Verificar si la consulta se ejecutó correctamente
if (!$result_roles) {
    die("Error en la consulta: " . $mysqli->error);
}

// Mostrar los roles
echo "<button onclick=\"openPopup('addRolePopup')\">Agregar Rol</button>";

echo "<table>";
echo "<thead>";
echo "<tr><th>Nombre del Rol</th><th>Acciones</th></tr>";
echo "</thead>";
echo "<tbody>";

// Comprobar si $result_roles tiene filas
if ($result_roles->num_rows > 0) {
    while ($role = $result_roles->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($role['ROLE_NAME']) . "</td>"; // Sanitizar salida
        echo "<td>
                <button onclick=\"openPopup('editRolePopup{$role['ID']}')\">Editar</button>
                <form method=\"POST\" action=\"\" style=\"display:inline;\">
                    <input type=\"hidden\" name=\"role_id\" value=\"" . htmlspecialchars($role['ID']) . "\">
                    <button type=\"submit\" name=\"delete_role\" onclick=\"return confirmDelete('Eliminar Rol')\">Eliminar</button>
                </form>
              </td>";
        echo "</tr>";

        // Popup para editar el rol
        echo "<div class=\"popup\" id=\"editRolePopup{$role['ID']}\">
        <div class=\"popup-content\">
            <button class=\"close-btn\" onclick=\"closePopup('editRolePopup{$role['ID']}')\">X</button>
            <h2>Editar Rol</h2>
            <form method=\"POST\" action=\"\">
                <input type=\"hidden\" name=\"role_id\" value=\"" . htmlspecialchars($role['ID']) . "\">
                <label for=\"edit_role_name\">Nombre del Rol:</label>
                <input type=\"text\" name=\"edit_role_name\" value=\"" . htmlspecialchars($role['ROLE_NAME']) . "\" required>
                
                <!-- Aquí llamamos a la tabla de permisos -->
                <label>Permisos:</label>";
                
                // Obtener permisos actuales del rol
                $current_permissions = [];
                $permissions_query = "SELECT * FROM roles WHERE ID = {$role['ID']}";
                $permissions_result = $mysqli->query($permissions_query);
                
                if ($permissions_result && $permissions_result->num_rows > 0) {
                    $permissions_row = $permissions_result->fetch_assoc();
                    foreach ($permissions as $perm => $value) {
                        $current_permissions[$perm] = (bool)$permissions_row[$perm];
                    }
                }
                
                include 'permissions_table.php';  // Se incluirá la tabla de permisos aquí

                echo "<td><button type=\"submit\" name=\"edit_role\">Guardar Cambios</button></td>
                </form>
                </div>
                </div>";
    }
} else {
    echo "<tr><td colspan='3'>No hay roles disponibles.</td></tr>";
}

echo "</tbody>";
echo "</table>";

// Popup para agregar rol
echo '<div class="popup" id="addRolePopup">
        <div class="popup-content">
            <button class="close-btn" onclick="closePopup(\'addRolePopup\')">X</button>
            <h2>Agregar Rol</h2>
            <form method="POST" action="admin.php">
                <label for="new_role_name">Nombre del Rol:</label>
                <input type="text" name="new_role_name" required>
                <label>Permisos:</label>
                <table>';

                // Inicializar permisos seleccionados para el nuevo rol
                $current_permissions = array_fill_keys(array_keys($permissions), false);
                include 'permissions_table.php';

echo '</table><input type="submit" value="Guardar Rol">
    </form>
    </div>
    </div>';


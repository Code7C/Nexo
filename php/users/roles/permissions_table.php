<?php
include 'permissions.php';

// Inicializar el contador
$counter = 0;

// Comenzar la fila de la tabla
echo '<th>Permisos</th><tr>';

foreach ($permissions as $column => $value) {
    // Obtener el nombre amigable si existe, si no, usar el nombre original
    $label = isset($permissionLabels[$column]) ? $permissionLabels[$column] : $column;
    
    // Determinar si el permiso está habilitado
    $checked = isset($current_permissions[$column]) && $current_permissions[$column] ? 'checked' : '';
    
    echo '<td>' . htmlspecialchars($label) . '</td>';
    echo '<td><input type="checkbox" name="permissions[]" value="' . htmlspecialchars($column) . '" ' . $checked . '></td>';
    
    // Incrementar el contador
    $counter++;

    // Cada vez que el contador llega a 3, cerrar la fila y comenzar una nueva
    if ($counter % 3 == 0) {
        echo '</tr><tr>';
    }
}

// Cerrar la última fila si no se ha cerrado
if ($counter % 3 != 0) {
    echo '</tr>';
}
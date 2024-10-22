<?php
function hasPermission($permission) {
    // Verificar si el array 'permissions' está presente en la sesión y si el permiso específico existe
    if (isset($_SESSION['permissions']) && isset($_SESSION['permissions'][$permission])) {
        // Retornar true si el permiso es 1 (activo)
        return $_SESSION['permissions'][$permission] == 1;
    }
    return false; // Retornar false si el permiso no está activo o no existe
}


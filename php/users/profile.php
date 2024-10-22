<?php 
session_start();

// Incluir el archivo de conexión a la base de datos
include '../conexion.php';
include 'ifSession.php';

// Asegúrate de incluir correctamente el archivo con los permisos
include 'roles/permissions.php'; 

// Verifica que la variable $permissionLabels está definida
if (!isset($permissionLabels)) {
    die("El archivo de permisos no se ha incluido correctamente.");
}

// Obtener información del usuario logueado y el nombre del rol
$id = $_SESSION['id'];
$sql = "SELECT users.*, roles.ROLE_NAME, roles.VIEW_CATEGORIES, roles.EDIT_CATEGORIES, 
        roles.VIEW_SUPPLIERS, roles.EDIT_SUPPLIERS, roles.VIEW_COMPLETE_PRODUCTS, 
        roles.EDIT_PRODUCTS, roles.VIEW_RECORDS, roles.EDIT_RECORDS, roles.VIEW_USERS, 
        roles.EDIT_USERS, roles.EDIT_ACCESS, roles.CUSTOM, roles.ACCESS_CART, 
        roles.ACCESS_ADMINISTRATION, roles.ACCESS_ORDERS, roles.ACCESS_STATISTICS,roles.VIEW_PASSWORD 
        FROM users 
        JOIN roles ON users.ROLE_ID = roles.ID 
        WHERE users.ID = '$id'";

$resultado = mysqli_query($cnx, $sql);

if ($resultado && mysqli_num_rows($resultado) > 0) {
    $usuario = mysqli_fetch_assoc($resultado);
} else {
    echo "No se encontró la información del usuario.";
    exit();
}

mysqli_close($cnx);

// Validación para verificar si tiene algún permiso activo
$hasPermissions = false;
foreach ($permissionLabels as $permissionKey => $permissionLabel) {
    if ($usuario[$permissionKey]) {
        $hasPermissions = true;
        break; // No es necesario seguir si ya sabemos que tiene al menos un permiso
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Perfil de Usuario</title>
    <link rel="stylesheet" type="text/css" href="../css/styles.css">
    <link rel="stylesheet" type="text/css" href="../css/main.css">
    <script type="text/javascript" src="../js/main.js"></script>
    <style>
    /* Estilos generales */
    /* Estilos para la navegación */
    nav {
        background-color: #333; /* Color de fondo de la barra de navegación */
        padding: 10px; /* Espaciado interno */
        border-radius: 5px; /* Bordes redondeados */
        z-index: 1000;
    }

    nav ul {
        list-style-type: none; /* Elimina las viñetas de la lista */
        margin: 0; /* Elimina márgenes */
        padding: 0; /* Elimina relleno */
        display: flex; /* Flexbox para disposición horizontal */
    }

    nav ul li {
        margin-right: 20px; /* Espacio entre los elementos de la lista */
    }

    nav a {
        color: white; /* Color de texto en blanco */
        text-decoration: none; /* Sin subrayado en enlaces */
    }

    nav a:hover {
        text-decoration: underline; /* Subrayado al pasar el mouse */
    }

    body {
        font-family: Arial, sans-serif; /* Fuente básica */
        background-color: #f4f4f4; /* Color de fondo suave */
        margin: 0; /* Elimina márgenes */
        padding: 20px; /* Espaciado interno */
    }

    /* Contenedor del perfil */
    .profile-container {
        max-width: 600px; /* Ancho máximo del contenedor */
        margin: 0 auto; /* Centrar horizontalmente */
        background: #fff; /* Fondo blanco para el perfil */
        padding: 20px; /* Espaciado interno */
        border-radius: 8px; /* Bordes redondeados */
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); /* Sombra suave */
    }

    /* Títulos del perfil */
    .profile-container h1 {
        text-align: center; /* Centrar el título */
        color: #555; /* Color del título */
    }

    /* Estilos de la información del perfil */
    .profile-info p {
        margin: 10px 0; /* Espacio entre párrafos */
        color: #555; /* Color de texto */
    }

    /* Estilo de la lista de permisos */
    .profile-info ul {
        padding-left: 20px; /* Relleno a la izquierda para la lista */
    }

    .profile-info ul li {
        color: #28a745; /* Color verde para los permisos activos */
        margin-bottom: 5px; /* Espacio debajo de cada permiso */
    }

    /* Estilo para botones */
    .btn {
        width: 50%; /* Ancho completo */
        padding: 10px; /* Espaciado interno */
        background-color: #5cb85c; /* Color de fondo verde */
        color: white; /* Color del texto en blanco */
        border: none; /* Sin borde */
        border-radius: 4px; /* Bordes redondeados */
        font-size: 16px; /* Tamaño de fuente */
        cursor: pointer; /* Cambia el cursor al pasar el mouse */
        transition: background-color 0.3s; /* Transición suave para el fondo */
        text-align: center; /* Centrar texto */
        margin-top: 10px; /* Margen superior */
    }

    .btn:hover {
        background-color: #4cae4c; /* Color más oscuro al pasar el mouse */
    }

    /* Estilo para mensajes de error */
    .error-message {
        color: red; /* Color rojo para mensajes de error */
        text-align: center; /* Centrar el texto */
        margin-top: 10px; /* Espacio superior */
    }
    
    .li {
        color: color: #000000; /* Color de texto */
    }

    </style>
</head>
<body>

<nav>
    <ul>
        <li><a href="../main.php">Volver atrás</a></li>
        <li><a href="logout.php">Cerrar Sesión</a></li>
    </ul>
</nav>

<div class="profile-container">
    <h1>Perfil del Usuario</h1>
    <div class="profile-info">
        <p><strong>Nombre:</strong> <?php echo htmlspecialchars($usuario['NAME_USER']) ." " . htmlspecialchars($usuario['LASTNAME_USER']); ?></p>
        <p><strong>Email:</strong> <?php echo htmlspecialchars($usuario['EMAIL']); ?></p>
        <p><strong>Teléfono:</strong> <?php echo htmlspecialchars($usuario['CONTACT']); ?></p>
        <p><strong>Rol:</strong> <?php echo htmlspecialchars($usuario['ROLE_NAME']); ?></p>

        <?php if ($hasPermissions): ?>
            <h2>Permisos especiales:</h2>
            <ul>
                <?php
                // Recorre todos los permisos y verifica cuáles están activos para el usuario
                foreach ($permissionLabels as $permissionKey => $permissionLabel) {
                    if ($usuario[$permissionKey]) {
                        echo "<li>$permissionLabel</li>";
                    }
                }
                ?>
            </ul>
        <?php endif; ?>
    </div>
    <div class="profile-actions">
        <a href="editProfile.php" class="btn">Editar Perfil</a>
        <a href="changePassword.php" class="btn">Cambiar Contraseña</a>
    </div>
</div>

</body>
</html>

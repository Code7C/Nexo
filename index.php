<?php
session_start();
$_SESSION['categoria'] = null;
include 'php/conexion.php'; // Incluye el archivo de conexión a la base de datos

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email_r = $_POST['email'];
    $password_r = $_POST['password'];

    // Consulta segura con parámetros preparados para evitar SQL Injection
    $sql = "SELECT * FROM users WHERE email = ? AND password = ?";
    $stmt = mysqli_prepare($cnx, $sql);
    mysqli_stmt_bind_param($stmt, "ss", $email_r, $password_r); // "ss" significa que son dos strings
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($res) == 1) {
        // Si se encuentra un registro, obtenemos los datos del usuario
        $row = mysqli_fetch_assoc($res);

        // Verificar que el campo 'id' existe en $row
        if (!isset($row['ID'])) {
            die("Error: No se encontró el campo 'id' en la tabla users.");
        }

        $id = $row['ID']; // Obtenemos el id del usuario
        $_SESSION['role_id'] = $row['ROLE_ID']; // Asegúrate de que 'ROLE_ID' existe
        $_SESSION['email'] = $email_r;
        $_SESSION['id'] = $id; // Guardamos el id en la sesión
        
        // Consulta para obtener permisos basados en el rol
        $userId = $_SESSION['id'];
        
        // Debugging: Mostrar el ID del usuario
        if (!isset($userId)) {
            die("El ID del usuario no está definido en la sesión.");
        }

        $sql = "SELECT 
            r.ID AS Role_ID,
            r.ROLE_NAME,
            r.VIEW_CATEGORIES,
            r.EDIT_CATEGORIES,
            r.VIEW_SUPPLIERS,
            r.EDIT_SUPPLIERS,
            r.VIEW_COMPLETE_PRODUCTS,
            r.EDIT_PRODUCTS,
            r.VIEW_RECORDS,
            r.EDIT_RECORDS,
            r.VIEW_USERS,
            r.EDIT_USERS,
            r.EDIT_ACCESS,
            r.CUSTOM,
            r.ACCESS_CART,
            r.ACCESS_ADMINISTRATION,
            r.ACCESS_ORDERS,
            r.ACCESS_STATISTICS,
            r.VIEW_PASSWORD,
            r.VIEW_ROLES,
            r.EDIT_ROLES
        FROM 
            users u
        JOIN 
            roles r ON u.ROLE_ID = r.ID
        WHERE 
            u.ID = ?";

        $stmt = $cnx->prepare($sql);
        $stmt->bind_param("i", $userId); // Vincular el parámetro $userId como un entero
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $permissions = $result->fetch_assoc();
            $_SESSION['permissions'] = $permissions; // Guardar permisos en la sesión
        } else {
            echo 'No se encontraron permisos para este usuario.';
        }

        if ($_SESSION['role_id'] == 1) {
            // Redirigimos al usuario al archivo admin.php
            header('Location: php/users/admin.php');
        } else {
            // Redirigimos al usuario al archivo main.php
            header('Location: php/main.php');
        }
        
        exit();
    } else {
        echo "Correo o contraseña incorrectos.";
    }
    mysqli_close($cnx);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Datafusion</title>
    <link rel="stylesheet" type="text/css" href="index.css">
    <script src="js/index.js"></script>
</head>
<body>
    <div class="logo-container">
        <img src="resources/logo.png" alt="Logo">
    </div>

   <div class="container">
    <h1>Bienvenido a Datafusion</h1>
        <p>Por favor, inicie sesión o regístrese para continuar.</p>
        <!-- Formulario de Inicio de Sesión -->
        <form id="loginForm" action="index.php" method="POST">
            <div class="input-container">
                <input type="email" id="email" name="email" placeholder="Correo Electrónico" required>
            </div>
            <div class="input-container">
                <input type="password" id="password" name="password" placeholder="Contraseña" required>
            </div>
            <div class="button-container">
                <button type="submit">Ingresar</button>
            </div>
        </form>
        <br>
        <a href="php/users/register.php">Registrarse</a>
    </div>
</body>
</html>
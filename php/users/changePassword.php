<?php
session_start();
include '../conexion.php';
include 'ifSession.php';

// Asegúrate de que la sesión tenga un ID de usuario válido
if (!isset($_SESSION['id'])) {
    echo "Acceso no autorizado.";
    exit();
}

$userID = $_SESSION['id'];

// Verifica si los datos han sido enviados correctamente
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $current_password = mysqli_real_escape_string($cnx, $_POST['current_password']);
    $new_password = mysqli_real_escape_string($cnx, $_POST['new_password']);
    $confirm_password = mysqli_real_escape_string($cnx, $_POST['confirm_password']);

    // Verifica si las contraseñas nuevas coinciden
    if ($new_password !== $confirm_password) {
        $error_message = "Las nuevas contraseñas no coinciden.";
    } else {
        // Obtén la contraseña actual del usuario desde la base de datos
        $sql = "SELECT PASSWORD FROM users WHERE ID = $userID";
        $resultado = mysqli_query($cnx, $sql);

        if ($resultado && mysqli_num_rows($resultado) > 0) {
            $usuario = mysqli_fetch_assoc($resultado);
            // Verifica que la contraseña actual ingresada sea correcta
            if ($current_password == $usuario['PASSWORD']) {
                // Actualiza la contraseña en la base de datos
                $sql_update = "UPDATE users SET PASSWORD = '$new_password' WHERE ID = $userID";
                if (mysqli_query($cnx, $sql_update)) {
                    header('Location: profile.php');
                    exit(); // Asegúrate de salir después de la redirección
                } else {
                    $error_message = "Error al actualizar la contraseña: " . mysqli_error($cnx);
                }
            } else {
                $error_message = "La contraseña actual es incorrecta.";
            }
        } else {
            $error_message = "Error al obtener los datos del usuario.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Cambiar Contraseña</title>
    <link rel="stylesheet" type="text/css" href="../css/styles.css">
    <style>
    /* Estilos para la navegación */
        nav {
            background-color: #333; /* Color de fondo de la barra de navegación */
            padding: 10px; /* Espaciado interno */
            border-radius: 5px; /* Bordes redondeados */
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
        
        h1 {
            text-align: center;
            color: #555;
        }
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 400px;
            margin: 0 auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .form-label {
            display: block;
            margin: 10px 0 5px;
            font-weight: bold;
            color: #555;
        }
        .form-input {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
        }
        .form-submit {
            width: 100%;
            padding: 10px;
            background-color: #5cb85c;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .form-submit:hover {
            background-color: #4cae4c;
        }
        .error-message {
            color: red;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <a href="profile.php">Volver atrás</a>
        <h1>Cambiar Contraseña</h1>
        <form action="" method="post">
            <label class="form-label" for="current_password">Contraseña actual:</label>
            <input class="form-input" type="password" name="current_password" id="current_password" required>

            <label class="form-label" for="new_password">Nueva contraseña:</label>
            <input class="form-input" type="password" name="new_password" id="new_password" required>

            <label class="form-label" for="confirm_password">Confirmar nueva contraseña:</label>
            <input class="form-input" type="password" name="confirm_password" id="confirm_password" required>
            
            <input class="form-submit" type="submit" value="Cambiar Contraseña">
        </form>
        <?php if (isset($error_message)): ?>
            <div class="error-message"><?php echo $error_message; ?></div>
        <?php endif; ?>
    </div>
</body>
</html>

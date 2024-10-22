<?php
session_start();
include '../conexion.php'; // Asegúrate de que la ruta es correcta
include 'ifSession.php';

$userID = $_SESSION['id'];
// Aquí se ejecuta la consulta para obtener los datos del usuario
try {
    $sql = "SELECT * FROM users WHERE ID = $userID";
    $res = mysqli_query($cnx, $sql);

    if (!$res) {
        throw new Exception("Error en la consulta: " . mysqli_error($cnx));
    }

    $userData = mysqli_fetch_assoc($res); // Datos del usuario para mostrar en el formulario
} catch (Exception $e) {
    echo $e->getMessage();
    exit();
}

mysqli_close($cnx);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Perfil</title>
    <style>
     * {
    margin: 0; /* Elimina márgenes por defecto */
    padding: 0; /* Elimina rellenos por defecto */
    box-sizing: border-box; /* Incluye padding y borde en el tamaño total */
    }

    body {
        font-family: 'Arial', sans-serif; /* Fuente base */
        background-color: #f4f4f4; /* Color de fondo suave */
        display: flex; /* Usar flexbox para centrar el contenido */
        justify-content: center; /* Centrar horizontalmente */
        align-items: center; /* Centrar verticalmente */
        min-height: 100vh; /* Mínima altura del viewport */
    }

    .profile-container {
        background-color: #fff; /* Fondo blanco */
        padding: 30px; /* Espaciado interno */
        border-radius: 10px; /* Bordes redondeados */
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1); /* Sombra suave */
        width: 100%; /* Ancho completo */
        max-width: 400px; /* Ancho máximo del contenedor */
    }

    h1 {
        text-align: center; /* Centrar el título */
        margin-bottom: 20px; /* Margen inferior */
        font-size: 24px; /* Tamaño de fuente */
        color: #333; /* Color del texto */
    }

    form {
        display: flex; /* Usar flexbox en el formulario */
        flex-direction: column; /* Colocar los elementos en columna */
    }

    label {
        margin-bottom: 5px; /* Margen inferior para los labels */
        font-weight: bold; /* Negrita para los labels */
        color: #555; /* Color de los labels */
    }

    input[type="text"],
    input[type="email"],
    input[type="password"] {
        padding: 10px; /* Espaciado interno */
        margin-bottom: 15px; /* Margen inferior */
        border: 1px solid #ccc; /* Borde gris claro */
        border-radius: 5px; /* Bordes redondeados */
        font-size: 16px; /* Tamaño de fuente */
    }

    input[type="submit"],
    button {
        padding: 10px; /* Espaciado interno */
        background-color: #28a745; /* Color de fondo verde */
        color: #fff; /* Color del texto en blanco */
        border: none; /* Sin borde */
        border-radius: 5px; /* Bordes redondeados */
        font-size: 16px; /* Tamaño de fuente */
        cursor: pointer; /* Cambia el cursor al pasar el mouse */
        transition: background-color 0.3s; /* Transición suave para el fondo */
    }

    input[type="submit"]:hover,
    button:hover {
        background-color: #218838; /* Color más oscuro al pasar el mouse */
    }

    .error {
        color: red; /* Color rojo para mensajes de error */
        margin-bottom: 10px; /* Margen inferior */
    }

    .success {
        color: green; /* Color verde para mensajes de éxito */
        margin-bottom: 10px; /* Margen inferior */
    }

    </style>
</head>
<body>  
    <a href="profile.php">Volver atrás</a>
    <h1>Editar Perfil</h1><br>

    <form action="updateProfile.php" method="POST">
        <!-- Aquí irán los campos con los datos del usuario -->
        <label for="nombre">Nombre:</label>
        <input type="text" name="nombre" value="<?php echo htmlspecialchars($userData['NAME_USER']); ?>" required>

        <label for="nombre">Apellido:</label>
        <input type="text" name="apellido" value="<?php echo htmlspecialchars($userData['LASTNAME_USER']); ?>" required>

        <label for="email">Correo Electrónico:</label>
        <input type="email" name="email" value="<?php echo htmlspecialchars($userData['EMAIL']); ?>" required>

        <label for="nombre">Teléfono:</label>
        <input type="text" name="tel" value="<?php echo htmlspecialchars($userData['CONTACT']); ?>" required>

        <button type="submit">Guardar Cambios</button>
    </form>
</body>
</html>
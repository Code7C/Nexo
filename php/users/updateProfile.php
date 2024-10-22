<?php
session_start();
include '../conexion.php'; // Asegúrate de que la ruta es correcta

// Verifica si el usuario ha iniciado sesión y tiene un ID
if (isset($_SESSION['id'])) {
    $userID = $_SESSION['id'];
} else {
    // Si no hay ID de usuario, redirige al inicio de sesión
    echo "No se ha iniciado sesión. Redirigiendo...";
    header('Location: ../../index.php');
    exit();
}

// Verifica si los datos han sido enviados correctamente desde el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = mysqli_real_escape_string($cnx, $_POST['email']);
    $nombre = mysqli_real_escape_string($cnx, $_POST['nombre']);
    $apellido = mysqli_real_escape_string($cnx, $_POST['apellido']);
    $telefono = mysqli_real_escape_string($cnx, $_POST['tel']);

    // Verifica si se ha enviado una nueva contraseña
    if (!empty($_POST['password'])) {
        $password = mysqli_real_escape_string($cnx, password_hash($_POST['password'], PASSWORD_BCRYPT));
        $sql = "UPDATE users SET CONTACT = '$telefono', EMAIL = '$email', NAME_USER = '$nombre', LASTNAME_USER = '$apellido', PASSWORD = '$password' WHERE id = $userID";
    } else {
        // Si no se ha enviado una nueva contraseña, actualiza solo el email, nombre y apellido
        $sql = "UPDATE users SET CONTACT = '$telefono', EMAIL = '$email', NAME_USER = '$nombre', LASTNAME_USER = '$apellido' WHERE id = $userID";
    }

    // Ejecuta la consulta de actualización
    try {
        if (mysqli_query($cnx, $sql)) {
            echo "Perfil actualizado exitosamente.";
            header('Location: profile.php'); // Redirige a la página de perfil o a donde sea necesario
            exit();
        } else {
            throw new Exception("Error al actualizar el perfil: " . mysqli_error($cnx));
        }
    } catch (Exception $e) {
        echo $e->getMessage();
        exit();
    }
} else {
    // Si se intenta acceder a este script directamente sin enviar el formulario, redirige o muestra un error
    echo "Acceso no autorizado.";
    header('Location: profile.php');
    exit();
}

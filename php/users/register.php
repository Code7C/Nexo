<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $lastname = $_POST['lastname'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    include '../conexion.php';

    $sql = "INSERT INTO users (NAME_USER, LASTNAME_USER, EMAIL, PASSWORD) VALUES ('$username', '$lastname', '$email', '$password')";
    if (mysqli_query($cnx, $sql)) {
        $_SESSION['username'] = $username;
        header('Location: ../../index.php');
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($cnx);
    }
    mysqli_close($cnx);
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - Datafusion</title>
    <link rel="stylesheet" href="../../styles.css">
    <link rel="stylesheet" type="text/css" href="../../css/users/register.css">
</head>
<body>
    <div class="logo-container">
        <img src="../../resources/logo.png" alt="Logo">
    </div>

    <div class="container">
        <h1>Regístrate en Datafusion</h1>
        <form method="POST" action="register.php">
            <div class="input-container">
                <input type="text" name="username" placeholder="Nombre" required>
            </div>
            <div class="input-container">
                <input type="text" name="lastname" placeholder="Apellido" required>
            </div>
            <div class="input-container">
                <input type="email" name="email" placeholder="Correo Electrónico" required>
            </div>
            <div class="input-container">
                <input type="password" name="password" placeholder="Contraseña" required>
            </div>
            <div class="button-container">
                <button type="submit">Registrarse</button>
            </div>
        </form>
        <br>
        <div class="button-container">
            <a href="../../index.php">Volver al Inicio</a>
        </div>
    </div>
</body>
</html>

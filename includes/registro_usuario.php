<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $ficha = $_POST['ficha'];
    $contraseña = password_hash($_POST['contraseña'], PASSWORD_BCRYPT); // Encriptar contraseña

    // Insertar nuevo usuario con rol "aprendiz" por defecto
    $sql = "INSERT INTO usuarios (nombre, apellido, ficha, rol, contraseña) VALUES ('$nombre', '$apellido', '$ficha', 'aprendiz', '$contraseña')";

    if ($conn->query($sql) === TRUE) {
        // Redirigir al usuario a la página de inicio de sesión
        header("Location: ../index.php"); // Cambia "index.html" por la ruta correcta
        exit(); // Asegúrate de terminar la ejecución del script después de redirigir
    } else {
        echo "Error al registrar: " . $conn->error;
    }
}
?>
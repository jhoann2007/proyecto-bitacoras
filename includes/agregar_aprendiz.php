<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $ficha = $_POST['ficha'];
    $contraseña = password_hash('123456', PASSWORD_BCRYPT); // Contraseña por defecto

    // Insertar nuevo aprendiz
    $sql = "INSERT INTO usuarios (nombre, apellido, ficha, rol, contraseña) VALUES ('$nombre', '$apellido', '$ficha', 'aprendiz', '$contraseña')";
    if ($conn->query($sql) === TRUE) {
        echo "Aprendiz agregado correctamente.";
    } else {
        echo "Error al agregar aprendiz: " . $conn->error;
    }
}
?>


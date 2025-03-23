<?php
session_start();
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $contraseña = $_POST['contraseña'];

    // Validar el usuario en la base de datos
    $sql = "SELECT * FROM usuarios WHERE nombre = ? AND apellido = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("ss", $nombre, $apellido); // Evitar inyecciones SQL
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();

            // Verificar la contraseña
            if (password_verify($contraseña, $user['contraseña'])) {
                // Iniciar sesión y guardar datos en la sesión
                $_SESSION['id'] = $user['id']; // Guardar el ID del usuario
                $_SESSION['nombre'] = $user['nombre'];
                $_SESSION['rol'] = $user['rol'];

                // Redirigir según el rol
                if ($user['rol'] === 'instructor') {
                    header("Location: ../dashboard_instructor.php");
                } elseif ($user['rol'] === 'aprendiz') {
                    header("Location: ../dashboard_aprendiz.php");
                } else {
                    echo "Rol no válido.";
                }
                exit();
            } else {
                echo "Contraseña incorrecta.";
            }
        } else {
            echo "Usuario no encontrado.";
        }

        $stmt->close();
    } else {
        echo "Error en la consulta SQL: " . $conn->error;
    }
} else {
    echo "Método de solicitud no válido.";
}

// Cerrar la conexión a la base de datos
$conn->close();
?>
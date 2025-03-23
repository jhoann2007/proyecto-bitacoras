<?php
session_start();
include 'includes/db.php';

// Verificar si el usuario es un aprendiz
if ($_SESSION['rol'] !== 'aprendiz') {
    header("Location: index.php"); // Redirigir si no es aprendiz
    exit();
}

// Obtener el ID del aprendiz
$id_aprendiz = $_SESSION['id'];

// Procesar el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verificar si se enviaron los datos necesarios
    if (isset($_POST['nombre_bitacora']) && isset($_FILES['archivo'])) {
        $nombre_bitacora = $_POST['nombre_bitacora'];
        $comentario = $_POST['comentario'] ?? ''; // El comentario es opcional

        // Procesar el archivo
        $archivo_nombre = basename($_FILES['archivo']['name']);
        $archivo_temporal = $_FILES['archivo']['tmp_name'];
        $ruta_archivo = "uploads/" . $archivo_nombre;

        // Validar que el archivo se haya subido correctamente
        if ($_FILES['archivo']['error'] === UPLOAD_ERR_OK) {
            // Mover el archivo a la carpeta de subidas
            if (move_uploaded_file($archivo_temporal, $ruta_archivo)) {
                // Insertar la bitácora en la base de datos
                $sql = "INSERT INTO bitacoras (id_aprendiz, nombre_bitacora, archivo, comentario) VALUES (?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);

                if ($stmt) {
                    $stmt->bind_param("isss", $id_aprendiz, $nombre_bitacora, $ruta_archivo, $comentario);

                    if ($stmt->execute()) {
                        echo "Bitácora subida correctamente.";
                    } else {
                        echo "Error al guardar la bitácora: " . $stmt->error;
                    }

                    $stmt->close();
                } else {
                    echo "Error en la preparación de la consulta: " . $conn->error;
                }
            } else {
                echo "Error al mover el archivo a la carpeta de subidas.";
            }
        } else {
            echo "Error al subir el archivo: Código de error " . $_FILES['archivo']['error'];
        }
    } else {
        echo "Faltan datos obligatorios.";
    }
} else {
    echo "Método de solicitud no válido.";
}

// Cerrar la conexión a la base de datos
$conn->close();
?>
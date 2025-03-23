<?php
session_start();
include 'includes/db.php';

// Verificar si el usuario es un instructor
if ($_SESSION['rol'] !== 'instructor') {
    echo "No tienes permiso para realizar esta acción.";
    exit();
}

// Obtener los datos de la solicitud
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idBitacora = $_POST['id_bitacora'];
    $calificada = $_POST['calificada'];

    // Actualizar el estado de la bitácora
    $sql = "UPDATE bitacoras SET calificada = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("ii", $calificada, $idBitacora);
        if ($stmt->execute()) {
            echo "Bitácora actualizada correctamente.";
        } else {
            echo "Error al actualizar la bitácora: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Error en la preparación de la consulta: " . $conn->error;
    }
} else {
    echo "Método de solicitud no válido.";
}

// Cerrar la conexión a la base de datos
$conn->close();
?>
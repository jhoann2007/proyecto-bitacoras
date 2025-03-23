<?php
session_start();
include 'db.php';

// Verificar si el usuario es un instructor
if ($_SESSION['rol'] !== 'instructor') {
    echo json_encode(['success' => false, 'message' => 'No tienes permiso para realizar esta acción.']);
    exit();
}

// Verificar si se recibió el ID de la bitácora
if (!isset($_POST['id_bitacora']) || empty($_POST['id_bitacora'])) {
    echo json_encode(['success' => false, 'message' => 'ID de bitácora no especificado']);
    exit();
}

$idBitacora = intval($_POST['id_bitacora']);

// Marcar la bitácora como calificada
$sql = "UPDATE bitacoras SET calificada = 1 WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $idBitacora);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Bitácora calificada correctamente']);
} else {
    echo json_encode(['success' => false, 'message' => 'Error al calificar la bitácora: ' . $conn->error]);
}

// Cerrar la conexión
$stmt->close();
$conn->close();
?>
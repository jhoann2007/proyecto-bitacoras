<?php
// Incluir archivo de conexión a la base de datos
require_once 'db.php';

// Verificar si la solicitud es POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
    exit();
}

// Verificar parámetros requeridos
if (!isset($_POST['id_bitacora']) || (!isset($_POST['comentario']) && !isset($_POST['comentario_instructor']))) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Parámetros incompletos']);
    exit();
}

$idBitacora = intval($_POST['id_bitacora']);
$comentario = isset($_POST['comentario']) ? trim($_POST['comentario']) : null;
$comentarioInstructor = isset($_POST['comentario_instructor']) ? trim($_POST['comentario_instructor']) : null;

// Verificar si la bitácora existe
$sql_check = "SELECT id FROM bitacoras WHERE id = ?";
$stmt_check = $conn->prepare($sql_check);
$stmt_check->bind_param("i", $idBitacora);
$stmt_check->execute();
$stmt_check->store_result();

if ($stmt_check->num_rows > 0) {
    // Si existe, actualizar los comentarios
    $setClause = [];
    $params = [];
    $types = '';

    if ($comentario !== null) {
        $setClause[] = "comentario = ?";
        $params[] = $comentario;
        $types .= 's';
    }

    if ($comentarioInstructor !== null) {
        $setClause[] = "comentario_instructor = ?";
        $params[] = $comentarioInstructor;
        $types .= 's';
    }

    if (!empty($setClause)) {
        $params[] = $idBitacora;
        $types .= 'i';
        
        $sql_update = "UPDATE bitacoras SET " . implode(", ", $setClause) . " WHERE id = ?";
        $stmt_update = $conn->prepare($sql_update);
        $stmt_update->bind_param($types, ...$params);

        if ($stmt_update->execute()) {
            echo json_encode(['success' => true, 'message' => 'Comentario actualizado correctamente']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al actualizar: ' . $stmt_update->error]);
        }
        $stmt_update->close();
    }
} else {
    // Si no existe, insertar una nueva bitácora
    $sql_insert = "INSERT INTO bitacoras (id, comentario, comentario_instructor) VALUES (?, ?, ?)";
    $stmt_insert = $conn->prepare($sql_insert);
    $stmt_insert->bind_param("iss", $idBitacora, $comentario, $comentarioInstructor);

    if ($stmt_insert->execute()) {
        echo json_encode(['success' => true, 'message' => 'Nueva bitácora agregada con comentario']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al agregar: ' . $stmt_insert->error]);
    }
    $stmt_insert->close();
}

// Cerrar conexiones
$stmt_check->close();
$conn->close();
?>

<?php
include 'db.php';

$data = json_decode(file_get_contents('php://input'), true);
$bitacoraId = $data['bitacoraId'];
$comentario = $data['comentario'];

$sql = "UPDATE bitacoras SET comentario = '$comentario' WHERE id = $bitacoraId";
if ($conn->query($sql) === TRUE) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => $conn->error]);
}
?>
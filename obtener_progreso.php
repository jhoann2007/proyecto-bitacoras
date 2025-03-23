<?php
session_start();
include 'includes/db.php';

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['id'])) {
    echo json_encode(['error' => 'Usuario no autenticado']);
    exit();
}

// Verificar si el usuario es un aprendiz
if ($_SESSION['rol'] !== 'aprendiz') {
    echo json_encode(['error' => 'Acceso no autorizado']);
    exit();
}

// Obtener el ID del aprendiz
$id_aprendiz = $_SESSION['id'];

// Obtener las bitácoras ya subidas por el aprendiz
$sqlBitacoras = "SELECT nombre_bitacora, calificada FROM bitacoras WHERE id_aprendiz = $id_aprendiz";
$resultBitacoras = $conn->query($sqlBitacoras);
$bitacorasSubidas = [];

if ($resultBitacoras && $resultBitacoras->num_rows > 0) {
    while ($row = $resultBitacoras->fetch_assoc()) {
        $bitacorasSubidas[$row['nombre_bitacora']] = $row['calificada'];
    }
}

// Definir las 16 filas predeterminadas
$filasPredeterminadas = [
    "Bitácora 1", "Bitácora 2", "Bitácora 3", "Bitácora 4", "Bitácora 5", "Bitácora 6",
    "Bitácora 7", "Bitácora 8", "Bitácora 9", "Bitácora 10", "Bitácora 11", "Bitácora 12",
    "Cédula", "Carnet", "APE", "Pruebas TYT"
];

// Calcular progreso
$totalBitacoras = count($filasPredeterminadas);
$bitacorasSubidasCount = count($bitacorasSubidas);
$bitacorasAprobadas = 0;

foreach ($bitacorasSubidas as $bitacora) {
    if ($bitacora == 1) {
        $bitacorasAprobadas++;
    }
}

$porcentajeProgreso = $totalBitacoras > 0 ? round(($bitacorasSubidasCount / $totalBitacoras) * 100) : 0;
$porcentajeAprobadas = $totalBitacoras > 0 ? round(($bitacorasAprobadas / $totalBitacoras) * 100) : 0;

// Devolver los datos en formato JSON
echo json_encode([
    'porcentajeProgreso' => $porcentajeProgreso,
    'porcentajeAprobadas' => $porcentajeAprobadas,
    'bitacorasSubidas' => $bitacorasSubidasCount,
    'bitacorasAprobadas' => $bitacorasAprobadas,
    'totalBitacoras' => $totalBitacoras
]);
?>
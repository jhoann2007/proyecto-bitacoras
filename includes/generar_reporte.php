<?php
// Incluir archivo de conexión a la base de datos
require_once 'db.php';

// Verificar parámetros
if (!isset($_GET['id_aprendiz']) || empty($_GET['id_aprendiz'])) {
    die('Error: ID de aprendiz no especificado');
}

$idAprendiz = intval($_GET['id_aprendiz']);
$nombreCarpeta = isset($_GET['nombre']) ? $_GET['nombre'] : 'aprendiz_' . $idAprendiz;

// Sanitizar el nombre de la carpeta para que sea seguro en sistema de archivos
$nombreCarpeta = preg_replace('/[^a-zA-Z0-9_]/', '_', $nombreCarpeta);

// Obtener documentos específicos del aprendiz
$sqlDocumentos = "SELECT nombre_bitacora, archivo FROM bitacoras 
                 WHERE id_aprendiz = ? 
                 AND nombre_bitacora IN ('Cédula', 'Carnet', 'APE', 'Pruebas TYT')";
                 
$stmt = $conn->prepare($sqlDocumentos);
$stmt->bind_param("i", $idAprendiz);
$stmt->execute();
$resultDocumentos = $stmt->get_result();

// Verificar si hay documentos
if ($resultDocumentos->num_rows === 0) {
    die('Error: No se encontraron los documentos requeridos para el reporte');
}

// Crear archivo ZIP temporal
$zipFile = tempnam(sys_get_temp_dir(), 'reporte_');
$zip = new ZipArchive();

if ($zip->open($zipFile, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
    die('Error: No se pudo crear el archivo ZIP');
}

// Obtener la ruta base del proyecto
$baseDir = __DIR__ . '/../'; // Ajusta según la ubicación del script

// Agregar archivos al ZIP
$documentosAgregados = [];
$documentosRequeridos = ['Cédula', 'Carnet', 'APE', 'Pruebas TYT']; // Lista de documentos requeridos

while ($documento = $resultDocumentos->fetch_assoc()) {
    $archivoRuta = $documento['archivo'];
    $nombreBitacora = $documento['nombre_bitacora'];
    
    // Convertir ruta relativa a absoluta
    if (strpos($archivoRuta, 'uploads/') === 0) {
        $archivoRuta = $baseDir . $archivoRuta;
    }
    
    // Verificar si el archivo existe y si es uno de los documentos requeridos
    if (file_exists($archivoRuta) && in_array($nombreBitacora, $documentosRequeridos)) {
        // Usar el nombre de la bitácora como nombre de archivo dentro del ZIP
        $nombreEnZip = $nombreBitacora . '.' . pathinfo($archivoRuta, PATHINFO_EXTENSION);
        
        // Agregar archivo al ZIP
        if ($zip->addFile($archivoRuta, $nombreEnZip)) {
            $documentosAgregados[] = $nombreBitacora;
        } else {
            die('Error: No se pudo agregar el archivo ' . $archivoRuta . ' al ZIP');
        }
    } else {
        die('Error: El archivo ' . $archivoRuta . ' no existe en el servidor o no es un documento requerido');
    }
}

// Cerrar el ZIP
$zip->close();

// Verificar que se agregaron los 4 documentos requeridos
if (count($documentosAgregados) !== 4) {
    if (file_exists($zipFile)) {
        unlink($zipFile);
    }
    die('Error: No se pudieron agregar todos los documentos requeridos al reporte');
}

// Enviar el archivo ZIP al navegador
header('Content-Type: application/zip');
header('Content-Disposition: attachment; filename="Reporte_' . $nombreCarpeta . '.zip"');
header('Content-Length: ' . filesize($zipFile));
header('Pragma: no-cache');
header('Expires: 0');

// Enviar el archivo y luego eliminarlo
readfile($zipFile);
if (file_exists($zipFile)) {
    unlink($zipFile);
}

// Cerrar la conexión
$stmt->close();
$conn->close();
?>
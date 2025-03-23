<?php
session_start();
include 'db.php';

// Verificar si el usuario es un instructor
if (!esInstructor()) {
    mostrarError("No tienes permiso para ver esta página.");
    exit();
}

// Verificar si se recibió el ID del aprendiz
if (!isset($_GET['id_aprendiz']) || empty($_GET['id_aprendiz'])) {
    mostrarError("ID de aprendiz no proporcionado.");
    exit();
}

$idAprendiz = intval($_GET['id_aprendiz']);

// Obtener información del aprendiz
$sqlAprendiz = "SELECT nombre, apellido FROM usuarios WHERE id = ? AND rol = 'aprendiz'";
$stmtAprendiz = $conn->prepare($sqlAprendiz);
$stmtAprendiz->bind_param("i", $idAprendiz);
$stmtAprendiz->execute();
$resultAprendiz = $stmtAprendiz->get_result();

if ($resultAprendiz->num_rows === 0) {
    mostrarError("Aprendiz no encontrado.");
    exit();
}

$aprendiz = $resultAprendiz->fetch_assoc();
$nombreCompleto = $aprendiz['nombre'] . ' ' . $aprendiz['apellido'];

// Obtener bitácoras del aprendiz
$sqlBitacoras = "SELECT id, nombre_bitacora, archivo, comentario, calificada FROM bitacoras WHERE id_aprendiz = ? ORDER BY nombre_bitacora ASC";
$stmtBitacoras = $conn->prepare($sqlBitacoras);
$stmtBitacoras->bind_param("i", $idAprendiz);
$stmtBitacoras->execute();
$resultBitacoras = $stmtBitacoras->get_result();

// Mostrar información del aprendiz
echo '<div class="mb-4 p-4 bg-blue-50 rounded-lg">';
echo '<h3 class="text-lg font-bold text-blue-800 mb-2">Aprendiz: ' . htmlspecialchars($nombreCompleto) . '</h3>';
echo '<p class="text-sm text-blue-600">Total de bitácoras: ' . $resultBitacoras->num_rows . ' de 16</p>';
echo '</div>';

// Si no hay bitácoras
if ($resultBitacoras->num_rows === 0) {
    mostrarMensaje("Este aprendiz aún no ha subido ninguna bitácora.");
} else {
    // Mostrar tabla de bitácoras
    echo '<div class="overflow-x-auto">';
    echo '<table class="w-full border-collapse">';
    echo '<thead class="bg-gray-100">';
    echo '<tr>';
    echo '<th class="p-2 text-left text-xs font-medium text-gray-600 uppercase">Nombre</th>';
    echo '<th class="p-2 text-left text-xs font-medium text-gray-600 uppercase">Archivo</th>';
    echo '<th class="p-2 text-left text-xs font-medium text-gray-600 uppercase">Estado</th>';
    echo '<th class="p-2 text-left text-xs font-medium text-gray-600 uppercase">Acciones</th>';
    echo '</tr>';
    echo '</thead>';
    echo '<tbody class="divide-y divide-gray-200">';
    
    while ($bitacora = $resultBitacoras->fetch_assoc()) {
        $estadoClase = $bitacora['calificada'] ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800';
        $estadoTexto = $bitacora['calificada'] ? 'Calificada' : 'Pendiente';
        $estadoIcono = $bitacora['calificada'] ? 'fa-check-circle' : 'fa-clock';
        
        // Obtener nombre de archivo sin la ruta
        $nombreArchivo = basename($bitacora['archivo']);
        
        echo '<tr class="hover:bg-gray-50">';
        echo '<td class="p-3 text-sm font-medium text-gray-900">' . htmlspecialchars($bitacora['nombre_bitacora']) . '</td>';
        echo '<td class="p-3 text-sm text-gray-700">' . htmlspecialchars($nombreArchivo) . '</td>';
        echo '<td class="p-3 text-sm">';
        echo '<span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full ' . $estadoClase . '">';
        echo '<i class="fas ' . $estadoIcono . ' mr-1"></i> ' . $estadoTexto;
        echo '</span>';
        echo '</td>';
        echo '<td class="p-3 text-sm flex gap-2">';
        echo '<a href="' . htmlspecialchars($bitacora['archivo']) . '" target="_blank" class="bg-blue-500 text-white px-2 py-1 rounded hover:bg-blue-600 transition">';
        echo '<i class="fas fa-eye mr-1"></i> Ver';
        echo '</a>';
        
        // Agregar botón para calificar si no está calificada
        if (!$bitacora['calificada']) {
            echo '<button onclick="calificarBitacora(' . $bitacora['id'] . ')" class="bg-green-500 text-white px-2 py-1 rounded hover:bg-green-600 transition">';
            echo '<i class="fas fa-check mr-1"></i> Calificar';
            echo '</button>';
        
        }
        
        echo '</td>';
        echo '</tr>';
        
        // Fila para comentarios - mostrar comentario existente y permitir edición
        echo '<tr class="bg-gray-50">';
        echo '<td colspan="4" class="p-3 text-sm">';
        echo '<div class="mb-2 font-medium text-gray-600">Comentario:</div>';
        echo '<textarea id="comentario_' . $bitacora['id'] . '" class="w-full p-2 border rounded" rows="2">' . htmlspecialchars($bitacora['comentario']) . '</textarea>';
        echo '<div class="mt-2 flex justify-end">';
        echo '<button onclick="guardarComentario(' . $bitacora['id'] . ')" class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600 transition">';
        echo '<i class="fas fa-save mr-1"></i> Guardar comentario';
        echo '</button>';
        echo '</div>';
        echo '<div id="comentario_status_' . $bitacora['id'] . '" class="mt-1"></div>';
        echo '</td>';
        echo '</tr>';
    }
    
    echo '</tbody>';
    echo '</table>';
    echo '</div>';
}

// Cerrar conexiones
$stmtAprendiz->close();
$stmtBitacoras->close();
$conn->close();

// ============== FUNCIONES ================

/**
 * Verifica si el usuario es un instructor.
 * @return bool - True si es instructor, False en caso contrario.
 */
function esInstructor() {
    return $_SESSION['rol'] === 'instructor';
}

/**
 * Muestra un mensaje de error.
 * @param string $mensaje - Mensaje de error a mostrar.
 */
function mostrarError($mensaje) {
    echo "
    <div class='bg-red-100 text-red-700 p-4 rounded-lg'>
        <i class='fas fa-exclamation-triangle mr-2'></i>$mensaje
    </div>";
}

/**
 * Muestra un mensaje informativo.
 * @param string $mensaje - Mensaje a mostrar.
 */
function mostrarMensaje($mensaje) {
    echo "<div class='text-gray-500 text-center'>$mensaje</div>";
}
?>
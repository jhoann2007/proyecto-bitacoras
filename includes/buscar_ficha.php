<?php
include 'db.php'; // Incluye la conexión a la base de datos

if (isset($_GET['ficha'])) {
    $ficha = $_GET['ficha'];

    // Consulta para buscar fichas que coincidan
    $sql = "SELECT numero_ficha FROM fichas WHERE numero_ficha LIKE '%$ficha%'";
    $result = $conn->query($sql);

    // Verificar si hay resultados
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<div class='p-2 hover:bg-gray-100 cursor-pointer'>" . $row['numero_ficha'] . "</div>";
        }
    } else {
        echo "<div class='p-2 text-gray-500'>No se encontraron fichas.</div>";
    }
} else {
    echo "<div class='p-2 text-gray-500'>No se ha proporcionado un número de ficha.</div>";
}
?>
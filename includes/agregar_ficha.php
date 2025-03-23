<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $numero_ficha = $_POST['numero_ficha'];

    // Insertar nueva ficha
    $sql = "INSERT INTO fichas (numero_ficha) VALUES ('$numero_ficha')";

    if ($conn->query($sql) === TRUE) {
        echo "Ficha agregada correctamente.";
    } else {
        echo "Error al agregar ficha: " . $conn->error;
    }
}
?>
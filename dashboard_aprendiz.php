<?php
session_start();
include 'includes/db.php';

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['id'])) {
    header("Location: index.php"); // Redirigir si no ha iniciado sesión
    exit();
}

// Verificar si el usuario es un aprendiz
if ($_SESSION['rol'] !== 'aprendiz') {
    header("Location: index.php"); // Redirigir si no es aprendiz
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

// Definir las 15 filas predeterminadas
$filasPredeterminadas = [
    "Bitácora 1", "Bitácora 2", "Bitácora 3", "Bitácora 4", "Bitácora 5", "Bitácora 6",
    "Bitácora 7", "Bitácora 8", "Bitácora 9", "Bitácora 10", "Bitácora 11", "Bitácora 12",
    "Cédula", "Carnet", "APE", "Pruebas TYT"
];
$bitacorasComentariosInstructor= [];
$sql = "SELECT nombre_bitacora, comentario_instructor FROM bitacoras WHERE id_aprendiz = $id_aprendiz";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $bitacorasComentariosInstructor[$row['nombre_bitacora']] = $row['comentario_instructor']; // Asociar comentario con nombre de bitácora
    }
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel del Aprendiz - SENA Bitácoras</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@300;400;700&display=swap" rel="stylesheet">
    <style>
         /* Clean and Modern with Bold Accents */
        body {
            font-family: 'Lato', sans-serif;
            background-color: #f0f4f8; /* Very light blue-gray */
            color: #4a6572; /* Dark blue-gray text */
            line-height: 1.7;
            overflow-x: hidden;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-start;
            min-height: 100vh;
            padding: 2rem;
            box-sizing: border-box;
        }

        /* Profile Picture */
        .profile-section {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background: url('https://source.unsplash.com/random/120x120') center/cover no-repeat;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); /* Subtle shadow */
            margin-bottom: 1.5rem;
            border: 3px solid #5f9ea0; /* Cadet Blue */
            transition: transform 0.3s ease;
        }

        .profile-section:hover {
            transform: scale(1.1);
        }

        /* Main Content Card */
        .info-card {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1); /* Softer shadow */
            padding: 2.5rem;
            margin-bottom: 2.5rem;
            width: 95%;
            max-width: 800px;
            text-align: left;
        }

        .info-card h2 {
            color: #34495e; /* Dark Blue */
            margin-bottom: 1.25rem;
            font-weight: 700;
            font-size: 1.75rem;
            letter-spacing: 0.08em;
            border-bottom: 3px solid #95a5a6; /* Silver border */
            padding-bottom: 0.75rem;
        }

        .info-card p {
            color: #6c7a89; /* Medium blue-gray */
            font-size: 1.1rem;
        }

        /* Progress Bar Styles */
        .progress-container {
            background-color: #dbe6e9; /* Light blue-gray */
            border-radius: 8px;
            height: 12px;
            margin-bottom: 0.75rem;
        }

        .progress-bar {
            background-color: #26a69a; /* Teal */
            border-radius: 8px;
            height: 100%;
            transition: width 0.4s ease;
        }

        /* State Indicators */
        .state-indicator {
            width: 18px;
            height: 18px;
            border-radius: 50%;
            display: inline-block;
            margin-right: 0.5rem;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); /* Subtle shadow */
        }

        .luz-verde {
            background-color: #a5d6a7; /* Light green */
        }

        .luz-amarilla {
            background-color: #ffcc80; /* Light orange */
        }

        .luz-roja {
            background-color: #ef9a9a; /* Light red */
        }

        /* Button Styles */
        .action-btn {
            background-color: #394a51; /* Dark Blue */
            color: #fff;
            border: none;
            padding: 0.8rem 1.6rem;
            border-radius: 25px;
            cursor: pointer;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            font-weight: 500;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .action-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2);
        }

         /* Table Styles */
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1.5rem;
        }

        .table th, .table td {
            padding: 1.2rem 1rem;
            text-align: left;
            border-bottom: 1px solid #d0d8dd; /* Light gray border */
            font-size: 0.95rem;
        }

        .table th {
            background-color: #ecf0f1; /* Lighter background */
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: #455a64; /* Darker text */
        }

        .table tbody tr:hover {
            background-color: #f5f8fa; /* Subtle hover */
        }
         /* Form Controls */
        .form-control {
            width: 100%;
            padding: 0.8rem;
            border: 1px solid #bdc3c7; /* Light gray border */
            border-radius: 8px;
            font-size: 1rem;
            color: #34495e;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }

        .form-control:focus {
            border-color: #7f8c8d; /* Medium gray */
            outline: none;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
        }

        /* Auxiliary Section */
        .auxiliary-section {
            margin-top: auto;
            text-align: center;
            padding: 1.2rem;
        }

        .auxiliary-section a {
            color: #2980b9; /* Strong Blue */
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .auxiliary-section a:hover {
            color: #3498db; /* Lighter Blue */
        }

        /* Footer Styles */
        footer {
            text-align: center;
            padding: 1.2rem;
            color: #7f8c8d;
            border-top: 1px solid #d0d8dd;
        }

    /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 100;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.5); /* Semi-transparent background */
        }

        .modal.open {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .modal-content {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
            padding: 2.5rem;
            max-width: 600px;
            width: 90%;
        }

        .modal-content h2 {
            color: #34495e; /* Dark Blue */
            margin-bottom: 1.25rem;
            font-weight: 700;
            font-size: 1.75rem;
            letter-spacing: 0.08em;
            border-bottom: 3px solid #95a5a6;
            padding-bottom: 0.75rem;
            text-align: center;
        }
        .modal {
            display: none;
        }
        .modal.open {
            display: flex;
        }
        .luz-verde {
            width: 15px;
            height: 15px;
            background-color: green;
            border-radius: 50%;
            display: inline-block;
        }
        .luz-amarilla {
            width: 15px;
            height: 15px;
            background-color: yellow;
            border-radius: 50%;
            display: inline-block;
        }
        .luz-roja {
            width: 15px;
            height: 15px;
            background-color: red;
            border-radius: 50%;
            display: inline-block;
        }
        .boton-bloqueado {
            opacity: 0.5;
            cursor: not-allowed;
        }
    </style>
</head>
<body>
    <div class="app-container">
        <div class="profile-section"></div>

        <div class="info-card">
            <h2 class="text-2xl font-bold text-center">Panel del Aprendiz</h2>
            <p>Bienvenido, <?php echo $_SESSION['nombre'] ?? 'Aprendiz'; ?>! Consulta y gestiona tus bitácoras aquí.</p>
            <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- Tarjeta de progreso total -->
                    <div class="text-center">
                        <h3 class="text-lg mb-2 font-semibold">Total</h3>
                        <div class="progress-container">
                            <div id="barra-progreso-total" class="progress-bar" style="width: 0%"></div>
                        </div>
                        <span id="progreso-total" class="block mt-2 text-sm">0%</span>
                        <p id="subidas-total" class="text-xs text-gray-600">0 de 16 bitácoras</p>
                    </div>

                    <!-- Tarjeta de bitácoras aprobadas -->
                    <div class="text-center">
                        <h3 class="text-lg mb-2 font-semibold">Aprobadas</h3>
                        <div class="progress-container">
                            <div id="barra-progreso-aprobadas" class="progress-bar luz-verde" style="width: 0%"></div>
                        </div>
                        <span id="progreso-aprobadas" class="block mt-2 text-sm">0%</span>
                        <p id="aprobadas-total" class="text-xs text-gray-600">0 de 16 bitácoras</p>
                    </div>

                    <!-- Tarjeta de bitácoras en revisión -->
                    <div class="text-center">
                        <h3 class="text-lg mb-2 font-semibold">En Revisión</h3>
                        <p id="en-revision" class="text-xl font-bold">0</p>
                        <div id="estado-revision" class="text-xs text-yellow-600">
                            <i class="fas fa-clock mr-1"></i> Pendiente
                        </div>
                        <p class="text-xs text-gray-600">Esperando calificación</p>
                    </div>
                </div>
            </div>
             <div class="info-card">
                <h2 class="text-xl font-semibold mb-4">Gestionar Bitácoras</h2>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th class="text-center">Estado</th>
                            <th class="text-center">Archivo</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($filasPredeterminadas as $fila): ?>
                            <?php
                            $subida = isset($bitacorasSubidas[$fila]);
                            $calificada = $subida && $bitacorasSubidas[$fila] == 1;

                            $estadoLuz = $calificada ? "luz-verde" : ($subida ? "luz-amarilla" : "luz-roja");
                            $estadoTexto = $calificada ? "Aprobada" : ($subida ? "En revisión" : "Pendiente");
                            $estadoTitle = $calificada ? "Bitácora aprobada" : ($subida ? "Bitácora en revisión" : "Bitácora pendiente");
                            $subirDisabled = $subida ? "disabled" : "";
                            ?>
                            <tr>
                                <td><?= $fila ?></td>
                                <td class="text-center">
                                    <span title="<?= $estadoTitle ?>" class="state-indicator <?= $estadoLuz ?>"></span>
                                    <?= $estadoTexto ?>
                                </td>
                                <td class="text-center">
                                    <form action='subir_bitacora.php' method='POST' enctype='multipart/form-data'>
                                        <input type='hidden' name='nombre_bitacora' value='<?= $fila ?>'>
                                        <input type='file' name='archivo' class='form-control' <?= $subirDisabled ?>>
                                </td>
                                <td class="text-center">
                                    <button type='submit' class='action-btn' <?= $subirDisabled ?>>Subir</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
           <div class="auxiliary-section">
                <a href="#" onclick="mostrarReporte()"><i class="fas fa-file-alt mr-2"></i> Ver Reporte</a> |
                <a href="logout.php"><i class="fas fa-sign-out-alt mr-2"></i> Salir</a>
            </div>

           <!-- Modal para el reporte -->
            <div id="modalReporte" class="modal">
                <div class="modal-content">
                    <h2>Reporte de Bitácoras</h2>
                    <p>Estado de tus bitácoras:</p>
                    <?php
                        foreach ($filasPredeterminadas as $fila) {
                            $subida = isset($bitacorasSubidas[$fila]);
                            $calificada = $subida && $bitacorasSubidas[$fila] == 1;
                            $estadoLuz = $calificada ? "luz-verde" : ($subida ? "luz-amarilla" : "luz-roja");
                            $estadoTexto = $calificada ? "Aprobada" : ($subida ? "En revisión" : "Pendiente");
                            echo "<p>$fila: <span class='state-indicator $estadoLuz'></span> $estadoTexto</p>";
                        }
                    ?>
                    <button onclick="cerrarModal()" class="action-btn">Cerrar</button>
                </div>
            </div>
        </div>
          <!-- Modal para el reporte -->
            <div id="modalReporte" class="modal">
                <div class="modal-content">
                    <h2>Reporte de Bitácoras</h2>
                    <p>Estado de tus bitácoras:</p>
                    <?php
                        foreach ($filasPredeterminadas as $fila) {
                            $subida = isset($bitacorasSubidas[$fila]);
                            $calificada = $subida && $bitacorasSubidas[$fila] == 1;
                            $estadoLuz = $calificada ? "luz-verde" : ($subida ? "luz-amarilla" : "luz-roja");
                            $estadoTexto = $calificada ? "Aprobada" : ($subida ? "En revisión" : "Pendiente");
                            echo "<p>$fila: <span class='state-indicator $estadoLuz'></span> $estadoTexto</p>";
                        }
                    ?>
                     <div class="form-container">
                         <form>
                            <button onclick="cerrarModal()" class="action-btn">Cerrar</button>
                        </form>
                    </div>
                </div>
            </div>
            <footer class="bg-gray-800 text-white py-4 mt-6">
                <div class="container mx-auto px-6 text-center">
                    <p>© <?php echo date('Y'); ?> SENA - Sistema Nacional de Aprendizaje</p>
                    <p class="text-gray-400 text-sm mt-1">Sistema de Bitácoras</p>
                </div>
            </footer>
        </div>
          
    </div>
<script>
      function subirBitacora(nombreBitacora) {
    const archivo = document.querySelector(`input[name="archivo_${nombreBitacora}"]`).files[0];
    const comentario = document.querySelector(`textarea[name="comentario_${nombreBitacora}"]`).value;

    if (!archivo) {
        alert("Por favor, selecciona un archivo.");
        return;
    }

    const formData = new FormData();
    formData.append("nombre_bitacora", nombreBitacora);
    formData.append("archivo", archivo);
    formData.append("comentario", comentario);

    fetch("subir_bitacora.php", {
        method: "POST",
        body: formData,
    })
    .then(response => response.text())
    .then(data => {
        alert(data); // Mostrar mensaje de éxito o error
        location.reload(); // Recargar la página para actualizar la tabla
    })
    .catch(error => {
        console.error("Error:", error);
    });
}
        function mostrarReporte() {
            document.getElementById('modalReporte').classList.add('open');
        }

        function cerrarModal() {
            document.getElementById('modalReporte').classList.remove('open');
        }

    // Función para actualizar las tarjetas de progreso
    async function actualizarTarjetas() {
        try {
            // Obtener datos actualizados desde el servidor
            const response = await fetch('obtener_progreso.php');
            const data = await response.json();

            // Actualizar la tarjeta de progreso total
            document.getElementById('progreso-total').textContent = `${data.porcentajeProgreso}%`;
            document.getElementById('barra-progreso-total').style.width = `${data.porcentajeProgreso}%`;
            document.getElementById('subidas-total').textContent = `${data.bitacorasSubidas} de ${data.totalBitacoras} bitácoras subidas`;

            // Actualizar la tarjeta de bitácoras aprobadas
            document.getElementById('progreso-aprobadas').textContent = `${data.porcentajeAprobadas}%`;
            document.getElementById('barra-progreso-aprobadas').style.width = `${data.porcentajeAprobadas}%`;
            document.getElementById('aprobadas-total').textContent = `${data.bitacorasAprobadas} de ${data.totalBitacoras} bitácoras aprobadas`;

            // Actualizar la tarjeta de bitácoras en revisión
            const enRevision = data.bitacorasSubidas - data.bitacorasAprobadas;
            document.getElementById('en-revision').textContent = enRevision;
            const estadoRevision = document.getElementById('estado-revision');
            if (enRevision > 0) {
                estadoRevision.innerHTML = `
                    <span class="bg-yellow-100 text-yellow-800 text-sm font-semibold px-3 py-1 rounded-full">
                        <i class="fas fa-clock mr-1"></i> Pendiente de revisión
                    </span>
                `;
            } else {
                estadoRevision.innerHTML = `
                    <span class="bg-gray-100 text-gray-600 text-sm font-semibold px-3 py-1 rounded-full">
                        <i class="fas fa-check-circle mr-1"></i> Sin pendientes
                    </span>
                `;
            }
        } catch (error) {
            console.error('Error al obtener los datos:', error);
        }
    }

    // Actualizar las tarjetas cada 5 segundos
    setInterval(actualizarTarjetas, 5000);

    // Actualizar las tarjetas al cargar la página
    document.addEventListener('DOMContentLoaded', actualizarTarjetas);

    </script>
</body>
</html>
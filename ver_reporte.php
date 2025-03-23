<?php
session_start();
include 'includes/db.php';

// Redirigir si no es instructor
if ($_SESSION['rol'] !== 'instructor') {
    header("Location: index.php");
    exit();
}

// Verificar conexión a la base de datos
if (!$conn) {
    die("Error: No se pudo conectar a la base de datos.");
}

// Obtener el total de fichas
$totalFichas = 0;
$sqlTotalFichas = "SELECT COUNT(DISTINCT ficha) AS total FROM usuarios WHERE rol = 'aprendiz'";
$resultTotalFichas = $conn->query($sqlTotalFichas);

if ($resultTotalFichas && $resultTotalFichas->num_rows > 0) {
    $row = $resultTotalFichas->fetch_assoc();
    $totalFichas = $row['total'];
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Instructor - SENA Bitácoras</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <style>
        /* Estilos personalizados */
        body {
            background: linear-gradient(to right, #6a11cb, #2575fc);
            color: #fff;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .sidebar {
            background-color: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-right: 1px solid rgba(255, 255, 255, 0.2);
        }

        .content {
            background-color: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .ficha-card {
            background-color: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 10px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            color: #fff;
        }

        .ficha-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
        }

        .modal {
            background-color: rgba(0, 0, 0, 0.7);
            backdrop-filter: blur(5px);
        }

        .modal-content {
            background-color: rgba(255, 255, 255, 0.9);
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            color: #333;
        }

        .btn-primary {
            background-color: #3490dc;
            color: #fff;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #2779bd;
        }

        .btn-secondary {
            background-color: #6c757d;
            color: #fff;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .btn-secondary:hover {
            background-color: #5a6268;
        }
    </style>
</head>

<body class="bg-gradient-to-r from-purple-500 to-blue-500 min-h-screen flex flex-col">
    <!-- Contenedor principal -->
    <div class="container mx-auto flex flex-grow p-4">
        <!-- Sidebar (Menú lateral) -->
        <aside class="w-64 py-4 px-3 rounded-l-lg sidebar fixed top-0 left-0 h-full">
            <div class="text-center mb-6">
                <img src="https://www.sena.edu.co/Style%20Library/alayout/images/logoSena.png" alt="Logo SENA"
                    class="h-16 inline-block">
                <h1 class="text-xl font-semibold mt-2">SENA Bitácoras</h1>
            </div>

            <nav>
                <ul>
                    <li class="mb-2">
                        <a href="dashboard_instructor.php" class="block py-2 px-4 rounded hover:bg-blue-700">
                            <i class="fas fa-home mr-2"></i>Panel Principal
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="#" class="block py-2 px-4 rounded hover:bg-blue-700">
                            <i class="fas fa-file-alt mr-2"></i>Reportes
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="#" class="block py-2 px-4 rounded hover:bg-blue-700">
                            <i class="fas fa-cog mr-2"></i>Configuración
                        </a>
                    </li>
                    <li class="mt-8">
                        <a href="logout.php" class="block py-2 px-4 rounded bg-red-600 hover:bg-red-700">
                            <i class="fas fa-sign-out-alt mr-2"></i>Cerrar Sesión
                        </a>
                    </li>
                </ul>
            </nav>
        </aside>

        <!-- Contenido principal -->
        <main class="flex-1 ml-64 p-8 content">
            <!-- Encabezado del contenido -->
            <header class="flex justify-between items-center mb-6">
                <div>
                    <h2 class="text-3xl font-bold animate__animated animate__fadeIn">
                        <i class="fas fa-chart-bar mr-2"></i>Reporte de Aprendices
                    </h2>
                    <p class="text-gray-300">Bienvenido, <?php echo $_SESSION['nombre'] ?? 'Instructor'; ?></p>
                </div>
                <div class="text-right">
                    <span class="inline-flex items-center justify-center px-3 py-2 text-sm font-bold leading-none bg-green-500 rounded-full">
                        Total Fichas: <?php echo $totalFichas; ?>
                    </span>
                </div>
            </header>

            <!-- Barra de búsqueda -->
            <div class="mb-6">
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <i class="fas fa-search text-gray-400"></i>
                    </div>
                    <input type="search" id="searchFicha" placeholder="Buscar ficha..."
                        class="block w-full p-4 pl-10 text-sm border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 text-black">
                </div>
            </div>

            <!-- Contenedor de fichas -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="fichasContainer">
                <?php
                // Obtener fichas y cantidad de aprendices por ficha
                $sqlFichas = "SELECT ficha, COUNT(*) AS cantidad FROM usuarios WHERE rol = 'aprendiz' GROUP BY ficha";
                $resultFichas = $conn->query($sqlFichas);

                if (!$resultFichas) {
                    echo "<div class='col-span-full p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50' role='alert'><i class='fas fa-exclamation-triangle mr-2'></i>Error en la consulta de fichas: " . $conn->error . "</div>";
                } elseif ($resultFichas->num_rows === 0) {
                    echo "<div class='col-span-full p-4 mb-4 text-sm text-blue-800 rounded-lg bg-blue-50' role='alert'><i class='fas fa-info-circle mr-2'></i>No hay fichas activas en el sistema.</div>";
                } else {
                    while ($filaFicha = $resultFichas->fetch_assoc()):
                        $ficha = $filaFicha['ficha'];
                        $cantidad = $filaFicha['cantidad'];
                ?>
                <!-- Tarjeta de ficha -->
                <div class="ficha-card p-4 rounded-lg shadow-md animate__animated animate__fadeInUp"
                    data-ficha="<?= $ficha ?>">
                    <h3 class="text-xl font-semibold mb-2"><i class="fas fa-folder mr-2"></i>Ficha: <?= $ficha ?></h3>
                    <p class="text-gray-300 mb-4">
                        <i class="fas fa-users mr-2"></i>Aprendices: <?= $cantidad ?>
                    </p>
                    <button onclick="toggleCollapse('ficha<?= $ficha ?>')"
                        class="btn-primary py-2 px-4 rounded hover:bg-blue-700"><i class="fas fa-eye mr-2"></i>Ver
                        Detalles</button>

                    <!-- Lista de aprendices (Colapsable) -->
                    <div id="ficha<?= $ficha ?>" class="collapse mt-4 hidden">
                        <div class="overflow-x-auto">
                            <table class="min-w-full leading-normal">
                                <thead>
                                    <tr>
                                        <th
                                            class="px-5 py-3 border-b-2 border-gray-200 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                            Nombre
                                        </th>
                                        <th
                                            class="px-5 py-3 border-b-2 border-gray-200 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                            Estado
                                        </th>
                                        <th
                                            class="px-5 py-3 border-b-2 border-gray-200 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                            Acciones
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                            // Obtener aprendices de la ficha actual con recuento de documentos
                                            $sqlAprendices = "SELECT u.id, u.nombre, u.apellido, u.estado, 
                                                            (SELECT COUNT(*) FROM bitacoras WHERE id_aprendiz = u.id) AS total_bitacoras,
                                                            (SELECT COUNT(*) FROM bitacoras WHERE id_aprendiz = u.id AND nombre_bitacora IN ('Cédula', 'Carnet', 'APE', 'Pruebas TYT')) AS docs_reporte
                                                    FROM usuarios u 
                                                    WHERE u.ficha = '$ficha' AND u.rol = 'aprendiz'";
                                            $resultAprendices = $conn->query($sqlAprendices);

                                            if (!$resultAprendices) {
                                                echo "<tr><td colspan='3' class='px-5 py-3 text-red-600'><i class='fas fa-exclamation-triangle mr-2'></i>Error: " . $conn->error . "</td></tr>";
                                            } elseif ($resultAprendices->num_rows === 0) {
                                                echo "<tr><td colspan='3' class='px-5 py-3 text-gray-500 text-center'>No hay aprendices registrados en esta ficha.</td></tr>";
                                            } else {
                                                while ($rowAprendiz = $resultAprendices->fetch_assoc()):
                                                    // Determinar estado
                                                    $estadoDisplay = $rowAprendiz['estado'];
                                                    $estadoClase = 'bg-yellow-100 text-yellow-800';
                                                    $estadoIcono = 'fa-hourglass-half';

                                                    // Si tiene 16 documentos, cambiar a "Completo"
                                                    $estadoCompleto = false;
                                                    if ($rowAprendiz['total_bitacoras'] >= 16) {
                                                        $estadoDisplay = 'Completo';
                                                        $estadoClase = 'bg-green-100 text-green-800';
                                                        $estadoIcono = 'fa-check-circle';
                                                        $estadoCompleto = true;

                                                    } elseif ($rowAprendiz['estado'] === 'Certificado') {
                                                        $estadoClase = 'bg-green-100 text-green-800';
                                                        $estadoIcono = 'fa-certificate';
                                                    }
                                            ?>
                                    <tr>
                                        <td class="px-5 py-3 border-b border-gray-200 text-sm">
                                            <?= $rowAprendiz['nombre'] . ' ' . $rowAprendiz['apellido'] ?>
                                        </td>
                                        <td
                                            class="px-5 py-3 border-b border-gray-200 text-sm text-center">
                                            <span
                                                class="relative inline-block px-3 py-1 font-semibold text-sm leading-tight rounded-full <?= $estadoClase ?>">
                                                <i class="fas <?= $estadoIcono ?> mr-1"></i>
                                                <?= $estadoDisplay ?> (<?= $rowAprendiz['total_bitacoras'] ?> de
                                                16)
                                            </span>
                                        </td>
                                        <td
                                            class="px-5 py-3 border-b border-gray-200 text-sm text-center">
                                            <?php if ($estadoCompleto && $rowAprendiz['docs_reporte'] >= 4): ?>
                                            <button
                                                onclick="generarReporte(<?= $rowAprendiz['id'] ?>, '<?= $rowAprendiz['nombre'] . '_' . $rowAprendiz['apellido'] ?>')"
                                                class="btn-primary py-2 px-4 rounded hover:bg-blue-700"><i
                                                    class="fas fa-file-download mr-2"></i>Enviar Reporte</button>
                                            <?php else: ?>
                                            <button onclick="verBitacoras(<?= $rowAprendiz['id'] ?>)"
                                                class="btn-secondary py-2 px-4 rounded hover:bg-gray-700"><i
                                                    class="fas fa-book mr-2"></i>Ver Bitácoras</button>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <?php 
                                                endwhile;
                                            }
                                            ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <?php 
                    endwhile;
                }
                ?>
            </div>
        </main>
    </div>

    <!-- Pie de página fijo en la parte inferior -->
    <footer class="bg-gray-900 text-white py-4 text-center">
        <p>© <?php echo date('Y'); ?> SENA - Sistema Nacional de Aprendizaje</p>
        <p class="text-gray-400 text-sm mt-1">Sistema de Bitácoras</p>
    </footer>

    <!-- Modal de Bitácoras -->
    <div id="modalBitacoras" class="fixed inset-0 z-50 flex items-center justify-center modal hidden">
        <div class="modal-content max-w-3xl p-6 rounded-lg">
            <header class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-semibold"><i class="fas fa-book mr-2"></i>Bitácoras del Aprendiz</h2>
                <button onclick="cerrarModal('modalBitacoras')" class="text-gray-500 hover:text-gray-700"><i
                        class="fas fa-times"></i></button>
            </header>
            <div id="bitacorasContent" class="max-h-96 overflow-y-auto mb-4">
                <!-- Contenido dinámico de las bitácoras -->
                <div class="text-center">
                    <i class="fas fa-spinner fa-spin fa-3x"></i>
                    <p>Cargando bitácoras...</p>
                </div>
            </div>
            <footer class="text-right">
                <button onclick="cerrarModal('modalBitacoras')" class="btn-secondary py-2 px-4 rounded"><i
                        class="fas fa-times mr-2"></i>Cerrar</button>
            </footer>
        </div>
    </div>

    <!-- Modal para mostrar archivos -->
    <div id="modalArchivo" class="fixed inset-0 z-50 flex items-center justify-center modal hidden">
        <div class="modal-content max-w-3xl p-6 rounded-lg">
            <header class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-semibold"><i class="fas fa-file mr-2"></i>Vista previa del archivo</h2>
                <button onclick="cerrarModalArchivo()" class="text-gray-500 hover:text-gray-700"><i
                        class="fas fa-times"></i></button>
            </header>
            <div id="contenidoArchivo" class="max-h-96 overflow-y-auto">
                <!-- Aquí se cargará el contenido del archivo -->
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script>
        // ============== BITÁCORA MANAGEMENT FUNCTIONS ================

        /**
         * Fetches and displays the bitácoras for a specific apprentice.
         * @param {number} idAprendiz - Apprentice ID.
         */
        function verBitacoras(idAprendiz) {
            // Show modal with loading message
            document.getElementById('modalBitacoras').classList.remove('hidden');
            document.getElementById('bitacorasContent').innerHTML = `
                <div class="text-center">
                    <i class="fas fa-spinner fa-spin fa-3x"></i>
                    <p>Cargando bitácoras...</p>
                </div>
            `;

            // Fetch bitácoras data
            fetch(`includes/obtener_bitacoras.php?id_aprendiz=${idAprendiz}`)
                .then(response => response.text())
                .then(data => {
                    document.getElementById('bitacorasContent').innerHTML = data;
                })
                .catch(error => {
                    document.getElementById('bitacorasContent').innerHTML = `
                        <div class="p-4 text-sm text-red-800 rounded-lg bg-red-50" role="alert">
                            <i class="fas fa-exclamation-triangle mr-2"></i>Error al cargar las bitácoras: ${error}
                        </div>
                    `;
                    console.error('Error:', error);
                });
        }

        /**
         * Opens a file in a new browser tab or window.
         * @param {string} archivo - Path to the file.
         */
        function abrirArchivo(archivo) {
            if (!archivo) {
                alert('No se encontró la ruta del archivo.');
                return;
            }
            window.open(archivo, '_blank');
        }

        /**
         * Opens a file in a modal.
         * @param {string} archivo - Path to the file.
         */
        function abrirArchivo(archivo) {
            if (!archivo) {
                alert('No se encontró la ruta del archivo.');
                return;
            }

            // Mostrar el modal
            document.getElementById('modalArchivo').classList.remove('hidden');

            // Mostrar mensaje de carga
            const contenidoArchivo = document.getElementById('contenidoArchivo');
            contenidoArchivo.innerHTML = `
                <div class="text-center">
                    <i class="fas fa-spinner fa-spin fa-3x"></i>
                    <p>Cargando archivo...</p>
                </div>
            `;

            // Cargar el archivo según su tipo
            if (archivo.endsWith('.pdf')) {
                mostrarPDF(archivo, contenidoArchivo);
            } else if (archivo.endsWith('.jpg') || archivo.endsWith('.jpeg') || archivo.endsWith('.png')) {
                mostrarImagen(archivo, contenidoArchivo);
            } else {
                contenidoArchivo.innerHTML = `
                    <p class="text-gray-500 text-center py-4">
                        El tipo de archivo no se puede previsualizar. 
                        <a href="${archivo}" target="_blank" class="text-blue-500 underline">Descargar archivo</a>
                    </p>
                `;
            }
        }

        /**
         * Muestra un archivo PDF en el modal.
         * @param {string} archivo - Ruta del archivo PDF.
         * @param {HTMLElement} contenedor - Contenedor donde se mostrará el PDF.
         */
        function mostrarPDF(archivo, contenedor) {
            contenedor.innerHTML = `
                <embed src="${archivo}" type="application/pdf" width="100%" height="100%">
            `;
        }

        /**
         * Muestra una imagen en el modal.
         * @param {string} archivo - Ruta de la imagen.
         * @param {HTMLElement} contenedor - Contenedor donde se mostrará la imagen.
         */
        function mostrarImagen(archivo, contenedor) {
            contenedor.innerHTML = `
                <img src="${archivo}" alt="Archivo" class="max-w-full h-auto mx-auto">
            `;
        }

        /**
         * Cierra el modal de archivos.
         */
        function cerrarModalArchivo() {
            document.getElementById('modalArchivo').classList.add('hidden');
        }

        // Cerrar modal al hacer clic fuera
        window.onclick = function (event) {
            const modalArchivo = document.getElementById('modalArchivo');
            if (event.target === modalArchivo) {
                cerrarModalArchivo();
            }
        };

        /**
         * Toggles the "calificada" status of a bitácora.
         * @param {number} idBitacora - Bitácora ID.
         */
        function toggleCalificada(idBitacora) {
            const checkbox = document.getElementById(`calificada${idBitacora}`);
            const calificada = checkbox.checked ? 1 : 0;

            // Show loading indicator
            const statusElement = document.getElementById(`status${idBitacora}`);
            if (statusElement) {
                statusElement.innerHTML = '<i class="fas fa-spinner fa-spin text-blue-500"></i>';
            }

            // Update bitácora status
            fetch(`includes/actualizar_bitacora.php?id_bitacora=${idBitacora}&calificada=${calificada}`)
                .then(response => response.text())
                .then(data => {
                    // Update visual status
                    if (statusElement) {
                        statusElement.innerHTML = calificada ?
                            '<i class="fas fa-check-circle text-green-500"></i>' :
                            '<i class="fas fa-times-circle text-red-500"></i>';
                    }
                    console.log(data); // Server response
                })
                .catch(error => {
                    if (statusElement) {
                        statusElement.innerHTML = '<i class="fas fa-exclamation-triangle text-red-500"></i>';
                    }
                    console.error('Error:', error);
                });
        }

        /**
         * Accepts a bitácora.
         * @param {number} idBitacora - Bitácora ID.
         */
        function aceptarBitacora(idBitacora) {
            // Confirm action
            const confirmar = confirm("¿Estás seguro de que deseas aceptar esta bitácora?");
            if (!confirmar) return;

            // Create FormData to send data
            const formData = new FormData();
            formData.append("id_bitacora", idBitacora);
            formData.append("calificada", 1); // 1 = Approved

            // Send request to server
            fetch("actualizar_bitacora.php", {
                    method: "POST",
                    body: formData,
                })
                .then(response => response.text())
                .then(data => {
                    alert(data); // Show success or error message
                    cerrarModal('modalBitacoras'); // Close modal
                    location.reload(); // Reload page to update table
                })
                .catch(error => {
                    console.error("Error:", error);
                });
        }

        /**
         * Prints the bitácoras content.
         */
        function imprimirBitacoras() {
            const contenido = document.getElementById('bitacorasContent').innerHTML;
            const ventanaImpresion = window.open('', '_blank');
            ventanaImpresion.document.write(`
        <!DOCTYPE html>
        <html>
        <head>
            <title>Bitácoras de Aprendiz - SENA</title>
            <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
            <style>
                body { padding: 20px; }
                @media print {
                    .no-print { display: none; }
                }
            </style>
        </head>
        <body>
            <div class="mb-4">
                <h1 class="text-2xl font-bold text-center mb-6">Bitácoras de Aprendiz - SENA</h1>
                <div class="text-right no-print mb-4">
                    <button onclick="window.print()" class="bg-blue-500 text-white px-4 py-2 rounded">Imprimir</button>
                </div>
            </div>
            ${contenido}
        </body>
        </html>
    `);
            ventanaImpresion.document.close();
        }

        // ============== UI HELPER FUNCTIONS ================

        /**
         * Closes a modal.
         * @param {string} modalId - Modal element ID.
         */
        function cerrarModal(modalId) {
            document.getElementById(modalId).classList.add('hidden');
        }

        /**
         * Toggles the collapse state of an element.
         * @param {string} id - Element ID to toggle.
         */
        function toggleCollapse(id) {
            const element = document.getElementById(id);
            element.classList.toggle('hidden');
        }

        // ============== EVENT LISTENERS ================

        // Filter fichas in real-time
        document.getElementById('searchFicha').addEventListener('input', function () {
            const searchTerm = this.value.toLowerCase();
            const fichas = document.querySelectorAll('.ficha-card');

            fichas.forEach(ficha => {
                const fichaText = ficha.getAttribute('data-ficha').toLowerCase();
                if (fichaText.includes(searchTerm)) {
                    ficha.style.display = 'block';
                } else {
                    ficha.style.display = 'none';
                }
            });
        });

        // Close modal when clicking outside
        window.onclick = function (event) {
            const modals = document.querySelectorAll('.modal');
            modals.forEach(modal => {
                if (event.target === modal) {
                    modal.classList.add('hidden');
                }
            });
        };
    </script>
</body>

</html>
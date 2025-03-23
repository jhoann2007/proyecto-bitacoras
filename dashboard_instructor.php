<?php
session_start();
include 'includes/db.php';
include 'includes/agregar_aprendiz.php';
include 'includes/agregar_ficha.php';

// Verificar si el usuario es un instructor
if ($_SESSION['rol'] !== 'instructor') {
    header("Location: index.php"); // Redirigir si no es instructor
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tablero Instructor - SENA Bitácoras</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        /* Estilos personalizados */
        body {
            font-family: 'Roboto', sans-serif;
            background: linear-gradient(135deg, #4A00E0, #8E2DE2);
            color: #fff;
            overflow-x: hidden; /* Evitar scroll horizontal */
        }

        /* Sidebar */
        .sidebar {
            width: 280px;
            background-color: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border-right: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 3px 0 5px rgba(0, 0, 0, 0.1);
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            z-index: 10; /* Asegurar que esté sobre el contenido principal */
            transition: transform 0.3s ease;
        }

        .sidebar a {
            color: #fff;
            display: block;
            padding: 12px 20px;
            text-decoration: none;
            border-radius: 8px;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        .sidebar a:hover {
            background-color: rgba(255, 255, 255, 0.1);
            transform: translateX(5px);
        }

        .sidebar .logo {
            padding: 20px;
            text-align: center;
        }

        .sidebar .logo img {
            width: 80px;
            margin-bottom: 10px;
        }

        /* Contenido principal */
        .main-content {
            margin-left: 280px;
            padding: 2rem;
            transition: margin-left 0.3s ease;
        }

        /* Tarjetas */
        .card {
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
        }

        /* Botones */
        .btn {
            background-color: #6C63FF;
            color: #fff;
            padding: 12px 24px;
            border-radius: 8px;
            text-decoration: none;
            transition: background-color 0.3s ease, transform 0.2s ease;
            display: inline-block;
            font-weight: 500;
        }

        .btn:hover {
            background-color: #5048B5;
            transform: translateY(-2px);
        }

        /* Modales */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(5px);
        }

        .modal-content {
            background-color: rgba(255, 255, 255, 0.95);
            margin: 10% auto;
            padding: 2rem;
            border: 1px solid #888;
            width: 80%;
            max-width: 600px;
            border-radius: 12px;
            color: #333;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            transition: color 0.2s ease;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }

        /* Contenedor de modales de confirmación */
        #modal-container {
            position: fixed;
            bottom: 4px;
            right: 4px;
            space-y: 2px;
            z-index: 1050;
        }

        /* Estilos para elementos del sidebar */
        .sidebar-item {
            transition: background-color 0.3s ease, transform 0.2s ease;
            border-radius: 8px;
            margin-bottom: 0.5rem; /* Espacio entre elementos */
        }

        .sidebar-item:hover {
            background-color: rgba(255, 255, 255, 0.1);
            transform: translateX(5px);
        }
    </style>
</head>

<body class="bg-gradient-to-r from-purple-600 to-indigo-600 min-h-screen text-white">
    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="logo">
            <img src="https://www.sena.edu.co/Style%20Library/alayout/images/logoSena.png" alt="Logo SENA">
            <h1 class="text-xl font-bold">SENA Bitácoras</h1>
        </div>
        <nav>
            <ul>
                <li class="sidebar-item">
                    <a href="#" class="flex items-center"><i class="fas fa-home mr-3"></i>Tablero</a>
                </li>
                <li class="sidebar-item">
                    <a href="ver_reporte.php" class="flex items-center"><i class="fas fa-chart-bar mr-3"></i>Ver
                        Reporte</a>
                </li>
                <li class="sidebar-item">
                    <a href="#" onclick="abrirModal('modalAgregarAprendiz')" class="flex items-center"><i
                            class="fas fa-user-plus mr-3"></i>Agregar Aprendiz</a>
                </li>
                <li class="sidebar-item">
                    <a href="#" onclick="abrirModal('modalAgregarFicha')" class="flex items-center"><i
                            class="fas fa-file-alt mr-3"></i>Agregar Ficha</a>
                </li>
                <li class="sidebar-item">
                    <a href="logout.php" class="flex items-center"><i class="fas fa-sign-out-alt mr-3"></i>Cerrar
                        Sesión</a>
                </li>
            </ul>
        </nav>
    </aside>

    <!-- Contenido principal -->
    <main class="main-content">
        <header class="mb-6">
            <h2 class="text-3xl font-bold"><i class="fas fa-tachometer-alt mr-2"></i>Panel del Instructor</h2>
            <p class="text-gray-200">Bienvenido, <?php echo $_SESSION['nombre'] ?? 'Instructor'; ?></p>
        </header>

        <!-- Resumen de Actividad -->
        <section class="card">
            <h3 class="text-xl font-bold mb-4"><i class="fas fa-chart-line mr-2"></i>Resumen de Actividad</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <?php
                // Consulta para contar los aprendices registrados (usuarios con rol 'aprendiz')
                $sql_aprendices = "SELECT COUNT(*) as total FROM usuarios WHERE rol = 'aprendiz'";
                $sql_fichas = "SELECT COUNT(*) as total FROM fichas"; // Consulta para contar las fichas activas

                // Ejecutar las consultas
                $result_aprendices = $conn->query($sql_aprendices);
                $result_fichas = $conn->query($sql_fichas);

                // Obtener los resultados
                $total_aprendices = ($result_aprendices && $result_aprendices->num_rows > 0) ? $result_aprendices->fetch_assoc()['total'] : 0;
                $total_fichas = ($result_fichas && $result_fichas->num_rows > 0) ? $result_fichas->fetch_assoc()['total'] : 0;
                ?>

                <!-- Mostrar el número de aprendices registrados -->
                <div class="bg-indigo-100 bg-opacity-20 p-4 rounded-lg border border-indigo-200 text-center">
                    <p class="text-3xl font-bold text-indigo-300"><?php echo $total_aprendices; ?></p>
                    <p class="text-gray-300">Aprendices Registrados</p>
                </div>

                <!-- Mostrar el número de fichas activas -->
                <div class="bg-green-100 bg-opacity-20 p-4 rounded-lg border border-green-200 text-center">
                    <p class="text-3xl font-bold text-green-300"><?php echo $total_fichas; ?></p>
                    <p class="text-gray-300">Fichas Activas</p>
                </div>

                <!-- Mostrar la fecha actual -->
                <div class="bg-blue-100 bg-opacity-20 p-4 rounded-lg border border-blue-200 text-center">
                    <p class="text-3xl font-bold text-blue-300">
                        <?php
                        // Fecha actual formateada
                        echo date('d/m/Y');
                        ?>
                    </p>
                    <p class="text-gray-300">Fecha Actual</p>
                </div>
            </div>
        </section>

        <!-- Bienvenida y opciones principales -->
        <section class="card">
            <h3 class="text-xl font-bold mb-4"><i class="fas fa-rocket mr-2"></i>Acciones Rápidas</h3>
            <p class="text-gray-300 mb-4">Seleccione una opción para comenzar:</p>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <a href="#" onclick="abrirModal('modalAgregarAprendiz')"
                    class="btn bg-blue-500 hover:bg-blue-700 flex items-center justify-center">
                    <i class="fas fa-user-plus mr-2"></i>Agregar Aprendiz
                </a>
                <a href="#" onclick="abrirModal('modalAgregarFicha')"
                    class="btn bg-green-500 hover:bg-green-700 flex items-center justify-center">
                    <i class="fas fa-file-alt mr-2"></i>Agregar Ficha
                </a>
                <a href="ver_reporte.php"
                    class="btn bg-purple-500 hover:bg-purple-700 flex items-center justify-center">
                    <i class="fas fa-chart-bar mr-2"></i>Ver Reporte
                </a>
            </div>
        </section>

        <!-- Footer -->
        <footer class="text-center mt-8">
            <p>© <?php echo date('Y'); ?> SENA - Sistema Nacional de Aprendizaje</p>
            <p class="text-gray-400 text-sm">Sistema de Bitácoras</p>
        </footer>
    </main>

    <!-- Modales -->
    <!-- Modal para Agregar Aprendiz -->
    <div id="modalAgregarAprendiz" class="modal">
        <div class="modal-content">
            <span class="close" onclick="cerrarModal('modalAgregarAprendiz')">×</span>
            <h2 class="text-xl font-bold text-gray-800 mb-4">Agregar Aprendiz</h2>
            <form id="formAgregarAprendiz" action="includes/agregar_aprendiz.php" method="POST">
                <div class="mb-4">
                    <label class="block text-gray-700 font-medium mb-2">Nombre del Aprendiz</label>
                    <input type="text" name="nombre"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500"
                        required>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 font-medium mb-2">Apellido del Aprendiz</label>
                    <input type="text" name="apellido"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500"
                        required>
                </div>
                <div class="mb-6">
                    <label class="block text-gray-700 font-medium mb-2">Ficha</label>
                    <select name="ficha"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500"
                        required>
                        <option value="">Seleccione una ficha</option>
                        <?php
                        // Cargar fichas desde la base de datos
                        $sql = "SELECT numero_ficha FROM fichas";
                        $result = $conn->query($sql);
                        if ($result && $result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<option value='{$row['numero_ficha']}'>{$row['numero_ficha']}</option>";
                            }
                        }
                        ?>
                    </select>
                </div>
                <div class="flex space-x-3">
                    <button type="submit" class="btn bg-blue-500 hover:bg-blue-600">
                        <i class="fas fa-save mr-2"></i> Guardar
                    </button>
                    <button type="button" onclick="cerrarModal('modalAgregarAprendiz')"
                        class="btn bg-gray-300 text-gray-700 hover:bg-gray-400">
                        <i class="fas fa-times mr-2"></i> Cancelar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal para Agregar Ficha -->
    <div id="modalAgregarFicha" class="modal">
        <div class="modal-content">
            <span class="close" onclick="cerrarModal('modalAgregarFicha')">×</span>
            <h2 class="text-xl font-bold text-gray-800 mb-4">Agregar Ficha</h2>
            <form id="formAgregarFicha" action="includes/agregar_ficha.php" method="POST">
                <div class="mb-6">
                    <label class="block text-gray-700 font-medium mb-2">Número de Ficha</label>
                    <input type="text" name="numero_ficha" placeholder="Ej: 2354321"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-green-500"
                        required>
                </div>
                <div class="flex space-x-3">
                    <button type="submit" class="btn bg-green-500 hover:bg-green-600">
                        <i class="fas fa-save mr-2"></i> Guardar
                    </button>
                    <button type="button" onclick="cerrarModal('modalAgregarFicha')"
                        class="btn bg-gray-300 text-gray-700 hover:bg-gray-400">
                        <i class="fas fa-times mr-2"></i> Cancelar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Contenedor de modales de confirmación -->
    <div id="modal-container">
        <!-- Los modales se agregarán aquí dinámicamente -->
    </div>

    <!-- Script para manejar modales -->
    <script>
        // Función para mostrar un modal de confirmación
        function mostrarModalConfirm(mensaje, tipo = 'success') {
            const colores = {
                success: 'bg-green-500',
                error: 'bg-red-500',
                warning: 'bg-yellow-500',
                info: 'bg-blue-500'
            };

            const modal = document.createElement('div');
            modal.className = `modal-confirm ${colores[tipo]} text-white p-4 rounded-lg shadow-lg flex items-center justify-between`;
            modal.innerHTML = `
                <span>${mensaje}</span>
                <button onclick="cerrarModalConfirm(this)" class="ml-4">
                    <i class="fas fa-times"></i>
                </button>
            `;

            const container = document.getElementById('modal-container');
            container.appendChild(modal);

            // Cerrar automáticamente después de 5 segundos
            setTimeout(() => {
                cerrarModalConfirm(modal.querySelector('button'));
            }, 5000);
        }

        // Función para cerrar un modal de confirmación
        function cerrarModalConfirm(boton) {
            const modal = boton.closest('.modal-confirm');
            modal.classList.add('hide');
            setTimeout(() => {
                modal.remove();
            }, 300); // Esperar a que termine la animación
        }

        // Función para abrir un modal
        function abrirModal(modalId) {
            document.getElementById(modalId).style.display = 'block';
        }

        // Función para cerrar un modal
        function cerrarModal(modalId) {
            document.getElementById(modalId).style.display = 'none';
        }

        // Cerrar modal al hacer clic fuera
        window.onclick = function (event) {
            const modals = document.getElementsByClassName('modal');
            for (let i = 0; i < modals.length; i++) {
                if (event.target == modals[i]) {
                    cerrarModal(modals[i].id);
                }
            }
        }

        // Función para calificar una bitácora
        function calificarBitacora(idBitacora, calificada) {
            const nuevaCalificacion = calificada ? 0 : 1; // Cambiar el estado

            // Crear un objeto FormData para enviar los datos
            const formData = new FormData();
            formData.append("id_bitacora", idBitacora);
            formData.append("calificada", nuevaCalificacion);

            // Enviar la solicitud al servidor
            fetch("actualizar_bitacora.php", {
                    method: "POST",
                    body: formData,
                })
                .then(response => response.text())
                .then(data => {
                    mostrarModalConfirm(data, 'success'); // Mostrar mensaje de éxito
                    location.reload(); // Recargar la página para actualizar el modal
                })
                .catch(error => {
                    mostrarModalConfirm('Error al calificar la bitácora.', 'error');
                    console.error("Error:", error);
                });
        }

        // Función para actualizar el estado de una bitácora
        function actualizarEstado(idBitacora, estado) {
            // Crear un objeto FormData para enviar los datos
            const formData = new FormData();
            formData.append("id_bitacora", idBitacora);
            formData.append("calificada", estado);

            // Enviar la solicitud al servidor
            fetch("actualizar_bitacora.php", {
                    method: "POST",
                    body: formData,
                })
                .then(response => response.text())
                .then(data => {
                    mostrarModalConfirm(data, 'success'); // Mostrar mensaje de éxito
                    location.reload(); // Recargar la página para actualizar el modal
                })
                .catch(error => {
                    mostrarModalConfirm('Error al actualizar el estado.', 'error');
                    console.error("Error:", error);
                });
        }

        // Función para aceptar una bitácora
        function aceptarBitacora(idBitacora) {
            // Confirmar la acción
            const confirmar = confirm("¿Estás seguro de que deseas aceptar esta bitácora?");
            if (!confirmar) return;

            // Crear un objeto FormData para enviar los datos
            const formData = new FormData();
            formData.append("id_bitacora", idBitacora);
            formData.append("calificada", 1); // 1 = Aprobada

            // Enviar la solicitud al servidor
            fetch("actualizar_bitacora.php", {
                    method: "POST",
                    body: formData,
                })
                .then(response => response.text())
                .then(data => {
                    mostrarModalConfirm(data, 'success'); // Mostrar mensaje de éxito
                    cerrarModal('modalBitacoras'); // Cerrar el modal
                    location.reload(); // Recargar la página para actualizar la tabla
                })
                .catch(error => {
                    mostrarModalConfirm('Error al aceptar la bitácora.', 'error');
                    console.error("Error:", error);
                });
        }

        // Evento para el formulario de agregar aprendiz
        document.getElementById('formAgregarAprendiz').addEventListener('submit', function (e) {
            e.preventDefault();
            const formData = new FormData(this);

            fetch('includes/agregar_aprendiz.php', {
                    method: "POST",
                    body: formData,
                })
                .then(response => response.text())
                .then(data => {
                    mostrarModalConfirm(data, 'success'); // Mostrar mensaje de éxito
                    cerrarModal('modalAgregarAprendiz'); // Cerrar el modal
                    this.reset(); // Limpiar el formulario
                })
                .catch(error => {
                    mostrarModalConfirm('Error al agregar el aprendiz.', 'error');
                    console.error("Error:", error);
                });
        });

        // Evento para el formulario de agregar ficha
        document.getElementById('formAgregarFicha').addEventListener('submit', function (e) {
            e.preventDefault();
            const formData = new FormData(this);

            fetch('includes/agregar_ficha.php', {
                    method: "POST",
                    body: formData,
                })
                .then(response => response.text())
                .then(data => {
                    mostrarModalConfirm(data, 'success'); // Mostrar mensaje de éxito
                    cerrarModal('modalAgregarFicha'); // Cerrar el modal
                    this.reset(); // Limpiar el formulario
                })
                .catch(error => {
                    mostrarModalConfirm('Error al agregar la ficha.', 'error');
                    console.error("Error:", error);
                });
        });
    </script>
</body>

</html>
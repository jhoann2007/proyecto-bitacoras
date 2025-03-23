<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - SENA Bitácoras</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* General Styles */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f9fafb; /* Light gray background */
            color: #374151; /* Dark gray text */
        }

        /* Container Styles */
        .register-container {
            max-width: 450px; /* Slightly wider container */
            width: 100%;
            padding: 2rem;
            background-color: #ffffff; /* White background */
            border-radius: 0.75rem; /* More rounded corners */
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1), 0 1px 3px rgba(0, 0, 0, 0.05); /* Enhanced shadow */
        }

        /* Header Styles */
        .register-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .register-title {
            font-size: 2.25rem; /* Slightly larger title */
            font-weight: 700; /* Bold font weight */
            color: #1f2937; /* Dark gray color */
            margin-bottom: 0.5rem;
        }

        .register-subtitle {
            font-size: 1rem;
            color: #6b7280; /* Medium gray color */
        }

        /* Form Styles */
        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            display: block;
            font-size: 0.875rem;
            font-weight: 500;
            color: #374151; /* Dark gray color */
            margin-bottom: 0.5rem;
        }

        .form-input {
            width: 100%;
            padding: 1rem;
            border: 1px solid #d1d5db; /* Light gray border */
            border-radius: 0.5rem;
            font-size: 1rem;
            color: #1f2937; /* Dark gray color */
            transition: border-color 0.2s ease;
        }

        .form-input:focus {
            border-color: #34d399; /* Teal focus color */
            outline: none;
            box-shadow: 0 0 0 3px rgba(52, 211, 153, 0.2); /* Teal focus shadow */
        }

        .input-icon {
            position: absolute;
            left: 0.75rem;
            top: 50%;
            transform: translateY(-50%);
            color: #9ca3af; /* Light gray color */
        }

        .password-toggle {
            position: absolute;
            right: 0.75rem;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #9ca3af; /* Light gray color */
        }

        /* Button Styles */
        .btn-primary {
            display: block;
            width: 100%;
            padding: 1rem;
            background-color: #10b981; /* Green background */
            color: #ffffff; /* White text */
            font-size: 1.125rem;
            font-weight: 600;
            text-align: center;
            border: none;
            border-radius: 0.5rem;
            cursor: pointer;
            transition: background-color 0.2s ease;
        }

        .btn-primary:hover {
            background-color: #059669; /* Darker green on hover */
        }

        /* Link Styles */
        .link-secondary {
            display: block;
            width: 100%;
            padding: 1rem;
            background-color: #f97316; /* Orange background */
            color: #ffffff; /* White text */
            font-size: 1.125rem;
            font-weight: 600;
            text-align: center;
            border: none;
            border-radius: 0.5rem;
            cursor: pointer;
            transition: background-color 0.2s ease;
            text-decoration: none;
        }

        .link-secondary:hover {
            background-color: #ea580c; /* Darker orange on hover */
        }

        /* Ficha Styles */
        .ficha-resultado {
            max-height: 150px;
            overflow-y: auto;
            border: 1px solid #e5e7eb;
            border-radius: 0.375rem;
        }

        .ficha-item:hover {
            background-color: #f3f4f6;
        }

        /* Password Strength */
        .password-strength-bar {
            height: 0.5rem;
            background-color: #ddd;
            border-radius: 0.25rem;
            overflow: hidden;
            margin-top: 0.5rem;
        }

        .password-strength-indicator {
            height: 100%;
            width: 0;
            transition: width 0.3s ease, background-color 0.3s ease;
        }

        .password-strength-text {
            font-size: 0.75rem;
            color: #6b7280;
            margin-top: 0.25rem;
        }

        /* Divider Styles */
        .divider {
            position: relative;
            text-align: center;
            margin-top: 2rem;
            margin-bottom: 2rem;
        }

        .divider:before {
            content: "";
            position: absolute;
            top: 50%;
            left: 0;
            width: 45%;
            height: 1px;
            background-color: #d1d5db; /* Light gray color */
        }

        .divider:after {
            content: "";
            position: absolute;
            top: 50%;
            right: 0;
            width: 45%;
            height: 1px;
            background-color: #d1d5db; /* Light gray color */
        }

        .divider-text {
            display: inline-block;
            padding: 0 0.75rem;
            background-color: #ffffff; /* White background */
            color: #6b7280; /* Medium gray color */
            font-size: 0.875rem;
        }
    </style>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen py-12 px-4 sm:px-6 lg:px-8">
    <div class="register-container">
        <!-- Logo SENA (puedes reemplazar este div con una imagen) -->
        <div class="flex justify-center">
            <img src="https://www.sena.edu.co/Style%20Library/alayout/images/logoSena.png" alt="Logo SENA" class="h-10 mr-3">
        </div>

        <div class="register-header">
            <h1 class="register-title">Registro de Usuario</h1>
            <p class="register-subtitle">Crea tu cuenta para acceder a las bitácoras SENA</p>
        </div>

        <form id="registroForm" action="includes/registro_usuario.php" method="POST">
            <div class="form-group relative">
                <label for="nombre" class="form-label">Nombre</label>
                <div class="relative">
                    <i class="fas fa-user input-icon"></i>
                    <input id="nombre" type="text" name="nombre" class="form-input pl-10" placeholder="Ingresa tu nombre" required>
                </div>
            </div>

            <div class="form-group relative">
                <label for="apellido" class="form-label">Apellido</label>
                <div class="relative">
                    <i class="fas fa-user-tag input-icon"></i>
                    <input id="apellido" type="text" name="apellido" class="form-input pl-10" placeholder="Ingresa tu apellido" required>
                </div>
            </div>

            <div class="form-group relative">
                <label for="ficha" class="form-label">Número de Ficha</label>
                <div class="relative">
                    <i class="fas fa-id-card input-icon"></i>
                    <input id="ficha" type="text" name="ficha" class="form-input pl-10" placeholder="Busca tu número de ficha">
                </div>
                <!-- Contenedor para mostrar resultados de búsqueda de ficha -->
                <div id="resultadosFicha" class="ficha-resultado hidden"></div>
                <p class="mt-1 text-xs text-gray-500">Ingresa al menos 3 caracteres para buscar tu ficha</p>
            </div>

            <div class="form-group relative">
                <label for="password" class="form-label">Contraseña</label>
                <div class="relative">
                    <i class="fas fa-lock input-icon"></i>
                    <input id="password" type="password" name="contraseña" class="form-input pl-10" placeholder="Crea tu contraseña" required>
                    <div class="password-toggle" id="togglePassword">
                        <i class="fas fa-eye"></i>
                    </div>
                </div>
                <div class="password-strength">
                    <div class="password-strength-bar">
                        <div id="passwordStrengthIndicator" class="password-strength-indicator"></div>
                    </div>
                    <p id="passwordStrengthText" class="password-strength-text">La contraseña debe tener al menos 8 caracteres</p>
                </div>
            </div>

            <div class="form-group relative">
                <label for="confirm-password" class="form-label">Confirmar Contraseña</label>
                <div class="relative">
                    <i class="fas fa-lock input-icon"></i>
                    <input id="confirm-password" type="password" name="confirmar_contraseña" class="form-input pl-10" placeholder="Confirma tu contraseña" required>
                </div>
                <p id="passwordMatch" class="text-xs mt-1 text-gray-500"></p>
            </div>

            <div>
                <button type="submit" class="btn-primary">
                    <i class="fas fa-user-plus mr-2"></i>
                    Registrarse
                </button>
            </div>
        </form>

        <div class="divider">
            <span class="divider-text">¿Ya tienes una cuenta?</span>
        </div>

        <div>
            <a href="index.php" class="link-secondary">
                Iniciar sesión
            </a>
        </div>
    </div>

    <script>
        // Toggle password visibility
        document.getElementById('togglePassword').addEventListener('click', function() {
            const passwordInput = document.getElementById('password');
            const icon = this.querySelector('i');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });

        // Password strength indicator
        document.getElementById('password').addEventListener('input', function() {
            const password = this.value;
            const indicator = document.getElementById('passwordStrengthIndicator');
            const text = document.getElementById('passwordStrengthText');

            let strength = 0;
            if (password.length >= 8) strength += 25;
            if (password.match(/[A-Z]/)) strength += 25;
            if (password.match(/[0-9]/)) strength += 25;
            if (password.match(/[^A-Za-z0-9]/)) strength += 25;

            indicator.style.width = strength + '%';

            if (strength < 50) {
                indicator.style.backgroundColor = 'red';
                text.textContent = 'Débil';
                text.style.color = 'red';
            } else if (strength < 75) {
                indicator.style.backgroundColor = 'orange';
                text.textContent = 'Moderada';
                text.style.color = 'orange';
            } else {
                indicator.style.backgroundColor = 'green';
                text.textContent = 'Fuerte';
                text.style.color = 'green';
            }
        });

        // Check if passwords match
        document.getElementById('confirm-password').addEventListener('input', function() {
            const password = document.getElementById('password').value;
            const confirmPassword = this.value;
            const passwordMatch = document.getElementById('passwordMatch');

            if (confirmPassword === '') {
                passwordMatch.textContent = '';
            } else if (password === confirmPassword) {
                passwordMatch.textContent = '¡Las contraseñas coinciden!';
                passwordMatch.style.color = 'green';
            } else {
                passwordMatch.textContent = 'Las contraseñas no coinciden';
                passwordMatch.style.color = 'red';
            }
        });

        // Búsqueda de ficha en tiempo real
        document.getElementById('ficha').addEventListener('input', function() {
            const ficha = this.value;
            const resultadosFicha = document.getElementById('resultadosFicha');

            if (ficha.length >= 3) {
                fetch(`includes/buscar_ficha.php?ficha=${ficha}`)
                    .then(response => response.text())
                    .then(data => {
                        resultadosFicha.innerHTML = data;
                        resultadosFicha.classList.remove('hidden');

                        // Aplicar estilos a los resultados
                        const resultItems = resultadosFicha.querySelectorAll('.cursor-pointer');
                        resultItems.forEach(item => {
                            item.classList.add('ficha-item', 'p-2', 'hover:bg-gray-100');
                        });
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });
            } else {
                resultadosFicha.innerHTML = '';
                resultadosFicha.classList.add('hidden');
            }
        });

        // Seleccionar ficha de la lista
        document.getElementById('resultadosFicha').addEventListener('click', function(e) {
            if (e.target && e.target.classList.contains('cursor-pointer')) {
                document.getElementById('ficha').value = e.target.textContent;
                this.innerHTML = '';
                this.classList.add('hidden');
            }
        });

        // Validación de formulario antes de enviar
        document.getElementById('registroForm').addEventListener('submit', function(e) {
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirm-password').value;

            if (password !== confirmPassword) {
                e.preventDefault();
                alert('Las contraseñas no coinciden. Por favor, verifica.');
            }
        });
    </script>
</body>
</html>
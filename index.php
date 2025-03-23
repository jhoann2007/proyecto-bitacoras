<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - SENA Bitácoras</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* General Styles */
        body {
            font-family: 'Nunito', sans-serif; /* Modern sans-serif font */
            background-color: #e5e7eb; /* Soft gray background */
            color: #4b5563; /* Darker gray text */
            line-height: 1.6;
        }

        /* Container Styles */
        .login-container {
            max-width: 500px; /* Increased max-width for a more spacious feel */
            width: 100%;
            padding: 3rem; /* Increased padding */
            background-color: #ffffff;
            border-radius: 1rem; /* More rounded corners */
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08); /* Softer, larger shadow */
            transition: transform 0.3s ease-in-out; /* Smooth transition for hover effect */
        }

        .login-container:hover {
            transform: translateY(-5px); /* Slight lift on hover */
        }

        /* Header Styles */
        .login-header {
            text-align: center;
            margin-bottom: 3rem; /* Increased margin */
        }

        .login-title {
            font-size: 3rem; /* Larger title */
            font-weight: 800; /* Extra bold font weight */
            color: #1e293b; /* Darker, more prominent title color */
            margin-bottom: 0.75rem;
        }

        .login-subtitle {
            font-size: 1.125rem; /* Slightly larger subtitle */
            color: #64748b; /* Muted subtitle color */
        }

        /* Form Styles */
        .form-group {
            margin-bottom: 2rem; /* Increased spacing between form groups */
        }

        .form-label {
            display: block;
            font-size: 1rem; /* Increased label size */
            font-weight: 600;
            color: #374151;
            margin-bottom: 0.75rem;
        }

        .form-input {
            width: 100%;
            padding: 1.25rem; /* Increased input padding */
            border: 2px solid #d1d5db; /* Thicker border */
            border-radius: 0.75rem; /* More rounded corners */
            font-size: 1.125rem; /* Increased font size */
            color: #374151;
            transition: border-color 0.3s ease;
        }

        .form-input:focus {
            border-color: #6366f1; /* Vibrant focus color */
            outline: none;
            box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.15); /* Softer focus shadow */
        }

        .input-icon {
            position: absolute;
            left: 1rem; /* Slightly larger icon spacing */
            top: 50%;
            transform: translateY(-50%);
            color: #9ca3af;
        }

        .password-toggle {
            position: absolute;
            right: 1rem; /* Slightly larger icon spacing */
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #9ca3af;
        }

        /* Button Styles */
        .btn-primary {
            display: block;
            width: 100%;
            padding: 1.25rem; /* Increased button padding */
            background-color: #2563eb; /* Modern blue background */
            color: #ffffff;
            font-size: 1.25rem; /* Increased font size */
            font-weight: 700;
            text-align: center;
            border: none;
            border-radius: 0.75rem; /* More rounded corners */
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.2s ease; /* Smooth transition */
            box-shadow: 0 4px 6px rgba(52, 71, 103, 0.11); /* Subtle shadow */
        }

        .btn-primary:hover {
            background-color: #1e40af; /* Darker blue on hover */
            transform: scale(1.03); /* Slight scale up on hover */
        }

        /* Link Styles */
        .link-secondary {
            display: block;
            width: 100%;
            padding: 1.25rem; /* Increased link padding */
            background-color: #7dd3fc; /* Softer background */
            color: #0369a1; /* Darker text color */
            font-size: 1.125rem;
            font-weight: 600;
            text-align: center;
            border: none;
            border-radius: 0.75rem; /* More rounded corners */
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.2s ease;
            text-decoration: none;
            box-shadow: 0 4px 6px rgba(52, 71, 103, 0.11); /* Subtle shadow */
        }

        .link-secondary:hover {
            background-color: #38bdf8; /* Hover color */
            transform: scale(1.03); /* Slight scale up on hover */
        }

        /* Remember Me Styles */
        .remember-me {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 2.5rem; /* Increased margin */
        }

        .remember-label {
            font-size: 1rem;
            color: #4b5563;
        }

        .remember-checkbox {
            width: 1.25rem; /* Larger checkbox */
            height: 1.25rem; /* Larger checkbox */
            border: 2px solid #d1d5db; /* Thicker border */
            border-radius: 0.375rem; /* Rounded corners */
            appearance: none;
            cursor: pointer;
            transition: border-color 0.3s ease, background-color 0.3s ease; /* Transition */
        }

        .remember-checkbox:checked {
            background-color: #2563eb; /* Modern blue when checked */
            border-color: #2563eb;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='white'%3E%3Cpath d='M0 0h24v24H0z' fill='none'/%3E%3Cpath d='M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41L9 16.17z'/%3E%3C/svg%3E");
            background-size: 100%;
            background-position: center;
            background-repeat: no-repeat;
        }

        .remember-checkbox:focus {
            outline: none;
            border-color: #6366f1; /* Focus color */
            box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.15); /* Softer focus shadow */
        }

        /* Divider Styles */
        .divider {
            position: relative;
            text-align: center;
            margin-top: 3rem; /* Increased margin */
            margin-bottom: 3rem; /* Increased margin */
        }

        .divider:before {
            content: "";
            position: absolute;
            top: 50%;
            left: 0;
            width: 40%; /* Adjusted width */
            height: 2px; /* Thicker divider */
            background-color: #e5e7eb; /* Softer color */
        }

        .divider:after {
            content: "";
            position: absolute;
            top: 50%;
            right: 0;
            width: 40%; /* Adjusted width */
            height: 2px; /* Thicker divider */
            background-color: #e5e7eb; /* Softer color */
        }

        .divider-text {
            display: inline-block;
            padding: 0 1rem; /* Slightly larger padding */
            background-color: #ffffff;
            color: #9ca3af; /* Muted color */
            font-size: 1rem;
        }
    </style>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen py-12 px-4 sm:px-6 lg:px-8">
    <div class="login-container">
        <!-- Logo SENA (puedes reemplazar este div con una imagen) -->
        <div class="flex justify-center">
            <img src="https://www.sena.edu.co/Style%20Library/alayout/images/logoSena.png" alt="Logo SENA" class="h-12"> <!-- Increased size -->
        </div>

        <div class="login-header">
            <h1 class="login-title">Acceder</h1>
            <p class="login-subtitle">Inicia sesión en tu cuenta SENA</p>
        </div>

        <form id="loginForm" action="includes/validar_usuario.php" method="POST">
            <div class="form-group relative">
                <label for="nombre" class="form-label">Nombre</label>
                <div class="relative">
                    <i class="fas fa-user input-icon"></i>
                    <input id="nombre" type="text" name="nombre" class="form-input pl-10" placeholder="Tu nombre" required>
                </div>
            </div>

            <div class="form-group relative">
                <label for="apellido" class="form-label">Apellido</label>
                <div class="relative">
                    <i class="fas fa-user-tag input-icon"></i>
                    <input id="apellido" type="text" name="apellido" class="form-input pl-10" placeholder="Tu apellido" required>
                </div>
            </div>

            <div class="form-group relative">
                <label for="password" class="form-label">Contraseña</label>
                <div class="relative">
                    <i class="fas fa-lock input-icon"></i>
                    <input id="password" type="password" name="contraseña" class="form-input pl-10" placeholder="Tu contraseña" required>
                    <div class="password-toggle" id="togglePassword">
                        <i class="fas fa-eye"></i>
                    </div>
                </div>
            </div>


            <div>
                <button type="submit" class="btn-primary">
                    <i class="fas fa-sign-in-alt mr-2"></i> Ingresar
                </button>
            </div>
        </form>

        
        <div>
            <a href="registro.php" class="">Crear cuenta</a>
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
    </script>
</body>
</html>
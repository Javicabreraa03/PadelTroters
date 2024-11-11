<?php
session_start();
include 'db/conexion.php'; // Incluye la conexión con la base de datos

// Verifica si el formulario ha sido enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = $_POST['username'];
    $password = $_POST['password'];

    // Caso 1: Usuario como "guest" si ambos campos están vacíos
    if (empty($usuario) && empty($password)) {
        $_SESSION['rol'] = 'guest';
        $_SESSION['user'] = 'guest';
        header("Location: /trabajofinalphp/pages/home/home.php");
        exit;
    }

 // Caso 2: Acceso como "admin" con credenciales específicas
if ($usuario == 'admin' && $password == 'admin') {
    // Consulta para obtener el usuario desde la base de datos
    $sql = "SELECT * FROM users_login WHERE usuario = :usuario";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':usuario', $usuario, PDO::PARAM_STR); // Bind en PDO
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Asignar los datos del usuario en la sesión
    $_SESSION['rol'] = 'admin';
    $_SESSION['user'] = 'admin';
    $_SESSION['id_user'] = $user['idUser'];
    
        echo"<script>alert('¡Bienvenido $usuario!'); window.location.href=' /trabajofinalphp/pages/home/home.php';</script>";
        exit;
    }

    // Caso 3: Verificación de usuario en la base de datos (usando PDO)
    $sql = "SELECT * FROM users_login WHERE usuario = :usuario";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':usuario', $usuario, PDO::PARAM_STR); // Bind en PDO
    $stmt->execute();

    // Verifica si el usuario existe
    if ($stmt->rowCount() > 0) {
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Verifica la contraseña usando password_verify
        if (password_verify($password, $user['password'])) {  // Comparar la contraseña encriptada con la ingresada
            $_SESSION['rol'] = $user['rol'];
            $_SESSION['user'] = $user['usuario'];
            $_SESSION['id_user'] = $user['idUser'];

            echo"<script>alert('¡Bienvenido $usuario!'); window.location.href=' /trabajofinalphp/pages/home/home.php';</script>";
            exit;
        } else {
            // Contraseña incorrecta
            echo "<script>alert('Contraseña incorrecta.'); window.location.href='index.php';</script>";
            exit;
        }
    } else {
        // Usuario no existe
        echo "<script>alert('Usuario no encontrado. Redirigiendo al registro.'); window.location.href='/trabajofinalphp/pages/registro/registro.php';</script>";
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión PadelTroters</title>
    <!-- Bootstrap CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <style>
        /* Estilos para el video de fondo */
        .video-background {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1; /* Ponerlo detrás de todo el contenido */
            object-fit: cover;
        }

        /* Estilo del contenedor del título */
        .title-container {
            width: 75%;
            max-width: 500px;
            margin: 50px auto;
            padding: 20px;
            background: rgba(0, 0, 0, 0.75); /* Fondo negro con 75% de opacidad */
            color: #fff; /* Letras blancas */
            text-align: center;
            font-size: 24px;
            cursor: pointer;
            border-radius: 10px;
            transition: all 0.4s ease-in-out; /* Transición suave para el hover */
        }

        /* Estilo del formulario de inicio de sesión */
        .login-container {
            max-width: 400px;
            margin: 50px auto;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            border-radius: 10px;
            background: rgba(255, 255, 255, 0.8); /* Fondo semi-transparente */
            opacity: 0;
            pointer-events: none; /* No permite hacer clic mientras está oculto */
            transition: opacity 0.4s ease-in-out, pointer-events 0.4s ease-in-out; /* Transición suave de opacidad y eventos */
        }

        /* Mostrar el formulario de inicio de sesión cuando se hace clic */
        .title-container.active + .login-container {
            opacity: 1;
            pointer-events: auto; /* Permite la interacción con el formulario */
        }
    </style>
</head>
<body onload="showReminder()">

<!-- Video de fondo -->
<iframe class="video-background" src="https://www.youtube.com/embed/AwAbAebYhJ8?autoplay=1&loop=1&playlist=AwAbAebYhJ8&controls=0&mute=1&modestbranding=1&showinfo=0" frameborder="0" allow="autoplay; fullscreen" allowfullscreen></iframe>

<div class="container">
    <!-- Título con fondo negro y opacidad del 75% -->
    <div class="title-container" onclick="toggleForm()">
        INICIAR SESIÓN
    </div>

    <!-- Formulario de inicio de sesión -->
    <div class="login-container bg-light p-4 rounded">
       <!-- <h3 class="text-center mb-4">INICIAR SESIÓN</h3>-->

        <form method="post" onsubmit="return validateForm()">
            <!-- Usuario -->
            <div class="form-group">
                <label for="username">Usuario</label>
                <input type="text" class="form-control" name="username" id="username" placeholder="Ingresa tu usuario">
            </div>
            
            <!-- Contraseña -->
            <div class="form-group">
                <label for="password">Contraseña</label>
                <div class="input-group">
                    <input type="password" class="form-control" name="password" id="password" placeholder="Ingresa tu contraseña">
                    <div class="input-group-append">
                        <button type="button" class="btn btn-secondary" onmousedown="togglePassword(true)" onmouseup="togglePassword(false)">👁️</button>
                    </div>
                </div>
            </div>

            <!-- Botón de Iniciar Sesión -->
            <button type="submit" class="btn btn-primary btn-block">Iniciar Sesión</button>
        </form>

        <!-- Enlace para registrarse -->
        <p class="text-center mt-3 small">
            <a href="/trabajofinalphp/pages/registro/registro.php">Regístrate aquí</a>
        </p>
    </div>
</div>


<!-- JavaScript para la validación, recordar mensaje y mostrar/ocultar contraseña -->
<script>
    // Mostrar recordatorio al abrir la página
    function showReminder() {
        alert("RECUERDA: SI QUIERES ENTRAR SIN USUARIO, DEJA EN BLANCO AMBOS HUECOS");
    }

    // Validar formulario de inicio de sesión
    function validateForm() {
        const username = document.getElementById('username').value.trim();
        const password = document.getElementById('password').value.trim();
        
        if ((username === '' && password !== '') || (username !== '' && password === '')) {
            alert("RELLENA AMBOS CAMPOS PARA ENTRAR CON TU CUENTA");
            return false;
        }
        return true;
    }

    // Mostrar/ocultar contraseña mientras el botón es presionado
    function togglePassword(show) {
        const passwordField = document.getElementById('password');
        passwordField.type = show ? 'text' : 'password';
    }

    // Alternar la visibilidad del formulario al hacer clic
    function toggleForm() {
        const titleContainer = document.querySelector('.title-container');
        titleContainer.classList.toggle('active');
    }
</script>

<!-- Bootstrap JS y Popper.js -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

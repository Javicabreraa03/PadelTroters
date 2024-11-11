<?php
include '../navbar/navbar.php';
include '../../db/conexion.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = $_POST['nombre'];
    $apellidos = $_POST['apellidos'];
    $email = $_POST['email'];
    $telefono = $_POST['telefono'];
    $fecha_nacimiento = $_POST['fecha_nacimiento'];
    $direccion = $_POST['direccion'];
    $sexo = $_POST['sexo'];
    $password = $_POST['password'];

    // Comprobación de campos vacíos
    if (empty($nombre) || empty($apellidos) || empty($email) || empty($telefono) || empty($fecha_nacimiento)) {
        echo "<script>alert('¡Rellena los campos vacíos!');</script>";
    } else {
        // Comprobar si el email ya existe
        $stmt = $conn->prepare("SELECT * FROM users_data WHERE email = ?");
        $stmt->execute([$email]);
        $result = $stmt->fetch();

        if ($result) {
            echo "<script>alert('Este email ya está registrado'); window.location.href = 'registro.php';</script>";
        } else {
            // Validar que el valor de sexo esté dentro de los permitidos
            if (in_array($sexo, ["Masculino", "Femenino", "Otro"])) {
                // Insertar en users_data
                $stmt = $conn->prepare("INSERT INTO users_data (nombre, apellidos, email, telefono, fecha_nacimiento, direccion, sexo) VALUES (?, ?, ?, ?, ?, ?, ?)");
                $stmt->execute([$nombre, $apellidos, $email, $telefono, $fecha_nacimiento, $direccion, $sexo]);

                // Obtener el ID del último usuario insertado
                $user_id = $conn->lastInsertId();

                // Encriptar la contraseña
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                try {
                    // Insertar en users_login utilizando el $user_id obtenido
                    $stmt_login = $conn->prepare("INSERT INTO users_login (idUser, usuario, password, rol) VALUES (?, ?, ?, 'user')");
                    $stmt_login->execute([$user_id, $nombre, $hashed_password]);

                    echo "<script>alert('Registro exitoso'); window.location.href = 'registro.php';</script>";
                } catch (Exception $e) {
                    echo "<script>alert('Error al registrar en el login: " . $e->getMessage() . "');</script>";
                }
            } else {
                echo "<script>alert('Valor de sexo no válido');</script>";
            }
        }
    }
}
?>




<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - PadelTroters</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-image: url('/trabajofinalphp/assets/images/pista-padel.jpg');
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center;
            color: white;
        }

        .form-container {
            background-color: black;
            padding: 30px;
            border-radius: 8px;
            color: gold;
            max-width: 500px;
            margin: 50px auto;
        }

        h1 {
    text-align: center;
    color: gold;
    text-transform: uppercase;
    background-color: black;
    padding: 10px 20px; /* Ajusta el padding para que se ajuste al tamaño de las letras */
    border-radius: 20px; /* Borde redondeado */
    text-decoration: underline; /* Subraya las letras */
    margin:  auto 10px; /* Centra horizontalmente y agrega margen arriba y abajo */
    
}



        .form-group label {
            color: white;
        }

        .btn-primary {
            background-color: gold;
            border: none;
            color: black;
        }

        .btn-primary:hover {
            background-color: darkgoldenrod;
            color: white;
        }

        #togglePassword {
            cursor: pointer;
        }
    </style>
</head>
<body>

    <div class="container">
        <h1>¿Quieres ser un Padel Troter?</h1>
        <div class="form-container">
            <form action="registro.php" method="POST">
                <div class="form-group">
                    <label for="nombre">Nombre</label>
                    <input type="text" name="nombre" id="nombre" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="apellidos">Apellidos</label>
                    <input type="text" name="apellidos" id="apellidos" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" name="email" id="email" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="telefono">Teléfono</label>
                    <input type="text" name="telefono" id="telefono" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="fecha_nacimiento">Fecha de Nacimiento</label>
                    <input type="date" name="fecha_nacimiento" id="fecha_nacimiento" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="direccion">Dirección</label>
                    <input type="text" name="direccion" id="direccion" class="form-control">
                </div>
                <div class="form-group">
                    <label for="sexo">Sexo</label>
                    <select name="sexo" id="sexo" class="form-control">
                        <option value="Masculino">Masculino</option>
                        <option value="Femenino">Femenino</option>
                        <option value="Otro">Otro</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="password">Contraseña</label>
                    <input type="password" name="password" id="password" class="form-control" required>
                    <small><input type="checkbox" id="togglePassword"> Mostrar contraseña</small>
                </div>
                <button type="submit" class="btn btn-primary btn-block">Registrarse</button>
            </form>
        </div>
    </div>

    <script>
        document.getElementById('togglePassword').addEventListener('click', function () {
            const password = document.getElementById('password');
            password.type = password.type === 'password' ? 'text' : 'password';
        });
    </script>

<?php include '../footer/footer.php'; ?>
</body>
</html>

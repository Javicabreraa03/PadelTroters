<?php
include '../navbar/navbar.php';
include '../../db/conexion.php'; // Conexión a la base de datos

// Recuperar el ID del usuario desde la sesión
$idUser = $_SESSION['id_user'];


// Obtener los datos del usuario desde la base de datos
$query = $conn->prepare("SELECT * FROM users_data WHERE idUser = ?");
$query->execute([$idUser]);
$user = $query->fetch();

$queryLogin = $conn->prepare("SELECT * FROM users_login WHERE idUser = ?");
$queryLogin->execute([$idUser]);
$userLogin = $queryLogin->fetch();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil de Usuario</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        /* Estilos para el contenedor de perfil */
        .container2 {
            width: 50%;
            margin: 50px auto;
            background-color: black;
            color: gold;
            padding: 20px;
            border-radius: 10px;
        }
        h1 {
            text-align: center;
            text-transform: uppercase;
            text-decoration: underline;
        }
        .form-group label {
            font-weight: bold;
        }
        .btn-danger {
            margin-top: 20px;
        }
        .btn-warning {
            margin-top: 20px;
        }
    </style>
</head>
<body> 

<div class="container2">
    <!-- Título con el nombre y apellidos -->
    <h1><?= $user['nombre'] . ' ' . $user['apellidos'] ?></h1>
    
    <!-- Formulario de modificación de datos -->
    <form action="perfil.php" method="POST">
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" class="form-control" id="email" name="email" value="<?= $user['email'] ?>" required>
        </div>

        <div class="form-group">
            <label for="telefono">Teléfono</label>
            <input type="text" class="form-control" id="telefono" name="telefono" value="<?= $user['telefono'] ?>" required>
        </div>

        <div class="form-group">
            <label for="fecha_nacimiento">Fecha de nacimiento</label>
            <input type="date" class="form-control" id="fecha_nacimiento" name="fecha_nacimiento" value="<?= $user['fecha_nacimiento'] ?>" required>
        </div>

        <div class="form-group">
            <label for="direccion">Dirección</label>
            <input type="text" class="form-control" id="direccion" name="direccion" value="<?= $user['direccion'] ?>" required>
        </div>

        <div class="form-group">
            <label for="sexo">Sexo</label>
            <select class="form-control" id="sexo" name="sexo" required>
                <option value="Masculino" <?= $user['sexo'] == 'Masculino' ? 'selected' : '' ?>>Masculino</option>
                <option value="Femenino" <?= $user['sexo'] == 'Femenino' ? 'selected' : '' ?>>Femenino</option>
                <option value="Otro" <?= $user['sexo'] == 'Otro' ? 'selected' : '' ?>>Otro</option>
            </select>
        </div>

        <!-- Campo Contraseña (Oculta por defecto) -->
        <div class="form-group">
            <label for="password">Contraseña</label>
            <div class="input-group">
                <input type="password" class="form-control" id="password" name="password" value="<?= $userLogin['password'] ?>" required>
                <div class="input-group-append">
                    <button type="button" id="showPassword" class="btn btn-secondary">Mostrar Contraseña</button>
                </div>
            </div>
        </div>

        <button type="submit" class="btn btn-warning btn-block">Guardar Cambios</button>
    </form>

    <!-- Botón para eliminar cuenta -->
    <form action="perfil.php" method="POST">
        <button type="submit" name="eliminar" class="btn btn-danger btn-block">Eliminar Usuario</button>
    </form>
</div>



<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>

<?php
// Procesar la eliminación del usuario
if (isset($_POST['eliminar'])) {
    // Eliminar usuario de la tabla users_data
    $deleteData = $conn->prepare("DELETE FROM users_data WHERE idUser = ?");
    $deleteData->execute([$idUser]);

    // Eliminar usuario de la tabla users_login
    $deleteLogin = $conn->prepare("DELETE FROM users_login WHERE idUser = ?");
    $deleteLogin->execute([$idUser]);

    // Cerrar sesión y redirigir a la página de inicio
    session_destroy();
    echo "<script>alert('Tu cuenta ha sido eliminada.'); window.location.href = 'index.php';</script>";
}

// Procesar la actualización de datos del usuario
if ($_SERVER['REQUEST_METHOD'] == 'POST' && !isset($_POST['eliminar'])) {
    $email = $_POST['email'];
    $telefono = $_POST['telefono'];
    $fecha_nacimiento = $_POST['fecha_nacimiento'];
    $direccion = $_POST['direccion'];
    $sexo = $_POST['sexo'];
    $password = $_POST['password'];

    // Si la contraseña ha cambiado, actualizarla
    if (!empty($password)) {
        // La contraseña debe ser encriptada antes de ser almacenada
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        $updateLogin = $conn->prepare("UPDATE users_login SET password = ? WHERE idUser = ?");
        $updateLogin->execute([$passwordHash, $idUser]);
    }

    // Actualizar los datos del usuario
    $update = $conn->prepare("UPDATE users_data SET email = ?, telefono = ?, fecha_nacimiento = ?, direccion = ?, sexo = ? WHERE idUser = ?");
    $update->execute([$email, $telefono, $fecha_nacimiento, $direccion, $sexo, $idUser]);

    echo "<script>alert('Datos actualizados exitosamente'); window.location.href = 'perfil.php';</script>";
}
?>

<script>
    // Mostrar u ocultar la contraseña
    document.getElementById('showPassword').addEventListener('click', function() {
        var passwordField = document.getElementById('password');
        if (passwordField.type === 'password') {
            passwordField.type = 'text';
            this.textContent = 'Ocultar Contraseña';
        } else {
            passwordField.type = 'password';
            this.textContent = 'Mostrar Contraseña';
        }
    });
</script>
<?php include '../footer/footer.php';?>
</body>
</html>

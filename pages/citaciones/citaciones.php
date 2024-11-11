<?php
include '../navbar/navbar.php';
include '../../db/conexion.php';

// Verificar si el usuario está autenticado
if (isset($_SESSION['idUser'])) {
    // Redirigir al login si no está autenticado
    header("Location: login.php");
    exit();
}

// Obtener el user_id de la sesión
$user_id = $_SESSION['id_user'];  // Este debe ser el ID de usuario que se guarda al iniciar sesión

// Obtener el idUser del users_data o users_login
function obtenerIdUser($conn, $user_id) {
    // Primero buscar el idUser en la tabla users_login
    $stmt = $conn->prepare("SELECT idUser FROM users_login WHERE idUser = ?");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch();
    if ($user) {
        return $user['idUser'];
    }

    // Si no se encuentra, buscar en users_data
    $stmt = $conn->prepare("SELECT idUser FROM users_data WHERE idUser = ?");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch();
    return $user ? $user['idUser'] : null;
}

$idUser = obtenerIdUser($conn, $user_id);

// Función para obtener las citas
function obtenerCitas($conn, $idUser) {
    $stmt = $conn->prepare("SELECT * FROM citas WHERE idUser = ? ORDER BY fecha_cita");
    $stmt->execute([$idUser]);
    return $stmt->fetchAll();
}
// Procesar formulario para solicitar cita
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['solicitar'])) {
    $fecha_cita = $_POST['fecha_cita'];
    $motivo_cita = $_POST['motivo_cita'];

    // Verificar si la fecha es anterior al día actual
    if (strtotime($fecha_cita) < time()) {
        echo "<script>alert('¡No puedes crear una cita para una fecha que ya ha pasado!');</script>";
    } elseif (empty($fecha_cita) || empty($motivo_cita)) {
        echo "<script>alert('¡Rellena todos los campos!');</script>";
    } else {
        $stmt = $conn->prepare("INSERT INTO citas (idUser, fecha_cita, motivo_cita) VALUES (?, ?, ?)");
        $stmt->execute([$idUser, $fecha_cita, $motivo_cita]);
        echo "<script>alert('Cita solicitada exitosamente'); window.location.href = 'citaciones.php';</script>";
    }
}


// Procesar modificación de cita
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['modificar'])) {
    $idCita = $_POST['idCita'];
    $fecha_cita = $_POST['fecha_cita'];
    $motivo_cita = $_POST['motivo_cita'];

    // Verificar si los campos no están vacíos
    if (empty($fecha_cita) || empty($motivo_cita)) {
        echo "<script>alert('¡Rellena todos los campos!');</script>";
    } else {
        // Verificar si la cita se puede modificar (fecha futura)
        $stmt = $conn->prepare("SELECT * FROM citas WHERE idCita = ?");
        $stmt->execute([$idCita]);
        $cita = $stmt->fetch();

        if ($cita && strtotime($cita['fecha_cita']) >= time()) {
            // Realizar la actualización de la cita
            $stmt = $conn->prepare("UPDATE citas SET fecha_cita = ?, motivo_cita = ? WHERE idCita = ?");
            $stmt->execute([$fecha_cita, $motivo_cita, $idCita]);
            echo "<script>alert('Cita modificada exitosamente'); window.location.href = 'citaciones.php';</script>";
        } else {
            echo "<script>alert('No se puede modificar una cita con fecha pasada');</script>";
        }
    }
}

// Procesar eliminación de cita
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['eliminar'])) {
    $idCita = $_POST['idCita'];

    // Verificar si la cita se puede eliminar (fecha futura)
    $stmt = $conn->prepare("SELECT * FROM citas WHERE idCita = ?");
    $stmt->execute([$idCita]);
    $cita = $stmt->fetch();

    if ($cita && strtotime($cita['fecha_cita']) >= time()) {
        $stmt = $conn->prepare("DELETE FROM citas WHERE idCita = ?");
        $stmt->execute([$idCita]);
        echo "<script>alert('Cita eliminada exitosamente'); window.location.href = 'citaciones.php';</script>";
    } else {
        echo "<script>alert('No se puede eliminar una cita con fecha pasada');</script>";
    }
}

$citas = obtenerCitas($conn, $idUser);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Citaciones</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-image: url('/trabajofinalphp/assets/images/A7B0C504-DC92-4C92-95C5-41F2108F0042.JPG');
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center;
            color: white;
        }

        .form-container, .citas-container {
            background-color: black;
            padding: 30px;
            border-radius: 8px;
            color: gold;
            max-width: 600px;
            margin: 20px auto;
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

        table {
            width: 100%;
            color: gold;
        }

        table th, table td {
            text-align: center;
            padding: 8px;
        }

        .btn-danger, .btn-warning {
            border: none;
        }

        .edit-form {
            display: none;
        }
    </style>
</head>
<body>

    <div class="container">
        <h1>Gestión de Citaciones</h1>

        <!-- Formulario para solicitar cita -->
        <div class="form-container">
            <h3>Solicitar Cita</h3>
            <form action="citaciones.php" method="POST">
                <div class="form-group">
                    <label for="fecha_cita">Fecha de la cita</label>
                    <input type="datetime-local" name="fecha_cita" id="fecha_cita" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="motivo_cita">Motivo de la cita</label>
                    <textarea name="motivo_cita" id="motivo_cita" class="form-control" required></textarea>
                </div>
                <button type="submit" name="solicitar" class="btn btn-primary btn-block">Solicitar</button>
            </form>
        </div>

        <!-- Tabla de citas -->
        <div class="citas-container">
            <h3>Citas Programadas</h3>
            <table class="table table-dark">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Motivo</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($citas): ?>
                        <?php foreach ($citas as $cita): ?>
                            <tr>
                                <td><?= $cita['fecha_cita'] ?></td>
                                <td><?= $cita['motivo_cita'] ?></td>
                                <td>
                                    <?php if (strtotime($cita['fecha_cita']) >= time()): ?>
                                        <!-- Botón de modificar cita -->
                                        <button class="btn btn-warning btn-modificar" data-id="<?= $cita['idCita'] ?>" data-fecha="<?= $cita['fecha_cita'] ?>" data-motivo="<?= $cita['motivo_cita'] ?>">Modificar</button>
                                        <!-- Botón de eliminar cita -->
                                        <form action="citaciones.php" method="POST" style="display:inline-block; margin-top:10px;">
                                            <input type="hidden" name="idCita" value="<?= $cita['idCita'] ?>">
                                            <button type="submit" name="eliminar" class="btn btn-danger">Eliminar</button>
                                        </form>

                                        <!-- Formulario de modificación (visible al hacer clic en modificar) -->
                                        <form action="citaciones.php" method="POST" class="edit-form" style="display:none;">
                                            <input type="hidden" name="idCita" value="<?= $cita['idCita'] ?>">
                                            <div class="form-group">
                                                <label for="fecha_cita">Fecha de la cita</label>
                                                <input type="datetime-local" name="fecha_cita" id="fecha_cita" class="form-control" value="<?= date('Y-m-d\TH:i', strtotime($cita['fecha_cita'])) ?>" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="motivo_cita">Motivo de la cita</label>
                                                <textarea name="motivo_cita" id="motivo_cita" class="form-control" required><?= $cita['motivo_cita'] ?></textarea>
                                            </div>
                                            <button type="submit" name="modificar" class="btn btn-warning btn-block">Guardar cambios</button>
                                        </form>
                                    <?php else: ?>
                                        <button class="btn btn-secondary" disabled>Modificada</button>
                                        <button class="btn btn-secondary" disabled>Eliminada</button>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="3" class="text-center">No tienes citas programadas.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <?php include '../footer/footer.php';?>

    <script>
        // Mostrar formulario de modificación cuando se haga clic en "Modificar"
        document.querySelectorAll('.btn-modificar').forEach(function (btn) {
            btn.addEventListener('click', function () {
                const idCita = this.getAttribute('data-id');
                const fecha = this.getAttribute('data-fecha');
                const motivo = this.getAttribute('data-motivo');

                // Mostrar formulario de edición
                const form = this.closest('td').querySelector('.edit-form');
                form.style.display = 'block';

                // Rellenar los campos con los valores actuales
                form.querySelector('input[name="idCita"]').value = idCita;
                form.querySelector('input[name="fecha_cita"]').value = fecha;
                form.querySelector('textarea[name="motivo_cita"]').value = motivo;

                // Ocultar botón de modificar
                this.style.display = 'none';
            });
        });
    </script>

</body>
</html>

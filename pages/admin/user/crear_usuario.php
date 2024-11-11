<?php
// Conexión a la base de datos
include '../../../db/conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los datos del formulario
    $nombre = $_POST['nombre'];
    $apellidos = $_POST['apellidos'];
    $email = $_POST['email'];
    $telefono = $_POST['telefono'];
    $fecha_nacimiento = $_POST['fecha_nacimiento'];
    $direccion = $_POST['direccion'];
    $sexo = $_POST['sexo'];
    $password = $_POST['password'];
    $rol = $_POST['rol'];

    try {
        // Iniciar una transacción
        $conn->beginTransaction();

        // Insertar datos en la tabla users_data
        $stmt1 = $conn->prepare("
            INSERT INTO users_data (nombre, apellidos, email, telefono, fecha_nacimiento, direccion, sexo)
            VALUES (:nombre, :apellidos, :email, :telefono, :fecha_nacimiento, :direccion, :sexo)
        ");
        $stmt1->execute([
            ':nombre' => $nombre,
            ':apellidos' => $apellidos,
            ':email' => $email,
            ':telefono' => $telefono,
            ':fecha_nacimiento' => $fecha_nacimiento,
            ':direccion' => $direccion,
            ':sexo' => $sexo
        ]);

        // Obtener el ID del usuario recién creado
        $idUser = $conn->lastInsertId();

        // Insertar datos en la tabla users_login
        $stmt2 = $conn->prepare("
            INSERT INTO users_login (idUser, usuario, password, rol)
            VALUES (:idUser, :nombre, :password, :rol)
        ");
        $stmt2->execute([
            ':idUser' => $idUser,
            ':nombre' => $nombre,
            ':password' => password_hash($password, PASSWORD_DEFAULT), // Hash de la contraseña
            ':rol' => $rol
        ]);

        // Confirmar la transacción
        $conn->commit();
        echo json_encode(['message' => 'Usuario creado exitosamente.']);
    } catch (Exception $e) {
        // Revertir la transacción si hay un error
        $conn->rollBack();
        echo json_encode(['message' => 'Error al crear el usuario: ' . $e->getMessage()]);
    }
}
?>

<?php
include '../../../db/conexion.php';

$idUser = $_POST['idUser'];
$nombre = $_POST['nombre'];
$apellidos = $_POST['apellidos'];
$email = $_POST['email'];
$telefono = $_POST['telefono'];
$fecha_nacimiento = $_POST['fecha_nacimiento'];
$direccion = $_POST['direccion'];
$sexo = $_POST['sexo'];
$rol = $_POST['rol'];

try {
    // Iniciar una transacción
    $conn->beginTransaction();

    // Actualizar en users_data
    $queryData = $conn->prepare("UPDATE users_data SET nombre = ?, apellidos = ?, email = ?, telefono = ?, fecha_nacimiento = ?, direccion = ?, sexo = ? WHERE idUser = ?");
    $queryData->execute([$nombre, $apellidos, $email, $telefono, $fecha_nacimiento, $direccion, $sexo, $idUser]);

    // Actualizar en users_login
    $queryLogin = $conn->prepare("UPDATE users_login SET rol = ? WHERE idUser = ?");
    $queryLogin->execute([$rol, $idUser]);

    // Confirmar transacción
    $conn->commit();

    echo json_encode(["message" => "Usuario actualizado exitosamente."]);
} catch (Exception $e) {
    $conn->rollBack();
    echo json_encode(["message" => "Error al actualizar el usuario: " . $e->getMessage()]);
}

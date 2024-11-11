<?php
include '../../../db/conexion.php';

$idUser = $_POST['idUser'];

try {
    $conn->beginTransaction();

    // Eliminar de users_login
    $queryLogin = $conn->prepare("DELETE FROM users_login WHERE idUser = ?");
    $queryLogin->execute([$idUser]);

    // Eliminar de users_data
    $queryData = $conn->prepare("DELETE FROM users_data WHERE idUser = ?");
    $queryData->execute([$idUser]);

    $conn->commit();
    echo json_encode(["message" => "Usuario eliminado exitosamente."]);
} catch (Exception $e) {
    $conn->rollBack();
    echo json_encode(["message" => "Error al eliminar el usuario: " . $e->getMessage()]);
}

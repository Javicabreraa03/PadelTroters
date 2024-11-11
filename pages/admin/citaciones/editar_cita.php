<?php
include '../../../db/conexion.php';

header('Content-Type: application/json');
error_reporting(0); // Oculta cualquier error o advertencia de PHP

// Verificar si `idCita` está presente
if (!isset($_POST['idCita'])) {
    echo json_encode(["message" => "ID de cita no proporcionado"]);
    exit();
}

$idCita = $_POST['idCita'];
$nombreUsuario = $_POST['nombreUsuario'];
$fecha = $_POST['fecha_cita'];
$motivo = $_POST['motivo_cita'];

try {
    // Obtener `idUser` basado en el nombre de usuario
    $query = $conn->prepare("SELECT idUser FROM users_data WHERE nombre = :nombre");
    $query->bindParam(':nombre', $nombreUsuario);
    $query->execute();
    $user = $query->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        $idUser = $user['idUser'];

        // Actualizar la cita en la base de datos
        $updateQuery = $conn->prepare("UPDATE citas SET idUser = ?, fecha_cita = ?, motivo_cita = ? WHERE idCita = ?");
        $updateQuery->execute([$idUser, $fecha, $motivo, $idCita]);

        echo json_encode(["message" => "Cita actualizada exitosamente"]);
    } else {
        echo json_encode(["message" => "Usuario no encontrado"]);
    }
} catch (PDOException $e) {
    // Captura cualquier excepción y la envía como JSON
    echo json_encode(["message" => "Error al actualizar la cita: " . $e->getMessage()]);
}
?>

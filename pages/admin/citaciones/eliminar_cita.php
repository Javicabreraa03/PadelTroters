<?php
include '../../../db/conexion.php';

// Comprobar si el ID de la cita está presente
if (isset($_POST['idCita'])) {
    $idCita = $_POST['idCita'];

    // Eliminar la cita de la base de datos
    $query = $conn->prepare("DELETE FROM citas WHERE idCita = ?");
    $query->execute([$idCita]);

    // Responder con un mensaje de éxito
    echo json_encode(['message' => 'Cita eliminada con éxito.']);
}
?>

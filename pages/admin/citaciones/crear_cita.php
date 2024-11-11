<?php
// Conexión a la base de datos
include '../../../db/conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recibir el nombre del usuario y demás datos de la cita
    $nombreUsuario = $_POST['nombreUsuario'];
    $fecha = $_POST['fecha'];
    $motivo = $_POST['motivo'];

    try {
        // Obtener el idUser del usuario con el nombre proporcionado
        $query = $conn->prepare("SELECT idUser FROM users_data WHERE nombre = :nombre");
        $query->bindParam(':nombre', $nombreUsuario);
        $query->execute();
        $user = $query->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            $idUser = $user['idUser'];

            // Insertar la cita con el idUser obtenido
            $insertQuery = $conn->prepare("INSERT INTO citas (idUser, fecha_cita, motivo_cita) VALUES (:idUser, :fecha, :motivo)");
            $insertQuery->bindParam(':idUser', $idUser);
            $insertQuery->bindParam(':fecha', $fecha);
            $insertQuery->bindParam(':motivo', $motivo);
            $insertQuery->execute();

            echo json_encode(["message" => "Cita creada exitosamente"]);
        } else {
            echo json_encode(["message" => "Usuario no encontrado"]);
        }
    } catch (PDOException $e) {
        echo json_encode(["message" => "Error al crear la cita: " . $e->getMessage()]);
    }
}
?>

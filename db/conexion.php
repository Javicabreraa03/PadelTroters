<?php
// Configuración de la conexión
$host = 'localhost';
$dbname = 'finalphp';
$username = 'root';
$password = '';

// Crear la conexión
try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    // Configurar el manejo de errores
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
   // echo "Conexión exitosa";
} catch (PDOException $e) {
    //echo "Error en la conexión: " . $e->getMessage();
}
?>

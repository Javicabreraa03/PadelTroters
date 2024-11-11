<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <title>CERRAR SESIÓN</title>
    <script>
        function confirmLogout() {
            // Crear un cuadro de confirmación personalizado
            let confirmation = confirm("¿Desea cerrar la sesión?\nSerá redirigido a la página de inicio de sesión.");
            
            if (confirmation) {
                // Si el usuario confirma, hacer la solicitud de logout
                window.location.href = 'logout_action.php';
            } else {
                // Si el usuario cancela, redirigir al usuario a la página anterior
                window.history.back();
            }
        }
        
        // Ejecutar confirmación al cargar la página
        window.onload = confirmLogout;
    </script>
</head>
<body>
</body>
</html>
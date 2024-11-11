<?php
include '../../../db/conexion.php';

// Obtener el ID de la noticia desde la URL (por ejemplo, editar_noticia.php?id=1)
$idNoticia = $_GET['id'];

// Consultar los datos de la noticia
$query = $conn->prepare("SELECT * FROM noticias WHERE idNoticia = ?");
$query->execute([$idNoticia]);
$noticia = $query->fetch(PDO::FETCH_ASSOC);

// Si no se encuentra la noticia, redirigir o mostrar un error
if (!$noticia) {
    echo "Noticia no encontrada.";
    exit;
}

// Si el formulario se envía, actualizar la noticia
if (isset($_POST['submit'])) {
    $titulo = $_POST['titulo'];
    $texto = $_POST['texto'];
    $fecha = date('Y-m-d'); // Fecha actual
    $imagen = $_FILES['imagen']['name'];
    $tmp_name = $_FILES['imagen']['tmp_name'];
    $ruta = "../../../assets/images/" . $imagen;

    // Si se ha subido una nueva imagen, moverla a la carpeta de imágenes
    if ($imagen) {
        if (move_uploaded_file($tmp_name, $ruta)) {
            // Actualizar la noticia en la base de datos con la nueva imagen
            $sql = "UPDATE noticias SET titulo = ?, imagen = ?, texto = ?, fecha = ? WHERE idNoticia = ?";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$titulo, $imagen, $texto, $fecha, $idNoticia]);
            echo "Noticia actualizada con éxito!";
        } else {
            echo "Error al subir la imagen.";
        }
    } else {
        // Si no se sube nueva imagen, solo actualizar los otros campos
        $sql = "UPDATE noticias SET titulo = ?, texto = ?, fecha = ? WHERE idNoticia = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$titulo, $texto, $fecha, $idNoticia]);
        echo "Noticia actualizada con éxito!";
    }
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Noticia</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<h1>Editar Noticia</h1>

<div class="container">
    <form action="editar_noticia.php?id=<?php echo $idNoticia; ?>" method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label for="titulo">Título</label>
            <input type="text" class="form-control" id="titulo" name="titulo" value="<?php echo htmlspecialchars($noticia['titulo']); ?>" required>
        </div>
        <div class="form-group">
            <label for="texto">Texto</label>
            <textarea class="form-control" id="texto" name="texto" required><?php echo htmlspecialchars($noticia['texto']); ?></textarea>
        </div>
        <div class="form-group">
            <label for="imagen">Imagen</label>
            <input type="file" class="form-control" id="imagen" name="imagen">
            <small class="form-text text-muted">Deja este campo vacío si no deseas cambiar la imagen.</small>
        </div>
        <button type="submit" name="submit" class="btn btn-primary">Actualizar Noticia</button>
    </form>
</div>

</body>
</html>

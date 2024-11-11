<?php
// Conexión a la base de datos
include '../../../db/conexion.php';
include '../../navbar/navbar.php';

// Insertar nueva noticia
if (isset($_POST['submit'])) {
    $titulo = $_POST['titulo'];
    $texto = $_POST['texto'];
    $fecha = date('Y-m-d'); // Fecha actual
    $imagen = $_FILES['imagen']['name'];
    $tmp_name = $_FILES['imagen']['tmp_name'];
    $ruta = "../../../assets/images/" . $imagen;
    
    if (move_uploaded_file($tmp_name, $ruta)) {
        // Insertar en la base de datos
        $sql = "INSERT INTO noticias (titulo, imagen, texto, fecha, idUser) VALUES ('$titulo', '$imagen', '$texto', '$fecha', '11')";
        // Usamos el método 'exec' de PDO para insertar datos
        $stmt = $conn->prepare($sql);
        if ($stmt->execute()) {
            echo "Noticia agregada con éxito!";
        } else {
            echo "Error: No se pudo agregar la noticia.";
        }
    }
}

// Obtener las noticias con PDO
$sql = "SELECT * FROM noticias";
$stmt = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administración de Noticias</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Agregar jQuery y Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>

<h1>Administrar Noticias</h1>

<!-- Formulario para crear noticia -->
<div class="container">
    <form action="noticias-administracion.php" method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label for="titulo">Título</label>
            <input type="text" class="form-control" id="titulo" name="titulo" required>
        </div>
        <div class="form-group">
            <label for="texto">Texto</label>
            <textarea class="form-control" id="texto" name="texto" required></textarea>
        </div>
        <div class="form-group">
            <label for="imagen">Imagen</label>
            <input type="file" class="form-control" id="imagen" name="imagen" required>
        </div>
        <button type="submit" name="submit" class="btn btn-primary">Crear Noticia</button>
    </form>
</div>

<!-- Mostrar noticias -->
<div class="container mt-5">
    <div class="row">
        <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) { ?>
            <div class="col-md-4">
                <div class="card">
                    <img src="../../../assets/images/<?php echo $row['imagen']; ?>" class="card-img-top" alt="<?php echo $row['titulo']; ?>">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo $row['titulo']; ?></h5>
                        <a href="editar_noticia.php?id=<?php echo $row['idNoticia']; ?>" class="btn btn-warning">Editar</a>
                        <a href="eliminar_noticia.php?id=<?php echo $row['idNoticia']; ?>" class="btn btn-danger">Eliminar</a>
                        <!-- Botón para abrir el modal -->
                        <button type="button" class="btn btn-info" data-toggle="modal" data-target="#noticiaModal<?php echo $row['idNoticia']; ?>">
                            Ver Detalles
                        </button>
                    </div>
                </div>
            </div>

            <!-- Modal para ver detalles de la noticia -->
            <div class="modal fade" id="noticiaModal<?php echo $row['idNoticia']; ?>" tabindex="-1" role="dialog" aria-labelledby="noticiaModalLabel<?php echo $row['idNoticia']; ?>" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="noticiaModalLabel<?php echo $row['idNoticia']; ?>"><?php echo $row['titulo']; ?></h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <img src="../../../assets/images/<?php echo $row['imagen']; ?>" class="img-fluid mb-3" alt="<?php echo $row['titulo']; ?>">
                            <p><?php echo $row['texto']; ?></p>
                            <p><strong>Fecha:</strong> <?php echo $row['fecha']; ?></p>
                            <p><strong>Creado por:</strong> <?php echo $row['idUser']; ?></p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
</div>

</body>
</html>

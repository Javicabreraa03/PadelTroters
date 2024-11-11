<?php
// Conexión a la base de datos
include '../navbar/navbar.php';
include '../../db/conexion.php'; // Aquí se incluye el archivo de conexión, que usa $conn
// Obtener noticias desde la base de datos
$query = "SELECT * FROM noticias ORDER BY fecha DESC"; // Ordenar por fecha
$stmt = $conn->prepare($query); // Cambié $pdo por $conn
$stmt->execute();
$noticias = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Noticias</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Fondo y estilo general */
        body {
            background-color: black;
            color: white;
            padding-top: 100px; /* Ajuste para el navbar fijo */
        }

        h1 {
            color: gold; /* Color dorado */
            text-align: center;
            margin-top: 20px;
            font-weight: bold;
            text-decoration: underline;
            font-size: 2.5rem;
            font-style: italic; /* Cursiva */
        }

        .card {
            background-color: white;
            color: black;
            border: none;
            margin: 20px;
            cursor: pointer;
            transition: transform 0.3s ease;
            height: 400px; /* Aumenta el tamaño de la carta */
        }

        .card:hover {
            transform: scale(1.05);
        }

        .card-img-top {
            height: 200px; /* Ajusta la imagen para que tenga un tamaño adecuado */
            object-fit: cover;
        }

        .card-body {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            height: 100%; /* Asegura que la carta ocupe toda la altura disponible */
        }

        .card-title {
            font-size: 1.2rem;
            font-weight: bold;
            margin-bottom: auto; /* Empuja el texto hacia la parte superior */
        }

        .card-text {
            display: none; /* Oculta la descripción */
        }

        .btn-read-more {
            background-color: transparent;
            border: 1px solid #000;
            color: #736;
            padding: 10px 20px;
            text-transform: uppercase;
            font-weight: bold;
            transition: all 0.3s ease;
            align-self: flex-start; /* Alinea el botón al inicio */
        }

        .btn-read-more:hover {
            background-color: #000;
            color: white;
            border: 1px solid #736;
        }

        /* Estilos del modal */
        .modal-dialog {
            max-width: 70%; /* Ajusta el ancho del modal al 90% */
            margin: 10% auto; /* Centra el modal */
        }

        .modal-content {
            background-color: #000;
            color: white;
            height: 80vh; /* Altura del modal al 90% de la pantalla */
        }

        .modal-header {
            background-color: #333;
            color: white;
        }

        .modal-body {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 80%; /* Hace que el contenido del modal ocupe toda la altura */
        }

        .modal-body img {
            max-width: 80%; /* Hace que la imagen ocupe el 100% del ancho del modal */
            max-height: 70%; /* Limita la altura máxima de la imagen al 80% */
            object-fit: contain; /* Asegura que la imagen se ajuste sin deformarse */
        }
    </style>
</head>
<body>

<h1>NOTICIAS</h1>

<div class="container">
    <div class="row">
        <?php foreach ($noticias as $noticia): ?>
            <div class="col-md-4">
                <div class="card" style="background-color: white;">
                    <img src="../../assets/images/<?= $noticia['imagen']; ?>" class="card-img-top" alt="<?= $noticia['titulo']; ?>">
                    <div class="card-body">
                        <h5 class="card-title"><?= htmlspecialchars($noticia['titulo']); ?></h5>
                        <p class="card-text"><?= htmlspecialchars($noticia['texto']); ?></p>
                        <p class="card-text"><small class="text-muted"><?= $noticia['fecha']; ?></small></p>
                        <!-- Botón para abrir el modal -->
                        <button type="button" class="btn btn-info" data-toggle="modal" data-target="#modalNoticia<?= $noticia['idNoticia']; ?>">
                            Leer más
                        </button>
                    </div>
                </div>
            </div>

            <!-- Modal para esta noticia -->
            <div class="modal fade" id="modalNoticia<?= $noticia['idNoticia']; ?>" tabindex="-1" role="dialog" aria-labelledby="modalNoticiaLabel<?= $noticia['idNoticia']; ?>" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalNoticiaLabel<?= $noticia['idNoticia']; ?>"><?= htmlspecialchars($noticia['titulo']); ?></h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <img src="../../assets/images/<?= $noticia['imagen']; ?>" class="img-fluid mb-3" alt="<?= $noticia['titulo']; ?>">
                            <p><?= htmlspecialchars($noticia['texto']); ?></p>
                            <p><strong>Fecha de publicación:</strong> <?= $noticia['fecha']; ?></p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        </div>
                    </div>
                </div>
            </div>

        <?php endforeach; ?>
    </div>
</div>

<!-- Agregar jQuery y Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>

<?php include '../footer/footer.php'; ?>

</body>
</html>

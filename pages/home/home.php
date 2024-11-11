<!--<?php
 //session_start(); Iniciar la sesión para verificar el rol del usuario

// Aquí puedes definir un rol de prueba si no has implementado el login aún.
// Por ejemplo:
// $_SESSION['user'] = 'exampleUser'; $_SESSION['rol'] = 'user';
// session_destroy();
?>-->

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio PadelTroters</title>
    <!-- Enlace a Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <style>
        /* Fondo y estilo general */
        body {
            background-color: black;
            color: white;
            padding-top: 100px; /* Ajuste para el navbar fijo */
        }

        /* Estilos de los encabezados */
        h1, h2 {
            color: gold;
            text-align: center;
            text-decoration: underline;
        }

        /* Estilos de las tarjetas de noticias */
        .card {
            background-color: white;
            color: black;
            border: 1px solid #333;
            margin-bottom: 20px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card:hover {
            transform: scale(1.05);
            box-shadow: 0 8px 16px rgba(255, 215, 0, 0.3);
        }

        /* Estilo del botón "Leer más" */
        .btn-read-more {
            background-color: gold;
            color: black;
            border: none;
            transition: all 0.3s ease;
        }

        .btn-read-more:hover {
            background-color: darkgoldenrod;
            color: white;
            font-weight: bold;
        }

        /* Estilos del modal */
        .modal-content {
            background-color: black;
            color: white;
        }

        .modal-header h3 {
            color: gold;
        }

        .btn-secondary {
            background-color: darkgray;
            color: white;
            border: none;
        }
        
        .btn-secondary:hover {
            background-color: gray;
        }
    </style>
</head>
<body>

    <!-- Incluir el navbar -->
    <?php include '../navbar/navbar.php';?>

    <!-- Contenido principal de la página de inicio -->
    <div class="container mt-4">
        <h1>¡Bienvenido a PadelTroters!</h1>
        <p>Aquí podrás encontrar informanción totalmente al día sobre el Premier Padel. 
            ¡Esperemos que disfrutes de nuestra web!
        </p>

        <!-- Sección "Sobre Nosotros" -->
        <section class="my-5">
            <h2>Sobre Nosotros</h2>
            <p>Somos un grupo de amigos encargados de llevar y actualizar la web de nuestra pasión, el PADEL. Empezamos este proyecto hace un par de años
                y hemos conseguido una comunidad que está con nosotros mano a mano siempre. Ofrecemos también servicios de citaciones para entrenos en nuestro club de San Roque.
                Ofrecemos un servicio exclusivo para nuestro cliente, solo tiene que solicitárnoslo a través de la web y nos pondremos en contacto en un máximo de 1 día laboral.
            </p>
        </section>

        <!-- Sección "Últimas Noticias" -->
        <section class="my-5">
            <h2>Últimas Noticias</h2>
            <div class="row">

                <!-- NOTICIA 1 -->
                <div class="col-md-4">
                    <div class="card" data-toggle="modal" data-target="#noticiaModal1">
                        <img src="/trabajofinalphp/assets/images/padel-dubai.jpg" class="card-img-top" alt="Dubai Premier Padel">
                        <div class="card-body">
                            <h5 class="card-title">El Dubai Premier Padel P1 arranca con emocionantes batallas</h5>
                            <button class="btn-read-more">Leer más</button>
                        </div>
                    </div>
                </div>

                <!-- NOTICIA 2 -->
                <div class="col-md-4">
                    <div class="card" data-toggle="modal" data-target="#noticiaModal2">
                        <img src="/trabajofinalphp/assets/images/masterbcn.webp" class="card-img-top" alt="Premier Padel Finals">
                        <div class="card-body">
                            <h5 class="card-title">Premier Padel Finals: Los 16 mejores jugadores competirán por el título de 2024 en Barcelona</h5>
                            <button class="btn-read-more">Leer más</button>
                        </div>
                    </div>
                </div>

                <!-- NOTICIA 3 -->
                <div class="col-md-4">
                    <div class="card" data-toggle="modal" data-target="#noticiaModal3">
                        <img src="/trabajofinalphp/assets/images/beaydelfi.webp" class="card-img-top" alt="Noticia 3">
                        <div class="card-body">
                            <h5 class="card-title">La final masculina hace historia, la femenina la reescribe</h5>
                            <button class="btn-read-more">Leer más</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal de Noticia 1 -->
            <div class="modal fade" id="noticiaModal1" tabindex="-1" role="dialog" aria-labelledby="noticiaModal1Label" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h3 class="modal-title" id="noticiaModal1Label">El Dubai Premier Padel P1 arranca con emocionantes batallas</h3>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <img src="/trabajofinalphp/assets/images/padel-dubai.jpg" alt="Dubai Premier Padel" class="img-fluid">
                            <p><h4>¡Bienvenidos a la jornada inaugural del Dubai Premier Padel P1!... </h4></p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal de Noticia 2 -->
            <div class="modal fade" id="noticiaModal2" tabindex="-1" role="dialog" aria-labelledby="noticiaModal2Label" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h3 class="modal-title" id="noticiaModal2Label">Premier Padel Finals: Los 16 mejores jugadores competirán...</h3>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <img src="/trabajofinalphp/assets/images/masterbcn.webp" alt="Premier Padel Finals" class="img-fluid">
                            <p><h4>Este evento de élite reunirá a los mejores jugadores del mundo...</h4></p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal de Noticia 3 -->
            <div class="modal fade" id="noticiaModal3" tabindex="-1" role="dialog" aria-labelledby="noticiaModal3Label" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h3 class="modal-title" id="noticiaModal3Label">La final masculina hace historia...</h3>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <img src="/trabajofinalphp/assets/images/beaydelfi.webp" alt="Noticia 3" class="img-fluid">
                            <p><h4>Las semifinales de hoy en el Dubai Premier Padel P1 nos han dejado emociones intensas...</h4></p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

 <?php include '../footer/footer.php';?>

    <!-- Enlace a los scripts de Bootstrap y jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
</body>
</html>



<?php
session_start();

// Definir la página actual para marcarla como activa
$page = basename($_SERVER['PHP_SELF'], ".php");
?>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
  <a class="navbar-brand" href="/trabajofinalphp/pages/home/home.php">PADEL TROTERS</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbarNav">
    <ul class="navbar-nav ml-auto"> <!-- Cambiado ml-auto para pegarlo a la derecha -->
      <!-- Links visibles para todos los usuarios -->
      <li class="nav-item">
        <a class="nav-link <?php echo ($page == 'index') ? 'active' : ''; ?>" href="/trabajofinalphp/pages/home/home.php">Inicio</a>
      </li>
      <li class="nav-item">
        <a class="nav-link <?php echo ($page == 'noticias') ? 'active' : ''; ?>" href="/trabajofinalphp/pages/noticias/noticias.php">Noticias</a>
      </li>

      <?php if (!isset($_SESSION['user']) || $_SESSION['rol'] === 'guest'): ?>
        <!-- Opciones para visitantes no registrados -->
        <li class="nav-item">
          <a class="nav-link <?php echo ($page == 'registro') ? 'active' : ''; ?>" href="/trabajofinalphp/pages/registro/registro.php">Registro</a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?php echo ($page == 'login') ? 'active' : ''; ?>" href="/trabajofinalphp/index.php">Iniciar sesión</a>
        </li>
      <?php elseif ($_SESSION['rol'] === 'user'): ?>
        <!-- Opciones exclusivas para usuarios registrados (rol: user) -->
        <li class="nav-item">
          <a class="nav-link <?php echo ($page == 'citaciones') ? 'active' : ''; ?>" href="/trabajofinalphp/pages/citaciones/citaciones.php">Citaciones</a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?php echo ($page == 'perfil') ? 'active' : ''; ?>" href="/trabajofinalphp/pages/perfil/perfil.php">Perfil</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="/trabajofinalphp/pages/logout/logout.php">Cerrar sesión</a>
        </li>
      <?php elseif ($_SESSION['rol'] === 'admin'): ?>
        <!-- Opciones exclusivas para administradores (rol: admin) -->
        <li class="nav-item">
          <a class="nav-link <?php echo ($page == 'usuarios-administracion') ? 'active' : ''; ?>" href="/trabajofinalphp/pages/admin/user/usuarios-administracion.php">Usuarios</a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?php echo ($page == 'citaciones-administracion') ? 'active' : ''; ?>" href="/trabajofinalphp/pages/admin/citaciones/citaciones-administracion.php">Citas Admin</a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?php echo ($page == 'noticias-administracion') ? 'active' : ''; ?>" href="/trabajofinalphp/pages/admin/noticias/noticias-administracion.php">Noticias Admin</a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?php echo ($page == 'perfil') ? 'active' : ''; ?>" href="/trabajofinalphp/pages/perfil/perfil.php">Perfil</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="/trabajofinalphp/pages/logout/logout.php">Cerrar sesión</a>
        </li>
      <?php endif; ?>
    </ul>
  </div>
</nav>

<style>
  /* Estilos generales para la barra de navegación */
  .navbar {
    position: fixed;
    top: 0;
    width: 100%;
    z-index: 1000;
    background-color: black;
    border-bottom: 2px solid gold;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
    transition: all 0.3s ease;
    padding-top: 15px;
    padding-bottom: 15px;
  }

  /* Estilo para el título */
  .navbar-brand {
    color: gold !important; /* Asegura el color dorado */
    font-weight: bold !important; /* Negrita */
    font-family: 'Arial', sans-serif;
    font-size: 1.3rem;
    text-transform: uppercase;
    font-style: italic !important; /* Cursiva */
    text-decoration: underline !important; /* Subrayado */
    margin-right: auto;
    padding-left: 10px;
    position: absolute;
  }

  .navbar-nav .nav-item {
    margin: 0 15px;
  }

  .navbar-nav .nav-link {
    color: gold !important;
    font-family: 'Arial', sans-serif;
    text-transform: uppercase;
    font-style: italic;
    font-size: 1.2rem;
    transition: all 0.3s ease;
  }

  .navbar-nav .nav-link:hover {
    font-weight: bold;
    text-decoration: underline;
  }

  .navbar-nav .nav-link.active {
    font-weight: bold;
    text-decoration: underline;
  }

  /* Efecto para la opacidad cuando se hace scroll */
  .navbar.scrolled {
    opacity: 0.85;
    background-color: rgba(0, 0, 0, 0.85);
  }

  /* Ajustar el margen superior del contenido */
  body {
    padding-top: 100px;
  }

  /* Estilos para el comportamiento del menú desplegable en pantallas pequeñas */
   /* Estilos para el comportamiento del menú desplegable en pantallas pequeñas */
   @media (max-width: 1300px) {
    .navbar-nav {
      text-align: center;
      width: 100%;
      padding-top: 15px;
      padding-bottom: 15px;
    }

    .navbar-nav .nav-item {
      width: 100%;
      margin: 10px 0;
    }

    .navbar-toggler {
      border-color: gold;
      margin-right: 15px;
      margin-left:auto; /* Coloca el toggler a la derecha */
    }

    .navbar {
      height: auto; /* Aumenta la altura para que sea más visible cuando el toggler se muestra */
      padding-top: 20px;
      padding-bottom: 20px;
    }
  }

  /* Estilo para la barra de navegación en pantallas grandes */
  @media (min-width: 1301px) {
    .navbar-nav {
      justify-content: center; /* Centra los enlaces de la barra de navegación */
    }
  }
</style>

<script>
  window.onscroll = function() {
    let navbar = document.querySelector('.navbar');
    if (window.scrollY > 50) {
      navbar.classList.add('scrolled');
    } else {
      navbar.classList.remove('scrolled');
    }
  };
</script>



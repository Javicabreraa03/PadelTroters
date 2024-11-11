<?php 
// Conexión a la base de datos
include '../../../db/conexion.php';
include '../../navbar/navbar.php';

// Obtener todos los usuarios de las tablas users_data y users_login
$query = $conn->prepare("
    SELECT ud.idUser, ud.nombre, ud.apellidos, ud.email, ud.telefono, ud.fecha_nacimiento, ud.direccion, ud.sexo,
           ul.password, ul.rol
    FROM users_data ud
    JOIN users_login ul ON ud.idUser = ul.idUser
");
$query->execute();
$usuarios = $query->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administración de Usuarios</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>

<div class="container">
    <h1>Administración de Usuarios</h1>
    
    <!-- Tabla de usuarios -->
    <table class="table table-dark table-bordered table-striped">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Apellidos</th>
                <th>Email</th>
                <th>Teléfono</th>
                <th>Fecha de Nacimiento</th>
                <th>Dirección</th>
                <th>Sexo</th>
                <th>Rol</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($usuarios as $usuario): ?>
                <tr>
                    <td><?= $usuario['nombre'] ?></td>
                    <td><?= $usuario['apellidos'] ?></td>
                    <td><?= $usuario['email'] ?></td>
                    <td><?= $usuario['telefono'] ?></td>
                    <td><?= $usuario['fecha_nacimiento'] ?></td>
                    <td><?= $usuario['direccion'] ?></td>
                    <td><?= $usuario['sexo'] ?></td>
                    <td><?= $usuario['rol'] ?></td>
                    <td>
                        <!-- Botón para abrir el modal de edición -->
                        <button class="btn btn-warning btn-sm" onclick="openEditModal(<?= htmlspecialchars(json_encode($usuario)) ?>)">Modificar</button>

                        <!-- Botón de eliminación con confirmación -->
                        <button class="btn btn-danger btn-sm" onclick="confirmDelete(<?= $usuario['idUser'] ?>)">Eliminar</button>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

<!-- Botón para abrir el modal de creación de usuario -->
<button class="btn btn-success" data-toggle="modal" data-target="#crearUsuarioModal">Crear Usuario</button>
</div>

<!-- Modal para crear un nuevo usuario -->
<div class="modal fade" id="crearUsuarioModal" tabindex="-1" role="dialog" aria-labelledby="crearUsuarioModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="crear_usuario.php" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title" id="crearUsuarioModalLabel">Crear Nuevo Usuario</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="nombre">Nombre</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" required>
                    </div>
                    <div class="form-group">
                        <label for="apellidos">Apellidos</label>
                        <input type="text" class="form-control" id="apellidos" name="apellidos" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="telefono">Teléfono</label>
                        <input type="text" class="form-control" id="telefono" name="telefono" required>
                    </div>
                    <div class="form-group">
                        <label for="fecha_nacimiento">Fecha de Nacimiento</label>
                        <input type="date" class="form-control" id="fecha_nacimiento" name="fecha_nacimiento" required>
                    </div>
                    <div class="form-group">
                        <label for="direccion">Dirección</label>
                        <input type="text" class="form-control" id="direccion" name="direccion" required>
                    </div>
                    <div class="form-group">
                        <label for="sexo">Sexo</label>
                        <select class="form-control" id="sexo" name="sexo" required>
                            <option value="Masculino">Masculino</option>
                            <option value="Femenino">Femenino</option>
                            <option value="Otro">Otro</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="password">Contraseña</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <div class="form-group">
                        <label for="rol">Rol</label>
                        <select class="form-control" id="rol" name="rol" required>
                            <option value="user">User</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary">Crear Usuario</button>
                </div>
            </form>
        </div>
    </div>
</div>
</div>


<!-- Modal para editar usuario -->
<div class="modal fade" id="editUsuarioModal" tabindex="-1" role="dialog" aria-labelledby="editUsuarioModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="editUserForm" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title" id="editUsuarioModalLabel">Editar Usuario</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="idUser" id="editUserId">
                    <div class="form-group">
                        <label for="editNombre">Nombre</label>
                        <input type="text" class="form-control" id="editNombre" name="nombre" required>
                    </div>
                    <div class="form-group">
                        <label for="editApellidos">Apellidos</label>
                        <input type="text" class="form-control" id="editApellidos" name="apellidos" required>
                    </div>
                    <div class="form-group">
                        <label for="editEmail">Email</label>
                        <input type="email" class="form-control" id="editEmail" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="editTelefono">Teléfono</label>
                        <input type="text" class="form-control" id="editTelefono" name="telefono" required>
                    </div>
                    <div class="form-group">
                        <label for="editFechaNacimiento">Fecha de Nacimiento</label>
                        <input type="date" class="form-control" id="editFechaNacimiento" name="fecha_nacimiento" required>
                    </div>
                    <div class="form-group">
                        <label for="editDireccion">Dirección</label>
                        <input type="text" class="form-control" id="editDireccion" name="direccion" required>
                    </div>
                    <div class="form-group">
                        <label for="editSexo">Sexo</label>
                        <select class="form-control" id="editSexo" name="sexo" required>
                            <option value="Masculino">Masculino</option>
                            <option value="Femenino">Femenino</option>
                            <option value="Otro">Otro</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="editRole">Rol</label>
                        <select class="form-control" id="editRol" name="rol" required>
                            <option value="user">User</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-primary" onclick="updateUser()">Guardar Cambios</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
   // Función para abrir el modal de edición y cargar los datos del usuario
function openEditModal(usuario) {
    document.getElementById('editUserId').value = usuario.idUser;
    document.getElementById('editNombre').value = usuario.nombre;
    document.getElementById('editApellidos').value = usuario.apellidos;
    document.getElementById('editEmail').value = usuario.email;
    document.getElementById('editTelefono').value = usuario.telefono;
    document.getElementById('editFechaNacimiento').value = usuario.fecha_nacimiento;
    document.getElementById('editDireccion').value = usuario.direccion;
    document.getElementById('editSexo').value = usuario.sexo;
    document.getElementById('editRol').value = usuario.rol;
    $('#editUsuarioModal').modal('show');
}

// Función para confirmar la eliminación del usuario
function confirmDelete(idUser) {
    if (confirm("¿Está seguro de que desea eliminar este usuario?")) {
        $.post('eliminar_usuario.php', { idUser: idUser }, function(response) {
            alert(response.message);
            location.reload();
        }, 'json');
    }
}

// Función para actualizar los datos del usuario mediante AJAX
function updateUser() {
    $.post('editar_usuario.php', $('#editUserForm').serialize(), function(response) {
        alert(response.message);
        $('#editUsuarioModal').modal('hide');
        location.reload();
    }, 'json');
}

</script>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

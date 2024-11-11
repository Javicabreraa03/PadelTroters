<?php 
// Conexión a la base de datos
include '../../../db/conexion.php';
include '../../navbar/navbar.php';

// Obtener todas las citas
$query = $conn->prepare("SELECT * FROM citas");
$query->execute();
$citas = $query->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administración de Citas</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>

<div class="container">
    <h1>Administración de Citas</h1>
    
    <!-- Tabla de citas -->
    <table class="table table-dark table-bordered table-striped">
        <thead>
            <tr>
                <th>ID Cita</th>
                <th>Fecha</th>
                <th>Usuario</th>
                <th>Motivo</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($citas as $cita): ?>
                <tr>
                    <td><?= $cita['idCita'] ?></td>
                    <td><?= $cita['fecha_cita'] ?></td>
                    <td><?= $cita['idUser'] ?></td>
                    <td><?= $cita['motivo_cita'] ?></td>
                    <td>
                        <!-- Botón para abrir el modal de edición -->
                        <button class="btn btn-warning btn-sm" onclick="openEditModal(<?= htmlspecialchars(json_encode($cita)) ?>)">Modificar</button>

                        <!-- Botón de eliminación con confirmación -->
                        <button class="btn btn-danger btn-sm" onclick="confirmDelete(<?= $cita['idCita'] ?>, '<?= $cita['fecha_cita'] ?>')">Eliminar</button>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Botón para abrir el modal de creación de cita -->
    <button class="btn btn-success" data-toggle="modal" data-target="#crearCitaModal">Crear Cita</button>
</div>

<!-- Modal para crear una nueva cita -->
<div class="modal fade" id="crearCitaModal" tabindex="-1" role="dialog" aria-labelledby="crearCitaModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="crear_cita.php" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title" id="crearCitaModalLabel">Crear Nueva Cita</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="fecha">Fecha</label>
                        <input type="date" class="form-control" id="fecha" name="fecha" required>
                    </div>
                    <div class="form-group">
                        <label for="hora">Hora</label>
                        <input type="time" class="form-control" id="hora" name="hora" required>
                    </div>
                    <div class="form-group">
                    <label for="usuario">Usuario</label>
                    <input type="text" class="form-control" id="nombreUsuario" name="nombreUsuario" required>
                    </div>
                    <div class="form-group">
                        <label for="motivo">Motivo</label>
                        <input type="text" class="form-control" id="motivo" name="motivo" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary">Crear Cita</button>
                </div>
            </form>
        </div>
    </div>
</div><!-- Modal para editar cita -->
<div class="modal fade" id="editCitaModal" tabindex="-1" role="dialog" aria-labelledby="editCitaModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="editCitaForm" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title" id="editCitaModalLabel">Editar Cita</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="idCita" id="editCitaId">
                    <div class="form-group">
                        <label for="editFecha">Fecha</label>
                        <input type="date" class="form-control" id="editFecha" name="fecha_cita" required>
                    </div>
                    <div class="form-group">
                        <label for="editHora">Hora</label>
                        <input type="time" class="form-control" id="editHora" name="hora" required>
                    </div>
                    <div class="form-group">
                        <label for="editUsuario">Usuario</label>
                        <input type="text" class="form-control" id="editUsuario" name="nombreUsuario" required>
                    </div>
                    <div class="form-group">
                        <label for="editMotivo">Motivo</label>
                        <input type="text" class="form-control" id="editMotivo" name="motivo_cita" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-primary" onclick="updateCita(event)">Guardar Cambios</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Función para actualizar los datos de la cita mediante AJAX
function updateCita(event) {
    event.preventDefault(); // Evita la recarga del formulario

    $.ajax({
        type: 'POST',
        url: 'editar_cita.php',
        data: $('#editCitaForm').serialize(),
        dataType: 'json',
        success: function(response) {
            alert(response.message);
            if (response.message === "Cita actualizada exitosamente") {
                $('#editCitaModal').modal('hide');
                location.reload(); // Refresca la página para mostrar los cambios en la tabla
            }
        },
        error: function(xhr, status, error) {
            alert("Error al actualizar la cita: " + error);
        }
    });
}

// Función para abrir el modal de edición y cargar los datos de la cita
function openEditModal(cita) {
    document.getElementById('editCitaId').value = cita.idCita;
    document.getElementById('editFecha').value = cita.fecha_cita;
    document.getElementById('editHora').value = cita.hora;
    document.getElementById('editUsuario').value = cita.idUser;
    document.getElementById('editMotivo').value = cita.motivo_cita;
    $('#editCitaModal').modal('show');
}
</script>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
// Función para confirmar la eliminación de la cita
function confirmDelete(idCita, fecha) {
    let fechaHoy = new Date().toISOString().split('T')[0];
    if (fecha < fechaHoy) {
        alert("No se puede eliminar una cita pasada.");
    } else if (confirm("¿Está seguro de que desea eliminar esta cita?")) {
        $.post('eliminar_cita.php', { idCita: idCita }, function(response) {
            alert(response.message);
            location.reload();
        }, 'json');
    }
}</script>
</body>
</html>

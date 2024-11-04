<?php
session_start();
require_once '../controlador/alquilerController.php';
if (!isset($_SESSION['user'])) {
    // Redirige al login si no ha iniciado sesión
    header("Location: ../index.php");
    exit();
}
$alquilerController = new AlquilerController();
$mensaje = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion'])) {
    switch ($_POST['accion']) {
        case 'agregar':
            $fecha = $_POST['fecha'] ?? '';
            $total = $_POST['total'] ?? 0;
            $id_empleado = $_POST['id_empleado'] ?? 0;
            $id_cliente = $_POST['id_cliente'] ?? 0;
            $id_pelicula = $_POST['id_pelicula'] ?? 0;

            $resultado = $alquilerController->agregarAlquiler($fecha, $total, $id_empleado, $id_cliente, $id_pelicula);
            $mensaje = $resultado['message'];
            break;

        case 'eliminar':
            $id = $_POST['id'] ?? '';
            $resultado = $alquilerController->eliminarAlquiler($id);
            $mensaje = $resultado['message'];
            break;

        case 'actualizar':
            $data = [
                'id' => $_POST['id'],
                'fecha' => $_POST['fecha'],
                'total' => $_POST['total'],
                'id_empleado' => $_POST['id_empleado'],
                'id_cliente' => $_POST['id_cliente'],
                'id_pelicula' => $_POST['id_pelicula'],
            ];

            $resultado = $alquilerController->actualizarAlquiler($data);
            $mensaje = $resultado['message'];
            break;
    }
}

$alquileres = $alquilerController->listarAlquileres();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Lista de Alquileres</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>

<body>
    <div class="container mt-5">
        <h1 class="text-center">Registro y Lista de Alquileres</h1>

        <?php if ($mensaje): ?>
            <div class="alert alert-info"><?= htmlspecialchars($mensaje) ?></div>
        <?php endif; ?>

        <div class="row">
            <div class="col-md-4">
                <h3>Registrar Alquiler</h3>
                <form action="alquiler.php" method="POST" class="mb-4" onsubmit="return confirm('¿Está seguro de que desea agregar este alquiler?');">
                    <input type="hidden" name="accion" value="agregar">
                    <div class="form-group">
                        <label>Fecha <span class="text-danger">*</span></label>
                        <input type="date" name="fecha" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Total <span class="text-danger">*</span></label>
                        <input type="number" name="total" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>ID Empleado <span class="text-danger">*</span></label>
                        <input type="number" name="id_empleado" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>ID Cliente <span class="text-danger">*</span></label>
                        <input type="number" name="id_cliente" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>ID Película <span class="text-danger">*</span></label>
                        <input type="number" name="id_pelicula" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">Agregar Alquiler</button>
                </form>
            </div>

            <div class="col-md-8">
                <h3>Lista de Alquileres</h3>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID Venta</th>
                            <th>Fecha</th>
                            <th>Total</th>
                            <th>ID Empleado</th>
                            <th>ID Cliente</th>
                            <th>ID Película</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($alquileres as $alquiler): ?>
                            <tr>
                                <td><?= htmlspecialchars($alquiler['Id_Venta']) ?></td>
                                <td><?= htmlspecialchars($alquiler['Fecha']) ?></td>
                                <td><?= htmlspecialchars($alquiler['Total']) ?></td>
                                <td><?= htmlspecialchars($alquiler['Id_Empleado']) ?></td>
                                <td><?= htmlspecialchars($alquiler['Id_Cliente']) ?></td>
                                <td><?= htmlspecialchars($alquiler['Id_Pelicula']) ?></td>
                                <td>
                                    <button class="btn btn-warning btn-sm" data-toggle="modal" data-target="#editModal"
                                        data-id="<?= $alquiler['Id_Venta'] ?>"
                                        data-fecha="<?= htmlspecialchars($alquiler['Fecha']) ?>"
                                        data-total="<?= htmlspecialchars($alquiler['Total']) ?>"
                                        data-id_empleado="<?= htmlspecialchars($alquiler['Id_Empleado']) ?>"
                                        data-id_cliente="<?= htmlspecialchars($alquiler['Id_Cliente']) ?>"
                                        data-id_pelicula="<?= htmlspecialchars($alquiler['Id_Pelicula']) ?>">Modificar</button>

                                    <button class="btn btn-danger btn-sm" onclick="confirmDelete(<?= $alquiler['Id_Venta'] ?>)">Eliminar</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Modal de confirmación de eliminación -->
        <div class="modal fade" id="confirmDeleteModal" tabindex="-1" role="dialog" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="confirmDeleteModalLabel">Confirmar Eliminación</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        ¿Está seguro de que desea eliminar este alquiler?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <form id="deleteForm" action="alquiler.php" method="POST" style="display:inline;">
                            <input type="hidden" name="accion" value="eliminar">
                            <input type="hidden" name="id" id="alquilerId">
                            <button type="submit" class="btn btn-danger">Eliminar</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal de edición -->
        <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editModalLabel">Modificar Alquiler</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form id="editForm" action="alquiler.php" method="POST" onsubmit="return confirm('¿Está seguro de que desea actualizar este alquiler?');">
                        <div class="modal-body">
                            <input type="hidden" name="accion" value="actualizar">
                            <input type="hidden" name="id" id="editAlquilerId">
                            <div class="form-group">
                                <label>Fecha <span class="text-danger">*</span></label>
                                <input type="date" name="fecha" id="editFecha" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label>Total <span class="text-danger">*</span></label>
                                <input type="number" name="total" id="editTotal" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label>ID Empleado <span class="text-danger">*</span></label>
                                <input type="number" name="id_empleado" id="editIdEmpleado" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label>ID Cliente <span class="text-danger">*</span></label>
                                <input type="number" name="id_cliente" id="editIdCliente" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label>ID Película <span class="text-danger">*</span></label>
                                <input type="number" name="id_pelicula" id="editIdPelicula" class="form-control" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-primary">Actualizar Alquiler</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <script>
            $(document).ready(function() {
                $('#editModal').on('show.bs.modal', function(event) {
                    var button = $(event.relatedTarget);
                    var modal = $(this);
                    modal.find('#editAlquilerId').val(button.data('id'));
                    modal.find('#editFecha').val(button.data('fecha'));
                    modal.find('#editTotal').val(button.data('total'));
                    modal.find('#editIdEmpleado').val(button.data('id_empleado'));
                    modal.find('#editIdCliente').val(button.data('id_cliente'));
                    modal.find('#editIdPelicula').val(button.data('id_pelicula'));
                });

                window.confirmDelete = function(id) {
                    $('#alquilerId').val(id);
                    $('#confirmDeleteModal').modal('show');
                };
            });
        </script>
    </div>
</body>

</html>
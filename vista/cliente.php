<?php
session_start();
require_once '../controlador/clienteController.php';
if (!isset($_SESSION['user'])) {
    // Redirige al login si no ha iniciado sesión
    header("Location: ../index.php");
    exit();
}
$clienteController = new ClienteController();
$mensaje = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion'])) {
    switch ($_POST['accion']) {
        case 'agregar':
            $nombre = $_POST['nombre'] ?? '';
            $apellidos = $_POST['apellidos'] ?? '';
            $direccion = $_POST['direccion'] ?? '';
            $telefono = $_POST['telefono'] ?? '';
            $email = $_POST['email'] ?? '';

            $resultado = $clienteController->agregarCliente($nombre, $apellidos, $direccion, $telefono, $email);
            $mensaje = $resultado ? 'Cliente agregado exitosamente.' : 'Error al agregar el cliente.';
            break;

        case 'eliminar':
            $id = $_POST['id'] ?? '';
            $resultado = $clienteController->eliminarCliente($id);
            $mensaje = $resultado ? 'Cliente eliminado exitosamente.' : 'Error al eliminar el cliente.';
            break;

        case 'actualizar':
            $data = [
                'id' => $_POST['id'],
                'nombre' => $_POST['nombre'],
                'apellidos' => $_POST['apellidos'],
                'direccion' => $_POST['direccion'],
                'telefono' => $_POST['telefono'],
                'email' => $_POST['email'],
            ];

            $resultado = $clienteController->actualizarCliente($data);
            $mensaje = $resultado ? 'Cliente actualizado exitosamente.' : 'Error al actualizar el cliente.';
            break;
    }
}

$clientes = $clienteController->listarClientes();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Lista de Clientes</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>

<body>
    <div class="container mt-5">
        <h1 class="text-center">Registro y Lista de Clientes</h1>

        <?php if ($mensaje): ?>
            <div class="alert alert-info"><?= htmlspecialchars($mensaje) ?></div>
        <?php endif; ?>

        <div class="row">
            <div class="col-md-4">
                <h3>Registrar Cliente</h3>
                <form action="cliente.php" method="POST" class="mb-4" onsubmit="return confirm('¿Está seguro de que desea agregar este cliente?');">
                    <input type="hidden" name="accion" value="agregar">
                    <div class="form-group">
                        <label>Nombre <span class="text-danger">*</span></label>
                        <input type="text" name="nombre" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Apellidos</label>
                        <input type="text" name="apellidos" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Dirección</label>
                        <input type="text" name="direccion" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Teléfono</label>
                        <input type="text" name="telefono" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control">
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">Agregar Cliente</button>
                </form>
            </div>

            <div class="col-md-8">
                <h3>Lista de Clientes</h3>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Apellidos</th>
                            <th>Dirección</th>
                            <th>Teléfono</th>
                            <th>Email</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($clientes as $cliente): ?>
                            <tr>
                                <td><?= htmlspecialchars($cliente['Id_Cliente']) ?></td>
                                <td><?= htmlspecialchars($cliente['Nombre']) ?></td>
                                <td><?= htmlspecialchars($cliente['Apellidos']) ?></td>
                                <td><?= htmlspecialchars($cliente['Direccion']) ?></td>
                                <td><?= htmlspecialchars($cliente['Telefono']) ?></td>
                                <td><?= htmlspecialchars($cliente['Email']) ?></td>
                                <td>
                                    <button class="btn btn-warning btn-sm" data-toggle="modal" data-target="#editModal" data-id="<?= $cliente['Id_Cliente'] ?>"
                                        data-nombre="<?= htmlspecialchars($cliente['Nombre']) ?>"
                                        data-apellidos="<?= htmlspecialchars($cliente['Apellidos']) ?>"
                                        data-direccion="<?= htmlspecialchars($cliente['Direccion']) ?>"
                                        data-telefono="<?= htmlspecialchars($cliente['Telefono']) ?>"
                                        data-email="<?= htmlspecialchars($cliente['Email']) ?>">Modificar</button>

                                    <button class="btn btn-danger btn-sm" onclick="confirmDelete(<?= $cliente['Id_Cliente'] ?>)">Eliminar</button>
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
                        ¿Está seguro de que desea eliminar este cliente?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <form id="deleteForm" action="cliente.php" method="POST" style="display:inline;">
                            <input type="hidden" name="accion" value="eliminar">
                            <input type="hidden" name="id" id="clienteId">
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
                        <h5 class="modal-title" id="editModalLabel">Modificar Cliente</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form id="editForm" action="cliente.php" method="POST" onsubmit="return confirm('¿Está seguro de que desea actualizar este cliente?');">
                        <div class="modal-body">
                            <input type="hidden" name="accion" value="actualizar">
                            <input type="hidden" name="id" id="editClienteId">
                            <div class="form-group">
                                <label>Nombre <span class="text-danger">*</span></label>
                                <input type="text" name="nombre" id="editNombre" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label>Apellidos</label>
                                <input type="text" name="apellidos" id="editApellidos" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>Dirección</label>
                                <input type="text" name="direccion" id="editDireccion" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>Teléfono</label>
                                <input type="text" name="telefono" id="editTelefono" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>Email</label>
                                <input type="email" name="email" id="editEmail" class="form-control">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-primary">Actualizar Cliente</button>
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
                    modal.find('#editClienteId').val(button.data('id'));
                    modal.find('#editNombre').val(button.data('nombre'));
                    modal.find('#editApellidos').val(button.data('apellidos'));
                    modal.find('#editDireccion').val(button.data('direccion'));
                    modal.find('#editTelefono').val(button.data('telefono'));
                    modal.find('#editEmail').val(button.data('email'));
                });

                window.confirmDelete = function(id) {
                    $('#clienteId').val(id);
                    $('#confirmDeleteModal').modal('show');
                };
            });
        </script>
    </div>
</body>

</html>
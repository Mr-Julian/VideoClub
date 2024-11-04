<?php
session_start();
require_once '../controlador/empleadoController.php';

// Verificar si el usuario ha iniciado sesión y tiene el cargo adecuado
if (!isset($_SESSION['Cargo']) || $_SESSION['Cargo'] !== 'admin') {
    // Redirigir a la página de inicio con un mensaje de error (opcional)
    $_SESSION['error'] = 'No tienes permisos para acceder a esta página.';
    header("Location: ../index.php");
    exit();
}

$empleadoController = new EmpleadoController();
$mensaje = '';

// Manejo de las acciones del formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion'])) {
    switch ($_POST['accion']) {
        case 'agregar':
            $nombre = $_POST['nombre'] ?? '';
            $apellidos = $_POST['apellidos'] ?? '';
            $direccion = $_POST['direccion'] ?? '';
            $telefono = $_POST['telefono'] ?? '';
            $email = $_POST['email'] ?? '';
            $nombre_usuario = $_POST['nombre_usuario'] ?? '';
            $password = $_POST['password'] ?? '';
            $cargo = $_POST['cargo'] ?? '';

            $password_cifrada = password_hash($password, PASSWORD_DEFAULT);

            $resultado = $empleadoController->agregarEmpleado($nombre, $apellidos, $direccion, $telefono, $email, $nombre_usuario, $password_cifrada, $cargo);
            $mensaje = $resultado['message'];
            break;

        case 'eliminar':
            $id = $_POST['id'] ?? '';
            $resultado = $empleadoController->eliminarEmpleado($id);
            $mensaje = $resultado['message'];
            break;

        case 'actualizar':
            $data = [
                'id' => $_POST['id'],
                'nombre' => $_POST['nombre'],
                'apellidos' => $_POST['apellidos'],
                'direccion' => $_POST['direccion'],
                'telefono' => $_POST['telefono'],
                'email' => $_POST['email'],
                'nombre_usuario' => $_POST['nombre_usuario'],
                'cargo' => $_POST['cargo'],
            ];

            if (!empty($_POST['password'])) {
                $data['password'] = password_hash($_POST['password'], PASSWORD_DEFAULT);
            }

            $resultado = $empleadoController->actualizarEmpleado($data);
            $mensaje = $resultado['message'];
            break;
    }
}

$empleados = $empleadoController->listarEmpleados();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Lista de Empleados</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>

<body>
    <div class="container mt-5">
        <h1 class="text-center">Registro y Lista de Empleados</h1>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($_SESSION['error']) ?></div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <?php if ($mensaje): ?>
            <div class="alert alert-info"><?= htmlspecialchars($mensaje) ?></div>
        <?php endif; ?>

        <div class="row">
            <div class="col-md-4">
                <h3>Registrar Empleado</h3>
                <form action="empleado.php" method="POST" class="mb-4" onsubmit="return confirm('¿Está seguro de que desea agregar este empleado?');">
                    <input type="hidden" name="accion" value="agregar">
                    <div class="form-group">
                        <label>Nombre <span class="text-danger">*</span></label>
                        <input type="text" name="nombre" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Apellidos <span class="text-danger">*</span></label>
                        <input type="text" name="apellidos" class="form-control" required>
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
                    <div class="form-group">
                        <label>Nombre de Usuario</label>
                        <input type="text" name="nombre_usuario" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Contraseña</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Cargo</label>
                        <input type="text" name="cargo" class="form-control">
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">Agregar Empleado</button>
                </form>
            </div>

            <div class="col-md-8">
                <h3>Lista de Empleados</h3>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Apellidos</th>
                            <th>Dirección</th>
                            <th>Teléfono</th>
                            <th>Email</th>
                            <th>Nombre de Usuario</th>
                            <th>Cargo</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($empleados as $empleado): ?>
                            <tr>
                                <td><?= htmlspecialchars($empleado['Id_Empleado']) ?></td>
                                <td><?= htmlspecialchars($empleado['Nombre']) ?></td>
                                <td><?= htmlspecialchars($empleado['Apellidos']) ?></td>
                                <td><?= htmlspecialchars($empleado['Direccion']) ?></td>
                                <td><?= htmlspecialchars($empleado['Telefono']) ?></td>
                                <td><?= htmlspecialchars($empleado['Email']) ?></td>
                                <td><?= htmlspecialchars($empleado['Nombre_Usuario']) ?></td>
                                <td><?= htmlspecialchars($empleado['Cargo']) ?></td>
                                <td>
                                    <button class="btn btn-warning btn-sm" data-toggle="modal" data-target="#editModal" data-id="<?= $empleado['Id_Empleado'] ?>"
                                        data-nombre="<?= htmlspecialchars($empleado['Nombre']) ?>"
                                        data-apellidos="<?= htmlspecialchars($empleado['Apellidos']) ?>"
                                        data-direccion="<?= htmlspecialchars($empleado['Direccion']) ?>"
                                        data-telefono="<?= htmlspecialchars($empleado['Telefono']) ?>"
                                        data-email="<?= htmlspecialchars($empleado['Email']) ?>"
                                        data-nombre_usuario="<?= htmlspecialchars($empleado['Nombre_Usuario']) ?>"
                                        data-cargo="<?= htmlspecialchars($empleado['Cargo']) ?>">Modificar</button>

                                    <button class="btn btn-danger btn-sm" onclick="confirmDelete(<?= $empleado['Id_Empleado'] ?>)">Eliminar</button>
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
                        ¿Está seguro de que desea eliminar este empleado?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <form id="deleteForm" action="empleado.php" method="POST" style="display:inline;">
                            <input type="hidden" name="accion" value="eliminar">
                            <input type="hidden" name="id" id="employeeId">
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
                        <h5 class="modal-title" id="editModalLabel">Modificar Empleado</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form id="editForm" action="empleado.php" method="POST" onsubmit="return confirm('¿Está seguro de que desea actualizar este empleado?');">
                        <div class="modal-body">
                            <input type="hidden" name="accion" value="actualizar">
                            <input type="hidden" name="id" id="editEmployeeId">
                            <div class="form-group">
                                <label>Nombre <span class="text-danger">*</span></label>
                                <input type="text" name="nombre" id="editNombre" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label>Apellidos <span class="text-danger">*</span></label>
                                <input type="text" name="apellidos" id="editApellidos" class="form-control" required>
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
                            <div class="form-group">
                                <label>Nombre de Usuario</label>
                                <input type="text" name="nombre_usuario" id="editNombreUsuario" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>Contraseña</label>
                                <input type="password" name="password" id="editPassword" class="form-control">
                                <small class="form-text text-muted">Deja este campo vacío si no deseas cambiar la contraseña.</small>
                            </div>
                            <div class="form-group">
                                <label>Cargo</label>
                                <input type="text" name="cargo" id="editCargo" class="form-control">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-primary">Actualizar Empleado</button>
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
                    modal.find('#editEmployeeId').val(button.data('id'));
                    modal.find('#editNombre').val(button.data('nombre'));
                    modal.find('#editApellidos').val(button.data('apellidos'));
                    modal.find('#editDireccion').val(button.data('direccion'));
                    modal.find('#editTelefono').val(button.data('telefono'));
                    modal.find('#editEmail').val(button.data('email'));
                    modal.find('#editNombreUsuario').val(button.data('nombre_usuario'));
                    modal.find('#editPassword').val(''); // Limpiar el campo de contraseña
                    modal.find('#editCargo').val(button.data('cargo'));
                });

                window.confirmDelete = function(id) {
                    $('#employeeId').val(id);
                    $('#confirmDeleteModal').modal('show');
                };
            });
        </script>
    </div>
</body>

</html>
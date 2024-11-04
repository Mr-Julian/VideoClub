<?php
session_start();
require_once '../controlador/categoriaController.php';
if (!isset($_SESSION['user'])) {
    // Redirige al login si no ha iniciado sesión
    header("Location: ../index.php");
    exit();
}
$categoriaController = new CategoriaController();
$mensaje = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion'])) {
    switch ($_POST['accion']) {
        case 'agregar':
            $nombreCategoria = $_POST['nombre_categoria'] ?? '';

            $resultado = $categoriaController->agregarCategoria($nombreCategoria);
            $mensaje = $resultado['success'] ? 'Categoría agregada exitosamente.' : $resultado['message'];
            break;

        case 'eliminar':
            $id = $_POST['id'] ?? '';
            $resultado = $categoriaController->eliminarCategoria($id);
            $mensaje = $resultado['success'] ? 'Categoría eliminada exitosamente.' : $resultado['message'];
            break;

        case 'actualizar':
            $data = [
                'id' => $_POST['id'],
                'nombre_categoria' => $_POST['nombre_categoria'],
            ];

            $resultado = $categoriaController->actualizarCategoria($data);
            $mensaje = $resultado['success'] ? 'Categoría actualizada exitosamente.' : $resultado['message'];
            break;
    }
}

$categorias = $categoriaController->listarCategorias();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Lista de Categorías</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>

<body>
    <div class="container mt-5">
        <h1 class="text-center">Registro y Lista de Categorías</h1>

        <?php if ($mensaje): ?>
            <div class="alert alert-info"><?= htmlspecialchars($mensaje) ?></div>
        <?php endif; ?>

        <div class="row">
            <div class="col-md-4">
                <h3>Registrar Categoría</h3>
                <form action="categoria.php" method="POST" class="mb-4" onsubmit="return confirm('¿Está seguro de que desea agregar esta categoría?');">
                    <input type="hidden" name="accion" value="agregar">
                    <div class="form-group">
                        <label>Nombre de la Categoría <span class="text-danger">*</span></label>
                        <input type="text" name="nombre_categoria" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">Agregar Categoría</button>
                </form>
            </div>

            <div class="col-md-8">
                <h3>Lista de Categorías</h3>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre de la Categoría</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($categorias as $categoria): ?>
                            <tr>
                                <td><?= htmlspecialchars($categoria['Id_Categoria']) ?></td>
                                <td><?= htmlspecialchars($categoria['Nombre_Categoria']) ?></td>
                                <td>
                                    <button class="btn btn-warning btn-sm" data-toggle="modal" data-target="#editModal" data-id="<?= $categoria['Id_Categoria'] ?>"
                                        data-nombre_categoria="<?= htmlspecialchars($categoria['Nombre_Categoria']) ?>">Modificar</button>

                                    <button class="btn btn-danger btn-sm" onclick="confirmDelete(<?= $categoria['Id_Categoria'] ?>)">Eliminar</button>
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
                        ¿Está seguro de que desea eliminar esta categoría?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <form id="deleteForm" action="categoria.php" method="POST" style="display:inline;">
                            <input type="hidden" name="accion" value="eliminar">
                            <input type="hidden" name="id" id="categoriaId">
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
                        <h5 class="modal-title" id="editModalLabel">Modificar Categoría</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form id="editForm" action="categoria.php" method="POST" onsubmit="return confirm('¿Está seguro de que desea actualizar esta categoría?');">
                        <div class="modal-body">
                            <input type="hidden" name="accion" value="actualizar">
                            <input type="hidden" name="id" id="editCategoriaId">
                            <div class="form-group">
                                <label>Nombre de la Categoría <span class="text-danger">*</span></label>
                                <input type="text" name="nombre_categoria" id="editNombreCategoria" class="form-control" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-primary">Actualizar Categoría</button>
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
                    modal.find('#editCategoriaId').val(button.data('id'));
                    modal.find('#editNombreCategoria').val(button.data('nombre_categoria'));
                });

                window.confirmDelete = function(id) {
                    $('#categoriaId').val(id);
                    $('#confirmDeleteModal').modal('show');
                };
            });
        </script>
    </div>
</body>

</html>
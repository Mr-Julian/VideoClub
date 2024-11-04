<?php
session_start();
require_once '../controlador/peliculaController.php';
if (!isset($_SESSION['user'])) {
    // Redirige al login si no ha iniciado sesión
    header("Location: ../index.php");
    exit();
}
$peliculaController = new PeliculaController();
$mensaje = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion'])) {
    switch ($_POST['accion']) {
        case 'agregar':
            $titulo = $_POST['titulo'] ?? '';
            $descripcion = $_POST['descripcion'] ?? '';
            $clasificacion_de_edad = $_POST['clasificacion_de_edad'] ?? '';
            $cantidad_en_inventario = $_POST['cantidad_en_inventario'] ?? '';
            $id_categoria = $_POST['id_categoria'] ?? '';

            $resultado = $peliculaController->agregarPelicula($titulo, $descripcion, $clasificacion_de_edad, $cantidad_en_inventario, $id_categoria);
            $mensaje = $resultado['message'];
            break;

        case 'eliminar':
            $id = $_POST['id'] ?? '';
            $resultado = $peliculaController->eliminarPelicula($id);
            $mensaje = $resultado['message'];
            break;

        case 'actualizar':
            $data = [
                'id' => $_POST['id'],
                'titulo' => $_POST['titulo'],
                'descripcion' => $_POST['descripcion'],
                'clasificacion_de_edad' => $_POST['clasificacion_de_edad'],
                'cantidad_en_inventario' => $_POST['cantidad_en_inventario'],
                'id_categoria' => $_POST['id_categoria'],
            ];

            $resultado = $peliculaController->actualizarPelicula($data);
            $mensaje = $resultado['message'];
            break;
    }
}

$peliculas = $peliculaController->listarPeliculas();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Lista de Películas</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>

<body>
    <div class="container mt-5">
        <h1 class="text-center">Registro y Lista de Películas</h1>

        <?php if ($mensaje): ?>
            <div class="alert alert-info"><?= htmlspecialchars($mensaje) ?></div>
        <?php endif; ?>

        <div class="row">
            <div class="col-md-4">
                <h3>Registrar Película</h3>
                <form action="pelicula.php" method="POST" class="mb-4" onsubmit="return confirm('¿Está seguro de que desea agregar esta película?');">
                    <input type="hidden" name="accion" value="agregar">
                    <div class="form-group">
                        <label>Título <span class="text-danger">*</span></label>
                        <input type="text" name="titulo" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Descripción</label>
                        <textarea name="descripcion" class="form-control"></textarea>
                    </div>
                    <div class="form-group">
                        <label>Clasificación de Edad</label>
                        <input type="text" name="clasificacion_de_edad" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Cantidad en Inventario</label>
                        <input type="number" name="cantidad_en_inventario" class="form-control" min="0">
                    </div>
                    <div class="form-group">
                        <label>ID de Categoría</label>
                        <input type="text" name="id_categoria" class="form-control">
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">Agregar Película</button>
                </form>
            </div>

            <div class="col-md-8">
                <h3>Lista de Películas</h3>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Título</th>
                            <th>Descripción</th>
                            <th>Clasificación de Edad</th>
                            <th>Cantidad en Inventario</th>
                            <th>ID de Categoría</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($peliculas as $pelicula): ?>
                            <tr>
                                <td><?= htmlspecialchars($pelicula['Id_Pelicula']) ?></td>
                                <td><?= htmlspecialchars($pelicula['Titulo']) ?></td>
                                <td><?= htmlspecialchars($pelicula['Descripcion']) ?></td>
                                <td><?= htmlspecialchars($pelicula['Clasificacion_de_edad']) ?></td>
                                <td><?= htmlspecialchars($pelicula['Cantidad_en_inventario']) ?></td>
                                <td><?= htmlspecialchars($pelicula['Id_Categoria']) ?></td>
                                <td>
                                    <button class="btn btn-warning btn-sm" data-toggle="modal" data-target="#editModal" data-id="<?= $pelicula['Id_Pelicula'] ?>"
                                        data-titulo="<?= htmlspecialchars($pelicula['Titulo']) ?>"
                                        data-descripcion="<?= htmlspecialchars($pelicula['Descripcion']) ?>"
                                        data-clasificacion_de_edad="<?= htmlspecialchars($pelicula['Clasificacion_de_edad']) ?>"
                                        data-cantidad_en_inventario="<?= htmlspecialchars($pelicula['Cantidad_en_inventario']) ?>"
                                        data-id_categoria="<?= htmlspecialchars($pelicula['Id_Categoria']) ?>">Modificar</button>

                                    <button class="btn btn-danger btn-sm" onclick="confirmDelete(<?= $pelicula['Id_Pelicula'] ?>)">Eliminar</button>
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
                        ¿Está seguro de que desea eliminar esta película?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <form id="deleteForm" action="pelicula.php" method="POST" style="display:inline;">
                            <input type="hidden" name="accion" value="eliminar">
                            <input type="hidden" name="id" id="movieId">
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
                        <h5 class="modal-title" id="editModalLabel">Modificar Película</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form id="editForm" action="pelicula.php" method="POST" onsubmit="return confirm('¿Está seguro de que desea actualizar esta película?');">
                        <div class="modal-body">
                            <input type="hidden" name="accion" value="actualizar">
                            <input type="hidden" name="id" id="editMovieId">
                            <div class="form-group">
                                <label>Título <span class="text-danger">*</span></label>
                                <input type="text" name="titulo" id="editTitulo" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label>Descripción</label>
                                <textarea name="descripcion" id="editDescripcion" class="form-control"></textarea>
                            </div>
                            <div class="form-group">
                                <label>Clasificación de Edad</label>
                                <input type="text" name="clasificacion_de_edad" id="editClasificacionEdad" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>Cantidad en Inventario</label>
                                <input type="number" name="cantidad_en_inventario" id="editCantidadInventario" class="form-control" min="0">
                            </div>
                            <div class="form-group">
                                <label>ID de Categoría</label>
                                <input type="text" name="id_categoria" id="editIdCategoria" class="form-control">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-primary">Actualizar Película</button>
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
                    modal.find('#editMovieId').val(button.data('id'));
                    modal.find('#editTitulo').val(button.data('titulo'));
                    modal.find('#editDescripcion').val(button.data('descripcion'));
                    modal.find('#editClasificacionEdad').val(button.data('clasificacion_de_edad'));
                    modal.find('#editCantidadInventario').val(button.data('cantidad_en_inventario'));
                    modal.find('#editIdCategoria').val(button.data('id_categoria'));
                });

                window.confirmDelete = function(id) {
                    $('#movieId').val(id);
                    $('#confirmDeleteModal').modal('show');
                };
            });
        </script>
    </div>
</body>

</html>
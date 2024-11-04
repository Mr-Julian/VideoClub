<?php
require_once '../modelo/Pelicula.php'; // Asegúrate de tener el modelo Pelicula

class PeliculaController
{
    private $peliculaModel;

    public function __construct()
    {
        $this->peliculaModel = new Pelicula();
    }

    public function listarPeliculas()
    {
        return $this->peliculaModel->listarPeliculas(); // Implementa esta función en el modelo
    }

    public function agregarPelicula($titulo, $descripcion, $clasificacionEdad, $cantidadInventario, $idCategoria)
    {
        if (empty($titulo)) {
            return ['success' => false, 'message' => 'El título es obligatorio.'];
        }

        $resultado = $this->peliculaModel->agregarPelicula($titulo, $descripcion, $clasificacionEdad, $cantidadInventario, $idCategoria);

        return $resultado
            ? ['success' => true, 'message' => 'Película agregada exitosamente.']
            : ['success' => false, 'message' => 'Error al agregar la película.'];
    }

    public function obtenerPeliculaPorId($id)
    {
        if (empty($id)) {
            return ['success' => false, 'message' => 'El ID es obligatorio.'];
        }

        $pelicula = $this->peliculaModel->obtenerPeliculaPorId($id);
        return $pelicula ? ['success' => true, 'pelicula' => $pelicula] : ['success' => false, 'message' => 'Película no encontrada.'];
    }

    public function actualizarPelicula($data)
    {
        if (empty($data['id']) || empty($data['titulo'])) {
            return ['success' => false, 'message' => 'El ID y el título son obligatorios para actualizar.'];
        }

        $resultado = $this->peliculaModel->actualizarPelicula($data);

        return $resultado
            ? ['success' => true, 'message' => 'Película actualizada exitosamente.']
            : ['success' => false, 'message' => 'Error al actualizar la película.'];
    }

    public function eliminarPelicula($id)
    {
        if (empty($id)) {
            return ['success' => false, 'message' => 'El ID es obligatorio para eliminar.'];
        }

        $resultado = $this->peliculaModel->eliminarPelicula($id);

        return $resultado
            ? ['success' => true, 'message' => 'Película eliminada exitosamente.']
            : ['success' => false, 'message' => 'Error al eliminar la película.'];
    }
}

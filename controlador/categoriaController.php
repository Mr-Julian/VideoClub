<?php
require_once '../modelo/Categoria.php';

class CategoriaController
{
    private $categoriaModel;

    public function __construct()
    {
        $this->categoriaModel = new Categoria();
    }

    public function listarCategorias()
    {
        return $this->categoriaModel->listarCategorias();
    }

    public function agregarCategoria($nombreCategoria)
    {
        if (empty($nombreCategoria)) {
            return ['success' => false, 'message' => 'El nombre de la categoría es obligatorio.'];
        }

        $resultado = $this->categoriaModel->agregarCategoria($nombreCategoria);

        return $resultado
            ? ['success' => true, 'message' => 'Categoría agregada exitosamente.']
            : ['success' => false, 'message' => 'Error al agregar la categoría.'];
    }

    public function obtenerCategoriaPorId($id)
    {
        if (empty($id)) {
            return ['success' => false, 'message' => 'El ID es obligatorio.'];
        }

        $categoria = $this->categoriaModel->obtenerCategoriaPorId($id);
        return $categoria ? ['success' => true, 'categoria' => $categoria] : ['success' => false, 'message' => 'Categoría no encontrada.'];
    }

    public function actualizarCategoria($data)
    {
        if (empty($data['id']) || empty($data['nombre_categoria'])) {
            return ['success' => false, 'message' => 'El ID y el nombre de la categoría son obligatorios para actualizar.'];
        }

        $resultado = $this->categoriaModel->actualizarCategoria($data);

        return $resultado
            ? ['success' => true, 'message' => 'Categoría actualizada exitosamente.']
            : ['success' => false, 'message' => 'Error al actualizar la categoría.'];
    }

    public function eliminarCategoria($id)
    {
        if (empty($id)) {
            return ['success' => false, 'message' => 'El ID es obligatorio para eliminar.'];
        }

        $resultado = $this->categoriaModel->eliminarCategoria($id);

        return $resultado
            ? ['success' => true, 'message' => 'Categoría eliminada exitosamente.']
            : ['success' => false, 'message' => 'Error al eliminar la categoría.'];
    }
}

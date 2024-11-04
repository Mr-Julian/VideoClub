<?php
require_once '../modelo/Alquiler.php';

class AlquilerController
{
    private $alquilerModel;

    public function __construct()
    {
        $this->alquilerModel = new Alquiler();
    }

    public function listarAlquileres()
    {
        return $this->alquilerModel->listarAlquileres();
    }

    public function agregarAlquiler($fecha, $total, $id_empleado, $id_cliente, $id_pelicula)
    {
        // Validar que todos los campos necesarios estén completos
        if (empty($fecha) || empty($total) || empty($id_empleado) || empty($id_cliente) || empty($id_pelicula)) {
            return ['success' => false, 'message' => 'Todos los campos son obligatorios.'];
        }

        // Agregar el alquiler
        $resultado = $this->alquilerModel->agregarAlquiler($fecha, $total, $id_empleado, $id_cliente, $id_pelicula);

        return $resultado
            ? ['success' => true, 'message' => 'Alquiler agregado exitosamente.']
            : ['success' => false, 'message' => 'Error al agregar el alquiler.'];
    }

    public function obtenerAlquilerPorId($id)
    {
        if (empty($id)) {
            return ['success' => false, 'message' => 'El ID es obligatorio.'];
        }

        $alquiler = $this->alquilerModel->obtenerAlquilerPorId($id);
        return $alquiler ? ['success' => true, 'alquiler' => $alquiler] : ['success' => false, 'message' => 'Alquiler no encontrado.'];
    }

    public function actualizarAlquiler($data)
    {
        if (empty($data['id'])) {
            return ['success' => false, 'message' => 'El ID es obligatorio para actualizar.'];
        }

        // Validar que todos los campos necesarios estén completos
        if (empty($data['fecha']) || empty($data['total']) || empty($data['id_empleado']) || empty($data['id_cliente']) || empty($data['id_pelicula'])) {
            return ['success' => false, 'message' => 'Todos los campos son obligatorios.'];
        }

        // Actualizar el alquiler
        $resultado = $this->alquilerModel->actualizarAlquiler($data);

        return $resultado
            ? ['success' => true, 'message' => 'Alquiler actualizado exitosamente.']
            : ['success' => false, 'message' => 'Error al actualizar el alquiler.'];
    }

    public function eliminarAlquiler($id)
    {
        if (empty($id)) {
            return ['success' => false, 'message' => 'El ID es obligatorio para eliminar.'];
        }

        $resultado = $this->alquilerModel->eliminarAlquiler($id);

        return $resultado
            ? ['success' => true, 'message' => 'Alquiler eliminado exitosamente.']
            : ['success' => false, 'message' => 'Error al eliminar el alquiler.'];
    }
}

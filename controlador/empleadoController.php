<?php
require_once '../modelo/Empleado.php';

class EmpleadoController
{
    private $empleadoModel;

    public function __construct()
    {
        $this->empleadoModel = new Empleado();
    }

    public function listarEmpleados()
    {
        return $this->empleadoModel->listarEmpleados();
    }

    public function agregarEmpleado($nombre, $apellidos, $direccion, $telefono, $email, $nombre_usuario, $password, $cargo)
    {
        if (empty($nombre) || empty($apellidos) || empty($email) || empty($nombre_usuario) || empty($password)) {
            return ['success' => false, 'message' => 'Todos los campos son obligatorios.'];
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return ['success' => false, 'message' => 'El correo electrónico no es válido.'];
        }

        try {
            $resultado = $this->empleadoModel->agregarEmpleado($nombre, $apellidos, $direccion, $telefono, $email, $nombre_usuario, $password, $cargo);
            return $resultado
                ? ['success' => true, 'message' => 'Empleado agregado exitosamente.']
                : ['success' => false, 'message' => 'Error al agregar el empleado.'];
        } catch (Exception $e) {
            error_log("Error al agregar empleado: " . $e->getMessage());
            return ['success' => false, 'message' => 'Error inesperado. Intente nuevamente.'];
        }
    }

    public function obtenerEmpleadoPorId($id)
    {
        if (empty($id)) {
            return ['success' => false, 'message' => 'El ID es obligatorio.'];
        }

        $empleado = $this->empleadoModel->obtenerEmpleadoPorId($id);
        return $empleado ? ['success' => true, 'empleado' => $empleado] : ['success' => false, 'message' => 'Empleado no encontrado.'];
    }

    public function actualizarEmpleado($data)
    {
        if (empty($data['id'])) {
            return ['success' => false, 'message' => 'El ID es obligatorio para actualizar.'];
        }

        // Validar que todos los campos necesarios estén completos
        if (empty($data['nombre']) || empty($data['apellidos']) || empty($data['email']) || empty($data['nombre_usuario'])) {
            return ['success' => false, 'message' => 'Todos los campos son obligatorios.'];
        }

        // Comprobar formato de correo electrónico
        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            return ['success' => false, 'message' => 'El correo electrónico no es válido.'];
        }

        // Verificar si hay una nueva contraseña
        if (!empty($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT); // Hash de la nueva contraseña
        }

        // Actualizar el empleado
        $resultado = $this->empleadoModel->actualizarEmpleado($data);

        return $resultado
            ? ['success' => true, 'message' => 'Empleado actualizado exitosamente.']
            : ['success' => false, 'message' => 'Error al actualizar el empleado.'];
    }

    public function eliminarEmpleado($id)
    {
        if (empty($id)) {
            return ['success' => false, 'message' => 'El ID es obligatorio para eliminar.'];
        }

        $resultado = $this->empleadoModel->eliminarEmpleado($id);

        return $resultado
            ? ['success' => true, 'message' => 'Empleado eliminado exitosamente.']
            : ['success' => false, 'message' => 'Error al eliminar el empleado.'];
    }
}

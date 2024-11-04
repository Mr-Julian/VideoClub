<?php
require_once '../modelo/Cliente.php';

class ClienteController
{
    private $clienteModel;

    public function __construct()
    {
        $this->clienteModel = new Cliente();
    }

    public function listarClientes()
    {
        return $this->clienteModel->listarClientes();
    }

    public function agregarCliente($nombre, $apellidos, $direccion, $telefono, $email)
    {
        if (empty($nombre)) {
            return ['success' => false, 'message' => 'El nombre es obligatorio.'];
        }

        if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return ['success' => false, 'message' => 'El correo electrónico no es válido.'];
        }

        $resultado = $this->clienteModel->agregarCliente($nombre, $apellidos, $direccion, $telefono, $email);

        return $resultado
            ? ['success' => true, 'message' => 'Cliente agregado exitosamente.']
            : ['success' => false, 'message' => 'Error al agregar el cliente.'];
    }

    public function obtenerClientePorId($id)
    {
        if (empty($id)) {
            return ['success' => false, 'message' => 'El ID es obligatorio.'];
        }

        $cliente = $this->clienteModel->obtenerClientePorId($id);
        return $cliente ? ['success' => true, 'cliente' => $cliente] : ['success' => false, 'message' => 'Cliente no encontrado.'];
    }

    public function actualizarCliente($data)
    {
        if (empty($data['id']) || empty($data['nombre'])) {
            return ['success' => false, 'message' => 'El ID y el nombre son obligatorios para actualizar.'];
        }

        if (!empty($data['email']) && !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            return ['success' => false, 'message' => 'El correo electrónico no es válido.'];
        }

        $resultado = $this->clienteModel->actualizarCliente($data);

        return $resultado
            ? ['success' => true, 'message' => 'Cliente actualizado exitosamente.']
            : ['success' => false, 'message' => 'Error al actualizar el cliente.'];
    }

    public function eliminarCliente($id)
    {
        if (empty($id)) {
            return ['success' => false, 'message' => 'El ID es obligatorio para eliminar.'];
        }

        $resultado = $this->clienteModel->eliminarCliente($id);

        return $resultado
            ? ['success' => true, 'message' => 'Cliente eliminado exitosamente.']
            : ['success' => false, 'message' => 'Error al eliminar el cliente.'];
    }
}

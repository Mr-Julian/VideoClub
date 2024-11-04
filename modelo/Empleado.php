<?php
require_once '../controlador/conexion.php';

class Empleado
{
    private $db;

    public function __construct()
    {
        $database = new baseDatos();
        $this->db = $database->connect();
    }

    public function listarEmpleados()
    {
        try {
            $sql = "SELECT * FROM empleado";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // Manejo de errores (puedes lanzar la excepción o registrar el error)
            return [];
        }
    }

    public function agregarEmpleado($nombre, $apellidos, $direccion, $telefono, $email, $nombre_usuario, $password, $cargo)
    {
        try {
            // Hash de la contraseña
            $sql = "INSERT INTO empleado (Nombre, Apellidos, Direccion, Telefono, Email, Nombre_Usuario, Password, Cargo) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([$nombre, $apellidos, $direccion, $telefono, $email, $nombre_usuario, $password, $cargo]);
        } catch (PDOException $e) {
            // Manejo de errores
            return false;
        }
    }

    public function obtenerEmpleadoPorId($id)
    {
        try {
            $sql = "SELECT * FROM empleado WHERE Id_Empleado = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // Manejo de errores
            return null;
        }
    }

    public function actualizarEmpleado($data)
    {
        try {
            // Verificar si se proporciona una nueva contraseña
            if (!empty($data['password'])) {
                $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
                $query = "UPDATE empleado SET Nombre = :nombre, Apellidos = :apellidos, Direccion = :direccion, Telefono = :telefono, Email = :email, Nombre_Usuario = :nombre_usuario, Password = :password, Cargo = :cargo WHERE Id_Empleado = :id";
            } else {
                // Si no hay nueva contraseña, exclúyela de la consulta
                $query = "UPDATE empleado SET Nombre = :nombre, Apellidos = :apellidos, Direccion = :direccion, Telefono = :telefono, Email = :email, Nombre_Usuario = :nombre_usuario, Cargo = :cargo WHERE Id_Empleado = :id";
            }

            $stmt = $this->db->prepare($query);

            // Prepara los datos a ejecutar
            if (!empty($data['password'])) {
                $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
                return $stmt->execute($data);
            } else {
                unset($data['password']); // Elimina la clave de la contraseña si no se proporciona
                return $stmt->execute($data);
            }
        } catch (PDOException $e) {
            // Manejo de errores
            return false;
        }
    }

    public function eliminarEmpleado($id)
    {
        try {
            $sql = "DELETE FROM empleado WHERE Id_Empleado = ?";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([$id]);
        } catch (PDOException $e) {
            // Manejo de errores
            return false;
        }
    }
}

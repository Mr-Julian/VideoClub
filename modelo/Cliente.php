<?php
require_once '../controlador/conexion.php';

class Cliente
{
    private $db;

    public function __construct()
    {
        $database = new baseDatos();
        $this->db = $database->connect();
    }

    public function listarClientes()
    {
        try {
            $sql = "SELECT * FROM cliente";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    public function agregarCliente($nombre, $apellidos, $direccion, $telefono, $email)
    {
        try {
            $sql = "INSERT INTO cliente (Nombre, Apellidos, Direccion, Telefono, Email) VALUES (?, ?, ?, ?, ?)";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([$nombre, $apellidos, $direccion, $telefono, $email]);
        } catch (PDOException $e) {
            return false;
        }
    }

    public function obtenerClientePorId($id)
    {
        try {
            $sql = "SELECT * FROM cliente WHERE Id_Cliente = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return null;
        }
    }

    public function actualizarCliente($data)
    {
        try {
            $query = "UPDATE cliente SET Nombre = :nombre, Apellidos = :apellidos, Direccion = :direccion, Telefono = :telefono, Email = :email WHERE Id_Cliente = :id";
            $stmt = $this->db->prepare($query);
            return $stmt->execute([
                ':nombre' => $data['nombre'],
                ':apellidos' => $data['apellidos'],
                ':direccion' => $data['direccion'],
                ':telefono' => $data['telefono'],
                ':email' => $data['email'],
                ':id' => $data['id']
            ]);
        } catch (PDOException $e) {
            return false;
        }
    }

    public function eliminarCliente($id)
    {
        try {
            $sql = "DELETE FROM cliente WHERE Id_Cliente = ?";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([$id]);
        } catch (PDOException $e) {
            return false;
        }
    }
}

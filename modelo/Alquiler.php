<?php
require_once '../controlador/conexion.php';

class Alquiler
{
    private $db;

    public function __construct()
    {
        $database = new baseDatos();
        $this->db = $database->connect();
    }

    public function listarAlquileres()
    {
        try {
            $sql = "SELECT a.*, e.Nombre AS Nombre_Empleado, c.Nombre AS Nombre_Cliente, p.Titulo AS Titulo_Pelicula
                    FROM alquiler a
                    JOIN empleado e ON a.Id_Empleado = e.Id_Empleado
                    JOIN cliente c ON a.Id_Cliente = c.Id_Cliente
                    JOIN peliculas p ON a.Id_Pelicula = p.Id_Pelicula";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // Manejo de errores (puedes lanzar la excepción o registrar el error)
            return [];
        }
    }

    public function agregarAlquiler($fecha, $total, $id_empleado, $id_cliente, $id_pelicula)
    {
        try {
            $sql = "INSERT INTO alquiler (Fecha, Total, Id_Empleado, Id_Cliente, Id_Pelicula) 
                    VALUES (?, ?, ?, ?, ?)";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([$fecha, $total, $id_empleado, $id_cliente, $id_pelicula]);
        } catch (PDOException $e) {
            // Manejo de errores
            return false;
        }
    }

    public function obtenerAlquilerPorId($id)
    {
        try {
            $sql = "SELECT * FROM alquiler WHERE Id_Venta = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // Manejo de errores
            return null;
        }
    }

    public function actualizarAlquiler($data)
    {
        try {
            $query = "UPDATE alquiler SET Fecha = :fecha, Total = :total, Id_Empleado = :id_empleado, Id_Cliente = :id_cliente, Id_Pelicula = :id_pelicula WHERE Id_Venta = :id";
            $stmt = $this->db->prepare($query);
            return $stmt->execute($data);
        } catch (PDOException $e) {
            // Manejo de errores
            return false;
        }
    }

    public function eliminarAlquiler($id)
    {
        try {
            $sql = "DELETE FROM alquiler WHERE Id_Venta = ?";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([$id]);
        } catch (PDOException $e) {
            // Manejo de errores
            return false;
        }
    }
}

<?php
require_once '../controlador/conexion.php';

class Categoria
{
    private $db;

    public function __construct()
    {
        $database = new baseDatos();
        $this->db = $database->connect();
    }

    // Listar todas las categorías
    public function listarCategorias()
    {
        try {
            $sql = "SELECT * FROM categoria";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    // Agregar una nueva categoría
    public function agregarCategoria($nombreCategoria)
    {
        try {
            $sql = "INSERT INTO categoria (Nombre_Categoria) VALUES (?)";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([$nombreCategoria]);
        } catch (PDOException $e) {
            return false;
        }
    }

    // Obtener una categoría por su ID
    public function obtenerCategoriaPorId($id)
    {
        try {
            $sql = "SELECT * FROM categoria WHERE Id_Categoria = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return null;
        }
    }

    // Actualizar una categoría
    public function actualizarCategoria($data)
    {
        try {
            $query = "UPDATE categoria SET Nombre_Categoria = :nombre_categoria WHERE Id_Categoria = :id";
            $stmt = $this->db->prepare($query);
            return $stmt->execute([
                ':nombre_categoria' => $data['nombre_categoria'],
                ':id' => $data['id']
            ]);
        } catch (PDOException $e) {
            return false;
        }
    }

    // Eliminar una categoría
    public function eliminarCategoria($id)
    {
        try {
            $sql = "DELETE FROM categoria WHERE Id_Categoria = ?";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([$id]);
        } catch (PDOException $e) {
            return false;
        }
    }
}

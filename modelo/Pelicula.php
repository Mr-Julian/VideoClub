<?php
require_once '../controlador/conexion.php';

class Pelicula
{
    private $db;

    public function __construct()
    {
        $database = new baseDatos();
        $this->db = $database->connect();
    }

    public function listarPeliculas()
    {
        try {
            $sql = "SELECT * FROM peliculas";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // Manejo de errores (puedes lanzar la excepción o registrar el error)
            error_log($e->getMessage());
            return [];
        }
    }

    public function agregarPelicula($titulo, $descripcion, $clasificacion_de_edad, $cantidad_en_inventario, $id_categoria)
    {
        try {
            $sql = "INSERT INTO peliculas (Titulo, Descripcion, Clasificacion_de_edad, Cantidad_en_inventario, Id_Categoria) 
                    VALUES (?, ?, ?, ?, ?)";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([$titulo, $descripcion, $clasificacion_de_edad, $cantidad_en_inventario, $id_categoria]);
        } catch (PDOException $e) {
            // Manejo de errores
            error_log($e->getMessage());
            return false;
        }
    }

    public function obtenerPeliculaPorId($id)
    {
        try {
            $sql = "SELECT * FROM peliculas WHERE Id_Pelicula = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // Manejo de errores
            error_log($e->getMessage());
            return null;
        }
    }

    public function actualizarPelicula($data)
    {
        try {
            $query = "UPDATE peliculas SET Titulo = :titulo, Descripcion = :descripcion, Clasificacion_de_edad = :clasificacion_de_edad, Cantidad_en_inventario = :cantidad_en_inventario, Id_Categoria = :id_categoria WHERE Id_Pelicula = :id";
            $stmt = $this->db->prepare($query);
            return $stmt->execute($data);
        } catch (PDOException $e) {
            // Manejo de errores
            error_log($e->getMessage());
            return false;
        }
    }

    public function eliminarPelicula($id)
    {
        try {
            $sql = "DELETE FROM peliculas WHERE Id_Pelicula = ?";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([$id]);
        } catch (PDOException $e) {
            // Manejo de errores
            error_log($e->getMessage());
            return false;
        }
    }
}

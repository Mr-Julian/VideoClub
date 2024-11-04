<?php

include_once 'conexion.php';

class User extends baseDatos
{
    private $nombre;
    private $username;

    // Verifica si el usuario existe y la contraseña es correcta
    public function userExists($user, $pass)
    {
        try {
            $query = $this->connect()->prepare('SELECT Password FROM empleado WHERE Nombre_Usuario = :user');
            $query->execute(['user' => $user]);
            $hashedPassword = $query->fetchColumn();

            // Debug: muestra el usuario y la contraseña hasheada
            error_log("Usuario: $user, Contraseña hasheada: $hashedPassword");

            return $hashedPassword && password_verify($pass, $hashedPassword);
        } catch (Exception $e) {
            // Manejo de errores
            error_log("Error: " . $e->getMessage());
            throw new Exception("Error al verificar el usuario: " . $e->getMessage());
        }
    }

    public function setUser($user)
    {
        try {
            $query = $this->connect()->prepare('SELECT * FROM empleado WHERE Nombre_Usuario = :user');
            $query->execute(['user' => $user]);

            if ($query->rowCount() > 0) {
                $currentUser = $query->fetch();
                $this->nombre = $currentUser['Nombre'];
                $this->username = $currentUser['Nombre_Usuario'];
            } else {
                throw new Exception("Usuario no encontrado.");
            }
        } catch (Exception $e) {
            // Loguea o maneja el error de la base de datos
            throw new Exception("Error al establecer el usuario: " . $e->getMessage());
        }
    }

    // Obtiene el nombre del usuario
    public function getNombre()
    {
        return $this->nombre;
    }

    // Obtiene el nombre de usuario
    public function getUsername()
    {
        return $this->username;
    }
}

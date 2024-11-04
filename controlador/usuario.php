<?php

include_once 'conexion.php';

class User extends baseDatos
{
    private $nombre;
    private $username;
    private $cargo;

    public function userExists($user, $pass)
    {
        // Prepara la consulta para obtener el hash de la contraseña almacenada en la base de datos
        $query = $this->connect()->prepare('SELECT Password FROM empleado WHERE Nombre_Usuario = :user');
        $query->execute(['user' => $user]);

        // Obtiene el hash de la contraseña almacenada
        $hashedPassword = $query->fetchColumn();

        // Verifica si el hash de la base de datos coincide con la contraseña ingresada
        if ($hashedPassword && password_verify($pass, $hashedPassword)) {
            return true;
        } else {
            return false;
        }
    }

    public function setUser($user)
    {
        // Prepara la consulta para obtener los datos del usuario
        $query = $this->connect()->prepare('SELECT * FROM empleado WHERE Nombre_Usuario = :user');
        $query->execute(['user' => $user]);

        foreach ($query as $currentUser) {
            $this->nombre = $currentUser['Nombre'];
            $this->username = $currentUser['Nombre_Usuario'];
            $this->cargo = $currentUser['Cargo']; // Asegúrate de que "Cargo" es el nombre de la columna en la base de datos
        }
    }

    public function getNombre()
    {
        return $this->nombre;
    }

    public function getCargo()
    {
        return $this->cargo;
    }
}

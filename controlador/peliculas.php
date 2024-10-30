<?php

include_once 'conexion.php';

class User extends baseDatos
{

    private $nombre;
    private $username;

    public function userExists($user, $pass)
    {
        //$md5pass = md5($pass);

        $query = $this->connect()->prepare('SELECT * FROM empleado WHERE Nombre_Usuario = :user AND Password = :pass');
        $query->execute(['user' => $user, 'pass' => $pass]);

        if ($query->rowCount()) {
            return true;
        } else {
            return false;
        }
    }

    public function setUser($user)
    {
        $query = $this->connect()->prepare('SELECT * FROM empleado WHERE Nombre_Usuario = :user');
        $query->execute(['user' => $user]);

        foreach ($query as $currentUser) {
            $this->nombre = $currentUser['Nombre'];
            $this->username = $currentUser['Nombre_Usuario'];
        }
    }

    public function getNombre()
    {
        return $this->nombre;
    }
}

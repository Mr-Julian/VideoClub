<?php

class UserSession
{
    public function __construct()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    // Método para establecer el usuario actual
    public function setCurrentUser($user)
    {
        $_SESSION['user'] = $user;
    }

    // Método para obtener el usuario actual
    public function getCurrentUser()
    {
        return isset($_SESSION['user']) ? $_SESSION['user'] : null;
    }

    // Método para establecer el rol del usuario actual
    public function setCurrentCargo($cargo)
    {
        $_SESSION['Cargo'] = $cargo;
    }

    // Método para obtener el rol del usuario actual
    public function getCurrentCargo()
    {
        return isset($_SESSION['Cargo']) ? $_SESSION['Cargo'] : null;
    }

    // Método para cerrar la sesión
    public function closeSession()
    {
        session_unset();
        session_destroy();
    }

    // Método para verificar el rol del usuario
    public function isUserRole($role)
    {
        return isset($_SESSION['Cargo']) && $_SESSION['Cargo'] === $role;
    }
}

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>

<body>
    <?php

    include_once 'controlador/usuario.php';
    include_once 'modelo/sesion.php';

    $userSession = new UserSession();
    $user = new User();

    if (isset($_SESSION['user'])) {
        $user->setUser($userSession->getCurrentUser());
        include_once 'vista/home.php';
    } else if (isset($_POST['username']) && isset($_POST['password'])) {
        $userForm = $_POST['username'];
        $passForm = $_POST['password'];

        // Verifica si el usuario y la contraseña son correctos
        if ($user->userExists($userForm, $passForm)) {
            $userSession->setCurrentUser($userForm);
            $user->setUser($userForm);
            $userSession->setCurrentCargo($user->getCargo());
            include_once 'vista/home.php';
        } else {
            $errorLogin = "Nombre de usuario y/o contraseña incorrecto";
            include_once 'vista/login.php';
        }
    } else {
        include_once 'vista/login.php';
    }
    ?>
</body>

</html>
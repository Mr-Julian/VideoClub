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
    include_once 'controlador/sesion.php';

    $userSession = new UserSession();
    $user = new User();

    if (isset($_SESSION['user'])) {
        //echo "Hay sesión";
        $user->setUser($userSession->getCurrentUser());
        include_once 'vista/home.php';
    } else if (isset($_POST['username']) && isset($_POST['password'])) {
        //echo "Validación de login";

        $userForm = $_POST['username'];
        $passForm = $_POST['password'];

        if ($user->userExists($userForm, $passForm)) {
            //echo "usuario validado";
            $userSession->setCurrentUser($userForm);
            $user->setUser($userForm);

            include_once 'vista/home.php';
        } else {
            //echo "nombre de usuario y/o password incorrecto";
            $errorLogin = "Nombre de usuario y/o password es incorrecto";
            include_once 'vista/login.php';
        }
    } else {
        //echo "Login";
        include_once 'vista/login.php';
    }
    ?>
</body>

</html>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Sesiones</title>

    <link rel="stylesheet" href="css/login.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>

<body>

    <form action="" method="POST">
        <?php

        if (isset($errorLogin)) {
            echo $errorLogin;
        }
        ?>
        <div class="login-container">
            <img src="images/login-logo-icon-2-333005368.png" alt="Logo">
            <h3>Inicio de Sesión</h3>
            <div class="mb-3">
                <input type="text" class="form-control" name="username" placeholder="Nombre Usuario">
            </div>
            <div class="mb-3">
                <input type="password" class="form-control" name="password" placeholder="Contraseña">
            </div>
            <button type="submit" class="btn btn-primary mt-3">Iniciar Sesión</button>
            <footer class="mt-4 text-muted">&copy; 2017–2024</footer>
        </div>
    </form>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>
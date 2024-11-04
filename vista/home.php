<!DOCTYPE html>
<html lang="en">

<?php
include_once __DIR__ . '/../controlador/usuario.php';
include_once __DIR__ . '/../modelo/sesion.php';

$userSession = new UserSession();
$user = new User();
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Verifica si el usuario ha iniciado sesión
if (isset($_SESSION['user'])) {
    // Inicializa el objeto User y establece el usuario actual
    $user->setUser($userSession->getCurrentUser());
} else {
    // Redirige al login si no ha iniciado sesión
    header("Location: index.php");
    exit();
}
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="main.css">
    <style>
        body {
            background-color: #f8f9fa;
        }

        #menu {
            margin: 20px auto;
            max-width: 600px;
        }

        .card {
            border: none;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .btn {
            width: 100%;
            margin: 5px 0;
        }

        .user-info {
            margin: 10px 0;
            text-align: center;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div id="menu" class="container">
        <div class="card">
            <div class="card-body">
                <section>
                    <h1>Bienvenido <?php echo isset($user) ? $user->getNombre() : 'Usuario'; ?> </h1>
                    <h3 class="user-info"><?php echo isset($user) ? $user->getCargo() : ''; ?></h3> <!-- Mostrar cargo -->
                </section>
                <h2 class="card-title text-center">Menú de Navegación</h2>
            </div>
            <ul class="list-unstyled">
                <li>
                    <a href="vista/empleado.php" target="_blank" rel="noopener noreferrer" class="btn btn-primary">Empleados</a>
                </li>
                <li>
                    <a href="vista/cliente.php" target="_blank" rel="noopener noreferrer" class="btn btn-primary">Clientes</a>
                </li>
                <li>
                    <a href="vista/categoria.php" target="_blank" rel="noopener noreferrer" class="btn btn-primary">Categorías</a>
                </li>
                <li>
                    <a href="vista/pelicula.php" target="_blank" rel="noopener noreferrer" class="btn btn-primary">Películas</a>
                </li>
                <li>
                    <a href="vista/alquiler.php" target="_blank" rel="noopener noreferrer" class="btn btn-primary">Alquileres</a>
                </li>
                <li>
                    <a href="controlador/cerrarSesion.php" rel="noopener noreferrer" class="btn btn-danger">Cerrar sesión</a>
                </li>
            </ul>
        </div>
    </div>
    </div>

    <section class="text-center">
        <h1>¡Disfruta tu día!</h1>
    </section>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
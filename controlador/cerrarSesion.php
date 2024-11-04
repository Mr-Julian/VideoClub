<?php
include_once '../modelo/sesion.php';

$userSession = new UserSession();
$userSession->closeSession();

// Redirigir a la página de inicio
header("Location: ../index.php");
exit(); // Asegúrate de terminar el script después de redirigir

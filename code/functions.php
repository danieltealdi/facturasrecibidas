<?php
require '../code/conf.php';

function conectarDb(): mysqli
{
    global $localhost, $user, $pw, $baseDeDatos;
    $db = new mysqli($localhost, $user, $pw, $baseDeDatos);
    $charset = "utf8";
    mysqli_set_charset($db, $charset);
    if (!$db) {
        echo "Error: No se pudo conectar a MySQL.";
        exit;
    }
    return $db;
}
function cerrarSesion()
{
    setcookie(session_name(), session_id(), 1); // to expire the session
    $_SESSION = [];
    session_destroy();
    header('location: login.php');
}

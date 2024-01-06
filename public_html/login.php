<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>login</title>
    <link rel="stylesheet" href="estilos.css">
    <link rel="icon" type="image/vnd.microsoft.icon" href="./favicon.ico">
</head>

<body>
    <h1>LOGIN</h1>
    <p id=texto></p>
    <form method='POST'>
        <label for='user' id='luser'>Usuario</label>
        <input id='user' type='text' name='user' value="" placeholder='entre el usuario' size='30'><br>
        <label for='passwd'>Contraseña</label>
        <input id='passwd' type='password' name='passwd' value="" placeholder='entre la contraseña' size='10'><br>
        <input type="submit" value="Login" class="boton boton-verde ">
    </form>

    <?PHP
    //error_reporting(E_ALL);
    //ini_set('display_errors', 1);
    require '../code/functions.php';
    global $db;
    $db = conectarDb();
    //setcookie(session_name(), session_id(), 1); // to expire the session
    //$_SESSION = [];
    //session_destroy();
    //session_name('login');
    // Agrega mensajes de depuración
    //cho "Session ID: " . session_id();
    //echo "Session Status: " . session_status();
    session_start();
    // Agrega mensajes de depuración
    //echo "Session ID: " . session_id();
    //echo "Session Status: " . session_status();
    //var_dump($_SESSION);
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $user = $_POST['user'];
        if ($user) {
            $passwd = "";
            $query = 'SELECT * from usuarios WHERE user=' . '"' . $user . '"';
            $result = $db->query($query);
            $row = $result->fetch_array();
            $passwd = $row['passwd'];
            if ($_POST['passwd'] == $passwd) {
                // Autenticado.
                // Para autenticar usuarios estaremos utilizando la superglobal SESSION, esta va a mantener eso una sesión activa en caso de que sea valida.
                $_SESSION['login'] = true;
                $db->close();
                ob_clean(); // Limpia el buffer de salida
                header('location: ./index.php');
            } else {
                // No autenticado
                $_SESSION['login'] = false;
            }
        }
        $_POST['user'] = "";
        $_POST['passwd'] = "";
    }else{
        $_POST['user'] = "";
        $_POST['passwd'] = "";
    }

    ?>

</body>
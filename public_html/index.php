<?php
session_start();
if (!$_SESSION['login']) {
    header('location: login.php');
}
include '../code/functions.php';
//$_SESSION['login'] = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $db = conectarDb();
    if (!$_POST['existe']) {
        //var_dump($_POST);        
        $query = "INSERT INTO proveedores (";
        $query .= "nif, ";
        $query .= "proveedor, ";
        $query .= "direccion, ";
        $query .= "poblacion, ";
        $query .= "codigo )";
        $query .= " VALUES ('";
        $query .= $_POST['nif'] . "', '";
        $query .= $_POST['proveedor'] . "', '";
        $query .= $_POST['direccion'] . "', '";
        $query .= $_POST['poblacion'] . "', '";
        $query .= $_POST['codigo'] . "')";
        //var_dump($query);
        //exit;        
        $db->query($query);
        //$db->close();
    }
    $fecha = date("Y-m-d", strtotime($_POST['fecha']));
    $query = "INSERT INTO facturas (";
    $query .= "nif, ";
    $query .= "proveedor, ";
    $query .= "direccion, ";
    $query .= "poblacion, ";
    $query .= "codigo, ";
    $query .= "factura, ";
    $query .= "fecha, ";
    $query .= "importe, ";
    $query .= "iva, ";
    $query .= "total )";
    $query .= " VALUES ('";
    $query .= $_POST['nif'] . "', '";
    $query .= $_POST['proveedor'] . "', '";
    $query .= $_POST['direccion'] . "', '";
    $query .= $_POST['poblacion'] . "', '";
    $query .= $_POST['codigo'] . "', '";
    $query .= $_POST['factura'] . "', '";
    $query .= $fecha . "', '";
    $query .= $_POST['base'] . "', '";
    $query .= $_POST['iva'] . "', '";
    $query .= $_POST['importe'] . "')";
    //var_dump($db);
    $db->query($query);
    $db->close();
}
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if ($_GET['cerrar']) {
        //setcookie(session_name(), session_id(), 1); // to expire the session
        //$_SESSION = [];
        //session_destroy();
        header('location: login.php');
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Facturas Recibidas</title>
    <link rel="stylesheet" href="estilos.css">
    <link rel="icon" type="image/vnd.microsoft.icon" href="favicon.ico">
    <script src="script.js"></script>
    
</head>

<body onload="focoEnNif()" onunload="cerrar()">
    <h1>Facturas recibidas</h1>
    <p id=texto class="texto"></p>
    <form method='POST' id='form' action="#">
        <fieldset id="fnif">
            <label for='nif' id='lnif'>NIF</label>
            <input id='nif' name='nif' type='text' placeholder='entre el nif' size='10' onchange='validarNif()' onblur='validarNif()'>
            Existe
            <input id='existe' name='existe' type='checkbox' disabled><br>
        </fieldset>
        <fieldset id="fproveeor">
            <label for='proveedor'>Proveedor</label>
            <input id='proveedor' name='proveedor' type='text' placeholder='entre el proveedor' size='50' onchange='focoEnDireccion()'><br>
            <label for='direccion'>Dirección</label>
            <input id='direccion' name='direccion' type='text' placeholder='entre la direccion' size='50' onchange='focoEnPoblacion()'><br>
            <label for='poblacion'>Población</label>
            <input id='poblacion' name='poblacion' type='text' placeholder='entre la población' size='50' onchange='focoEnCodigo()'><br>
            <label for='codigo'>Código postal</label>
            <input id='codigo' name='codigo' type='number' placeholder='entre el código postal' size='10' onchange='validarCodigo()'><br>
            <label for='factura'>Nº de Factura</label>
        </fieldset>
        <fieldset id="ffactura">
            <input id='factura' name='factura' type='text' placeholder='entre el número de factura' size='10' onchange='focoEnFecha()'><br>
            <label for='fecha'>Fecha</label>
            <input id='fecha' name='fecha' type='date' placeholder='dd/mm/yyyy' size='10' onchange='focoEnBase()'><br>
            <label for='base'>Base Imponible</label>
            <input id='base' name='base' type='number' pattern="^\d*(\.\d{0,2})?$" placeholder='entre la base imponible' size='10' onchange='focoEnIva()'><br>
            <label for='iva'>IVA</label>
            <input id='iva' name='iva' type='number' pattern="^\d*(\.\d{0,2})?$" placeholder='entre el iva' size='10' onchange='focoEnImporte()'><br>
            <label for='importe'>Importe</label>
            <input id='importe' name='importe' type='number' pattern="^\d*(\.\d{0,2})?$" placeholder='entre el importe' size='10' onchange='validarImporte()'><br>
            <input type="button" id='boton' onClick='enviarForm()' value="Entrar factura" disabled="disabled" class="boton boton-verde invisible">
        </fieldset>
    </form>
    <form method='GET' id='fcerrar' action="#">
        <input type="submit" id='cerrar' name="cerrar" value="Cerrar Sesión" class="boton boton-verde">
    </form>
    <table id="proveedores" class="invisible">

        <thead>
            <tr>
                <th>NIF</th>
                <th>PROVEEDOR</th>
                <th>DIRECCIÓN</th>
                <th>POBLACIÓN</TH>
                <th>C.POSTAL</th>

            </tr>
        </thead>
        <?PHP
        $db = conectarDb();
        $proveedores = [];
        $registro = [];
        foreach ($db->query('SELECT * from proveedores') as $row) { ?>
            <tr>
                <td><?php echo $row['nif'] ?></td>
                <td><?php echo $row['proveedor'] ?></td>
                <td><?php echo $row['direccion'] ?></td>
                <td><?php echo $row['poblacion'] ?></td>
                <td><?php echo $row['codigo'] ?></td>
            </tr>
        <?php
        }
        ?>
    </table>
</body>

function focoEnNif() {
    document.getElementById('nif').disabled = false;
    document.getElementById('proveedor').disabled = true;
    document.getElementById('direccion').disabled = true;
    document.getElementById('poblacion').disabled = true;
    document.getElementById('codigo').disabled = true;
    document.getElementById('factura').disabled = true;
    document.getElementById('fecha').disabled = true;
    document.getElementById('base').disabled = true;
    document.getElementById('iva').disabled = true;
    document.getElementById('importe').disabled = true;
    document.getElementById('nif').focus();
}
function focoEnProveedor() {
    document.getElementById('nif').disabled = true;
    document.getElementById('proveedor').disabled = false;
    document.getElementById('direccion').disabled = false;
    document.getElementById('poblacion').disabled = false;
    document.getElementById('codigo').disabled = false;
    document.getElementById('factura').disabled = true;
    document.getElementById('fecha').disabled = true;
    document.getElementById('base').disabled = true;
    document.getElementById('iva').disabled = true;
    document.getElementById('importe').disabled = true;
    document.getElementById('proveedor').focus();
}
function focoEnFactura() {
    document.getElementById('nif').disabled = true;
    document.getElementById('proveedor').disabled = true;
    document.getElementById('direccion').disabled = true;
    document.getElementById('poblacion').disabled = true;
    document.getElementById('codigo').disabled = true;
    document.getElementById('factura').disabled = false;
    document.getElementById('fecha').disabled = false;
    document.getElementById('base').disabled = false;
    document.getElementById('iva').disabled = false;
    document.getElementById('importe').disabled = false;
    document.getElementById('factura').focus();
}
function focoEnBoton() {
    document.getElementById('nif').disabled = false;
    document.getElementById('proveedor').disabled = false;
    document.getElementById('direccion').disabled = false;
    document.getElementById('poblacion').disabled = false;
    document.getElementById('codigo').disabled = false;
    document.getElementById('factura').disabled = false;
    document.getElementById('fecha').disabled = false;
    document.getElementById('base').disabled = false;
    document.getElementById('iva').disabled = false;
    document.getElementById('importe').disabled = false;
    document.getElementById('existe').disabled = false;
    document.getElementById('boton').focus();
}
function focoEnFecha() {
    document.getElementById('fecha').focus();
}
function focoEnBase() {
    document.getElementById('base').focus();
}
function focoEnIva() {
    document.getElementById('iva').focus();
}
function focoEnImporte() {
    document.getElementById('importe').focus();
}
function focoEnDireccion() {
    document.getElementById('direccion').focus();
}
function focoEnPoblacion() {
    document.getElementById('poblacion').focus();
}
function focoEnCodigo() {
    document.getElementById('codigo').focus();
}
//document.getElementById('fproveedor').disabled=true;
//document.getElementById('ffactura').disabled=true;
function enviarForm() {
    focoEnBoton();
    //alert('enviarForm');
    $formulario = document.getElementById('form');
    //alert($formulario);
    $formulario.submit();
    //alert('enviado');
    focoEnNif();
}
function validarImporte() {
    $texto = document.getElementById("texto");
    $factura = document.getElementById('factura').value;
    $fecha = document.getElementById('fecha').value;
    $base = document.getElementById('base').value;
    $iva = document.getElementById('iva').value;
    $importe = document.getElementById('importe').value;
    $base = Number(Number($base).toFixed(2))
    $iva = Number(Number($iva).toFixed(2))
    $importe = Number(Number($importe).toFixed(2))
    $suma = Number(($base + $iva).toFixed(2))
    //alert($importe);
    //alert($iva);
    //alert($base);
    //alert(Number(($base+$iva).toFixed(2)));

    if (!$factura || !$fecha || !$base || !$iva || !$importe) {
        alert('Debe rellenar todos los campos');
        document.getElementById('importe').value = '';
    } else if ($importe != $suma) {
        alert('El importe no es correcto');
        document.getElementById('importe').value = '';
    } else {
        $texto.innerHTML = '';
        document.getElementById('boton').disabled = false;
        //document.getElementById('factura').focus();
        document.getElementById('boton').classList.remove('invisible');
        focoEnBoton();
    }
}

function validarCodigo() {
    $texto = document.getElementById("texto");
    $nif = document.getElementById('nif').value;
    $proveedor = document.getElementById('proveedor').value;
    $direccion = document.getElementById('direccion').value;
    $poblacion = document.getElementById('poblacion').value;
    $codigo = document.getElementById('codigo').value;

    if (!$nif || !$proveedor || !$direccion || !$poblacion || !$codigo) {
        alert('Debe rellenar todos los campos');
        document.getElementById('codigo').value = '';
    } else {
        $texto.innerHTML = '';
        //document.getElementById('boton').disabled=false;
        //document.getElementById('factura').focus();
        //document.getElementById('boton').classList.remove('invisible');
        focoEnFactura();
    }
}

function validarNif() {
    document.getElementById('nif').focus();
    //document.getElementById('proveedor').focus();
    $nif = document.getElementById('nif').value;
    $tabla = document.getElementById('proveedores');
    $existe = false;
    $filas = $tabla.rows.length;
    $texto = document.getElementById("texto");
    $texto.innerHTML = ' ';
    for (let i = 1; i < $filas; i++) {
        $prueba = $tabla.rows[i].cells[0].innerHTML;
        if ($nif == $prueba) {
            $existe = true;
            document.getElementById('existe').checked = true;
            document.getElementById('proveedor').value = $tabla.rows[i].cells[1].innerHTML;
            document.getElementById('direccion').value = $tabla.rows[i].cells[2].innerHTML;
            document.getElementById('poblacion').value = $tabla.rows[i].cells[3].innerHTML;
            document.getElementById('codigo').value = $tabla.rows[i].cells[4].innerHTML;
            i = $filas;
        } else {
            document.getElementById('proveedor').value = '';
            document.getElementById('direccion').value = '';
            document.getElementById('poblacion').value = '';
            document.getElementById('codigo').value = '';
        }
    }
    $longitudMal = $nif.length != 9;
    $letraInicio = !isNaN($nif.charAt(0));
    $letraFin = !isNaN($nif.charAt(8));
    if ($longitudMal || ($letraInicio && $letraFin)) {
        $texto.innerHTML = 'Nif incorrecto';
        focoEnNif();
    } else if (!$existe) {
        $texto.innerHTML = 'El proveedor no existe. Rellene los datos';
        focoEnProveedor();
    } else {
        $texto.innerHTML = ' ';
        focoEnFactura();
    }
}
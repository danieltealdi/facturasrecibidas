<?php
function conectarDb(): mysqli
{
    $db = new mysqli('localhost', 'root', '1Coma4142', 'test');

    if (!$db) {
        echo "Error: No se pudo conectar a MySQL.";
        exit;
    }

    return $db;
}

class ActiveRecord{
    protected static $db;
    protected static $columnasDB=[];
    protected static $errores;
    protected static $tabla='';
    public static function setDB($database){
        self::$db=$database;
    }
    public function guardar(){
        if(!is_null($this->id)){
            $this->actualizar();
        }
        else{
            $this->crear();
        }
    }
    public function actualizar(){
       
        $atributos=$this->sanitizarAtributos();
        $valores=[];
        foreach($atributos as $key=>$value){
            $valores[]="$key='$value'";
        }

        $query="UPDATE " . static::$tabla . " SET ";
        $query.=join(', ',$valores);
        $query.="  WHERE id = '".self::$db->escape_string($this->id)."' ";
        $query.="LIMIT 1";
        //debugear($query);
        $resultado=self::$db->query($query);
        //debugear($resultado);
        if ($resultado) {
            header('location: /admin/index.php?mensaje=2');
        }
        //return $resultado;        
    }

    public function crear(){
       
        $atributos=$this->sanitizarAtributos();

        $query = "INSERT INTO " . static::$tabla . " (";
        $query .=join(", ", array_keys($atributos));
        $query .=" ) VALUES ( ' ";
        $query .=join("', '", array_values($atributos));
        $query .=" ' ) ";
        $resultado=self::$db->query($query);
        if ($resultado) {
            header('location: /admin/index.php?mensaje=1');
        }
    }

    public function eliminar(){
        $query = "DELETE FROM " . static::$tabla . " WHERE id = "; 
        $query .= self::$db->escape_string($this->id);
        $query .= " LIMIT 1";
        //debugear($query);
        $resultado=self::$db->query($query);        
        if ($resultado) {
            if(static::$tabla==='propiedades'){
                $this->borrarImagen();
            }
            header('location: /admin/index.php?mensaje=3');
        }else{
            header('location: /admin/index.php?mensaje=4');
        }
    }

    public function atributos(){
        $atributos=[];
        foreach(static::$columnasDB as $columna){
            if($columna==='id')continue;
            $atributos[$columna]=$this->$columna;
        }
        return $atributos;
    }

    public function sanitizarAtributos(){
        $atributos=$this->atributos();
        $sanitizado = [];
        foreach($atributos as $key => $value){
            $sanitizado[$key] = self::$db->escape_string($value);
        }        
        return $sanitizado;
    }
     //Validación
    public static function getErrores(){
        return static::$errores;
    }
    public function validar(){
        static::$errores=[];        
        return static::$errores;
    } 

    public static function all(){
        //debugear(static::$tabla);
        $query = "SELECT * FROM " . static::$tabla;
        //debugear($query);
        $resultado=self::consultarSQL($query);
        return $resultado;
    }

    public static function get($cantidad){
        //debugear(static::$tabla);
        $query = "SELECT * FROM " . static::$tabla . " LIMIT " . $cantidad;
        //debugear($query);
        $resultado=self::consultarSQL($query);
        return $resultado;
    }

    public static function find($id){
        $consulta = "SELECT * FROM " . static::$tabla . " WHERE id = ${id}";
        $resultado=self::consultarSQL($consulta);
        return array_shift($resultado);
    }

    public static function consultarSQL($query){
        //Consultar DB
        $resultado=self::$db->query($query);
        //Iterar los registros
        $array=[];
        while($registro=$resultado->fetch_assoc()){
            $array[]=static::crearObjeto($registro);
        }
        //Liberar la memoria
        $resultado->free();
        //retornar el resultado
        return $array;
    }

    protected static function crearObjeto($registro){
        $objeto=new static;

        foreach($registro as $key => $value){
            if(property_exists($objeto, $key)){
                $objeto->$key=$value;
            }
        }
        return $objeto;
    }

    public function sincronizar($args=[]){
        //debugear($args);
        foreach($args as $key=>$value){
            //echo $value;
            
            if(property_exists($this, $key) && !is_null($value)){
                
                $this->$key=$value;
                //echo $this->$key;
            }
        }
    }
    
} 
class Proveedores extends ActiveRecord{
    protected static $tabla='proveedores';
    protected static $columnasDB=['nif', 'proveedor', 'direccion', 'codigo'];

    public $nif;
    public $proveedor;
    public $direccion;
    public $codigo;

    public function __construct($args=[])
    {
        $this->nif=$args['nif'] ?? null; 
        $this->proveedor=$args['proveedor'] ?? ''; 
        $this->direccion=$args['direccion'] ?? ''; 
        $this->codigo=$args['codigo'] ?? ''; 
    }
    public function validar(){
        if (!$this->nif) {
            self::$errores[] = 'El NIF es obligatorio';
            //echo 'El Nombre es obligatorio';
        }
        if (!$this->proveedor) {
            self::$errores[] = 'El Proveedor es obligatorio';
            //echo 'El Nombre es obligatorio';
        }
        if (!$this->direccion) {
            self::$errores[] = 'La direción es obligatorios';
        }
        if (!$this->codigo) {
            self::$errores[] = 'El Codigo Postal es obligatorio';
        }
        
        return self::$errores;
    }
}
class Facturas extends ActiveRecord{
    protected static $tabla='facturas';
    protected static $columnasDB=['nif', 'proveedor', 'direccion', 'codigo', 'factura', 'fecha', 'base', 'iva', 'importe'];

    public $nif;
    public $proveedor;
    public $direccion;
    public $codigo;
    public $factura;
    public $fecha;
    public $base;
    public $iva;
    public $importe;

    public function __construct($args=[])
    {
        $this->nif=$args['nif'] ?? null; 
        $this->proveedor=$args['proveedor'] ?? ''; 
        $this->direccion=$args['direccion'] ?? ''; 
        $this->codigo=$args['codigo'] ?? ''; 
        $this->factura=$args['factura'] ?? ''; 
        $this->fecha=$args['fecha'] ?? ''; 
        $this->base=$args['base'] ?? 0; 
        $this->iva=$args['iva'] ?? 0; 
        $this->importe=$args['importe'] ?? 0; 
    }

    public function validar(){
        if (!$this->nif) {
            self::$errores[] = 'El NIF es obligatorio';
            //echo 'El Nombre es obligatorio';
        }
        if (!$this->proveedor) {
            self::$errores[] = 'El Proveedor es obligatorio';
            //echo 'El Nombre es obligatorio';
        }
        if (!$this->direccion) {
            self::$errores[] = 'La direción es obligatorios';
        }
        if (!$this->codigo) {
            self::$errores[] = 'El Codigo Postal es obligatorio';
        }
        if (!$this->factura) {
            self::$errores[] = 'El número de factura es obligatoria';
        }
        if (!$this->fecha) {
            self::$errores[] = 'La fecha es obligatoria';
        }
        if (!$this->base) {
            self::$errores[] = 'La base imponible es obligatorio';
        }
        if (!$this->iva) {
            self::$errores[] = 'El iva es obligatorio';
        }
        if (!$this->importe) {
            self::$errores[] = 'El Importe es obligatorio';
        }
        
        return self::$errores;
    }
}
function existeProveedor(){
    //return true;
    //var_dump($proveedor);
    $proveedor=new Proveedores;
    var_dump($proveedor);
    //$proveedor->setDB=conectarDb();
    //$resultado=$proveedor->find('B87744314');
    return $proveedor->nif;
}




?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?php echo $nombrePagina ?? 'Bienes Raices'; ?></title>
    <link rel="stylesheet" href="estilos.css">
    <script>
        function validarNif(){
            
            //document.getElementById("texto").innerHTML=document.getElementById('nif').value;
            $nif=document.getElementById('nif').value;
            //document.write($nif);
            //exit;
            //$existeProveedor=;
            if(<?php echo existeProveedor(); ?>){
                document.getElementById("texto").innerHTML='Existe';

            }
            
        }
    </script>
</head>
<body>
    <H1>Facturas recibidas</H1>
    <p id=texto></p>
    <form method='POST'>
        <label for='nif' id='lnif' hiden='true'>NIF</label>
        <input id='nif' type='text' placeholder='entre el nif' size='10' onchange='validarNif()'><br>
        <label for='proveedor'>Proveedor</label>
        <input id='proveedor' type='text' placeholder='entre el proveedor' size='50'><br>
        <label for='direccion'>Dirección</label>
        <input id='direccion' type='text' placeholder='entre la direccion' size='50'><br>
        <label for='codigo'>Código postal</label>
        <input id='codigo' type='number' placeholder='entre el código postal' size='10'><br>
        <label for='factura'>Nº de Factura</label>
        <input id='factura' type='text' placeholder='entre el número de factura' size='10'><br>
        <label for='fecha'>Fecha</label>
        <input id='fecha' type='date' placeholder='entre la fecha dd/mm/aa' size='10'><br>
        <label for='base'>Base Imponible</label>
        <input id='base' type='number' placeholder='entre la base imponible' size='10'><br>
        <label for='iva'>IVA</label>
        <input id='iva' type='number' placeholder='entre el iva' size='10'><br>
        <label for='importe'>Importe</label>
        <input id='importe' type='number' placeholder='entre el importe' size='10'><br>
        <input type="submit" value="Entrar factura" class="boton boton-verde">
    </form>

</body>



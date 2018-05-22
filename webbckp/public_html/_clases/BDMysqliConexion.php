<?php

class BDMysqliConexion {
    
    private static $conexion = false;

    public static function conectar($servidor, $basedatos, $usuario, $clave) {

        $charset = "utf8";

        $conexion = @mysqli_connect($servidor, $usuario, $clave, $basedatos);
        
        BDMysqliConexion::$conexion = $conexion;

        @mysqli_set_charset($conexion, $charset);
    }
    
    public static function conexion() {
        return self::$conexion;
    }

}

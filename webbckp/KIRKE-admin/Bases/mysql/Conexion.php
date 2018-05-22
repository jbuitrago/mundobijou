<?php

class Bases_Conexion {

    static public function consulta($servidor, $basedatos, $usuario, $clave) {

        $charset = "utf8";

        $conexion = @mysql_connect($servidor, $usuario, $clave);

        if (!$conexion) {
            die('Configurar la base de datos');
        }

        @mysql_select_db($basedatos);

        if (!function_exists('mysql_set_charset')) {

            function mysql_set_charset($charset, $conexion) {
                return mysql_query("SET NAMES $charset", $conexion);
            }

        }

        @mysql_set_charset($charset, $conexion);
    }

}


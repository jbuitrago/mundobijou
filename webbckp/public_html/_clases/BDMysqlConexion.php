<?php

class BDMysqlConexion {

    static public function conectar($servidor, $basedatos, $usuario, $clave) {

        $charset = "utf8";

        $conexion = @mysql_connect($servidor, $usuario, $clave);

        @mysql_select_db($basedatos);

        if (!function_exists('mysql_set_charset')) {

            function mysql_set_charset($charset, $conexion) {
                return mysql_query("SET NAMES $charset", $conexion);
            }

        }

        @mysql_set_charset($charset, $conexion);
    }

}

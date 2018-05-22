<?php

class BDPostgreConexion {

    static public function conectar($servidor, $basedatos, $usuario, $clave) {

        //$charset = "utf8";
//		$_SESSION['kk_sistema']['kk_pg_conexion'] = pg_connect('host='.$servidor.' dbname='.$basedatos.' user='.$usuario.' password='.$clave);
//echo 'host='.$servidor.' dbname='.$basedatos.' user='.$usuario.' password='.$clave;
        /*
          @mysql_select_db($basedatos);

          if (!function_exists('mysql_set_charset')) {
          function mysql_set_charset($charset,$conexion) {
          return mysql_query("SET NAMES $charset",$conexion);
          }
          }

          @mysql_set_charset($charset,$conexion);
         */
    }

}

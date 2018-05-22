<?php

class BDPostgreObtenerOrden {

    static public function consulta($tabla) {

        $resultado_matriz = pg_fetch_array(pg_query($_SESSION['kk_sistema']['kk_pg_conexion'], 'SELECT MAX(orden) AS orden FROM ' . $tabla . ';'), MYSQL_ASSOC);

        return ($resultado_matriz['orden'] + 1);
    }

}

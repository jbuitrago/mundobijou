<?php

class BDMysqlObtenerOrden {

    static public function consulta($tabla) {

        $resultado_matriz = mysql_fetch_array(mysql_query('SELECT MAX(orden) AS orden FROM ' . $tabla . ';'), MYSQL_ASSOC);

        return ($resultado_matriz['orden'] + 1);
    }

}

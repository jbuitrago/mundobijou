<?php

class BDMysqliObtenerOrden {

    static public function consulta($tabla) {

        $resultado_matriz = mysqli_fetch_array(mysqli_query(BDMysqliConexion::conexion(), 'SELECT MAX(orden) AS orden FROM ' . $tabla . ';'), MYSQLI_ASSOC);

        return ($resultado_matriz['orden'] + 1);
    }

}

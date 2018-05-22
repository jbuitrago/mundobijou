<?php

class Consultas_CambiarOrden {

    static public function armado($tabla, $id_tabla_registro, $campo, $orden, $id_tabla_nombre = null) {

        if (!isset($id_tabla_nombre)) {
            $id_tabla_nombre = 'id_' . $tabla;
        }

        $consulta = new Bases_RegistroModificar(__FILE__, __LINE__);
        $consulta->tabla($tabla);
        $consulta->campoValor($tabla, $campo, $orden);
        $consulta->condiciones('', $tabla, $id_tabla_nombre, 'iguales', '', '', $id_tabla_registro);
        //$consulta->verConsulta();
        $consulta->realizarConsulta();
    }

}


<?php

class Generales_ObtenerValoresTbRegistros {

    static public function armado($tabla_nombre, $matriz_componentes = null, $id_tabla = null) {

        $consulta = new Bases_RegistroConsulta(__FILE__, __LINE__);
        $consulta->tablas($tabla_nombre);

        // obtengo listado de columnas de la tabla
        if ($matriz_componentes) {
            $cantidad_campos = 0;
            foreach ($matriz_componentes as $id => $value) {
                if ($value['tb_campo']) {
                    $consulta->campos($tabla_nombre, $value['tb_campo']);
                    $cantidad_campos++;
                }
            }
            if ($cantidad_campos == 0) {
                return false;
            }
        }

        // realizo una consulta de los valores cargados en la tabla con los campos
        // obtenidos en la matriz anterior
        if ($id_tabla === null) {
            $id_tabla = $_GET['id_tabla_registro'];
        }

        $consulta->condiciones('', $tabla_nombre, 'id_' . $tabla_nombre, 'iguales', '', '', $id_tabla);
        $registro = $consulta->realizarConsulta();

        if (isset($registro[0])) {
            return $registro[0];
        } else {
            return false;
        }
    }

}

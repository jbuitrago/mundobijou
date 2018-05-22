<?php

class Generales_ObtenerValoresTbVariables {

    static public function armado($tabla_nombre, $matriz_componentes = null) {

        // se obtiene los campos de la tabla
        $consulta = new Bases_RegistroConsulta(__FILE__, __LINE__);
        $consulta->tablas($tabla_nombre);
        $consulta->campos($tabla_nombre, 'variables');
        $consulta->campos($tabla_nombre, 'valores');
        $matriz_valores = $consulta->realizarConsulta();

        // armo una matriz ordenada con la matriz anterior
        if (is_array($matriz_valores)) {
            foreach ($matriz_valores as $id => $valor) {
                $matriz_valores2[$valor['variables']] = $valor['valores'];
            }
            return $matriz_valores2;
        }else{
            return false;
        }

    }

}


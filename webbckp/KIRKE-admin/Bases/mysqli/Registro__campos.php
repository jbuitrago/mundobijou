<?php

class Bases_Registro__campos {

    static public function armado($campos = null, $tabla, $nuevo_campo, $etiqueta = null, $tipo = null) {

        if ($campos) {
            $campos_existentes = ',';
        } else {
            $campos_existentes = '';
        }

        if (!$tipo) {
            $campo = ' `' . $tabla . '`.`' . $nuevo_campo . '` ';
        } elseif ($tipo && $tipo == 'maximo') {
            $campo = ' MAX(`' . $tabla . '`.`' . $nuevo_campo . '`) ';
        } elseif ($tipo && $tipo == 'minimo') {
            $campo = ' MIN(`' . $tabla . '`.`' . $nuevo_campo . '`) ';
        } elseif ($tipo && $tipo == 'contador') {
            $campo = ' COUNT(`' . $tabla . '`.`' . $nuevo_campo . '`) ';
        }

        if ($etiqueta) {
            $etiqueta = ' AS ' . $etiqueta . ' ';
        }

        $consulta = $campos . $campos_existentes . $campo . $etiqueta . "\n";

        return $consulta;
    }

}


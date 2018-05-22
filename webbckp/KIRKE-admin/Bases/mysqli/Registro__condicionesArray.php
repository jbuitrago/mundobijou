<?php

class Bases_Registro__condicionesArray {

    static public function armado($operador, $condiciones = null, $condicion = null, $tabla, $campo, $array_datos) {

        if ($operador == 'en') {
            $operador = 'IN';
        } elseif ($operador == 'no_en') {
            $operador = 'NOT IN';
        }

        $condiciones_existentes = self::condiciones($condiciones, $condicion);

        return $condiciones . self::condiciones($condiciones, $condicion) . ' `' . $tabla . '`.`' . $campo . '` ' . $operador . ' (' . implode(',', $array_datos) . ')' . "\n";
    }

    static private function condiciones($condiciones, $condicion) {

        if ($condicion == 'y') {
            $condiciones_existentes = ' AND ';
        } elseif ($condicion == 'o') {
            $condiciones_existentes = ' OR ';
        } else {
            $condiciones_existentes = '';
        }
        return $condiciones_existentes;
    }

}

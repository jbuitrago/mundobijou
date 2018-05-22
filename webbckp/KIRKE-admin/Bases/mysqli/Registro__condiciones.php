<?php

class Bases_Registro__condiciones {

    static private $_agrupacion = '';
    static private $_agrupacion_condicion = '';

    static public function armadoAgrupacionInicio($condiciones = null, $condicion) {
        return $condiciones . self::condiciones($condiciones, $condicion) . "\n" . ' ( ' . "\n";
    }

    static public function armadoAgrupacionFin($condiciones = null) {
        return $condiciones . ' ) ';
    }

    static public function armado($condiciones = null, $condicion = null, $tabla1, $campo1, $relacion, $tabla2 = null, $campo2 = null, $cadena = null) {

        $campo = true;
        $derecha = '';

        $condiciones_existentes = self::condiciones($condiciones, $condicion);

        $cond_izq = ' `' . $tabla1 . '`.`' . $campo1 . '` ';

        if ($tabla2 && $campo2) {
            $derecha = ' `' . $tabla2 . '`.`' . $campo2 . '` ';
        } else {
            $campo = false;
        }

        switch ($relacion) {
            case 'iguales':
                $relacion = ' = ';
                if ($campo == false) {
                    $derecha = " '" . $cadena . "' ";
                }
                break;
            case 'distintos':
                $relacion = ' != ';
                if ($campo == false) {
                    $derecha = " '" . $cadena . "' ";
                }
                break;
            case 'mayor':
                $relacion = ' > ';
                if ($campo == false) {
                    $derecha = " '" . $cadena . "' ";
                }
                break;
            case 'menor':
                $relacion = ' < ';
                if ($campo == false) {
                    $derecha = " '" . $cadena . "' ";
                }
                break;
            case 'mayor_igual':
                $relacion = ' >= ';
                if ($campo == false) {
                    $derecha = " '" . $cadena . "' ";
                }
                break;
            case 'menor_igual':
                $relacion = ' <= ';
                if ($campo == false) {
                    $derecha = " '" . $cadena . "' ";
                }
                break;
            case 'semejante':
                $relacion = " LIKE '" . $cadena . "' ";
                break;
            case 'no_semejante':
                $relacion = " NOT LIKE '" . $cadena . "' ";
                break;
            case 'coincide':
                $relacion = " LIKE '%" . $cadena . "%' ";
                break;
            case 'coincide_izq':
                $relacion = " LIKE '" . $cadena . "%' ";
                break;
            case 'coincide_der':
                $relacion = " LIKE '%" . $cadena . "' ";
                break;
            case 'no_coincide':
                $relacion = " NOT LIKE '%" . $cadena . "%' ";
                break;
            case 'no_coincide_izq':
                $relacion = " NOT LIKE '%" . $cadena . "' ";
                break;
            case 'no_coincide_der':
                $relacion = " NOT LIKE '" . $cadena . "%' ";
                break;
            case 'nulo':
                $relacion = " IS NULL ";
                break;
            case 'no_nulo':
                $relacion = " IS NOT NULL ";
                break;
        }

        return $condiciones . $condiciones_existentes . $cond_izq . $relacion . $derecha . "\n";

    }

    static private function condiciones($condiciones, $condicion) {

        if ($condiciones == null) {
            $condiciones_existentes = ' ';
        } elseif ($condicion == 'y') {
            $condiciones_existentes = ' AND ';
        } elseif ($condicion == 'o') {
            $condiciones_existentes = ' OR ';
        } else {
            $condiciones_existentes = '';
        }
        return $condiciones_existentes;
    }

}

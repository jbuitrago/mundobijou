<?php

class Bases_Registro__tablas {

    static public function armado($tablas, $tablas_der, $tablas_der_diferentes, $condiciones_union, $tablas_der_sub, $tablas_der_diferentes_sub, $condiciones_union_sub) {
        $consulta = '';
        $tablas_existentes = '';
        foreach ($tablas as $tabla) {
            $consulta .= $tablas_existentes . ' `' . $tabla . '` ' . "\n";
            foreach ($tablas_der as $id_union => $tablas_der_datos) {
                if ($tabla == $tablas_der_datos['izq']) {
                    if ($tablas_der_datos['etiqueta'] != '') {
                        $as_tabla = 'AS ' . $tablas_der_datos['etiqueta'];
                    } else {
                        $as_tabla = '';
                    }
                    $consulta .= ' LEFT JOIN `' . $tablas_der_datos['der'] . '` ' . $as_tabla;
                    $consulta .= self::condiciones($id_union, $tablas_der_datos['der'], $condiciones_union, $tablas_der_sub, $tablas_der_sub, $condiciones_union_sub);
                }
            }
            foreach ($tablas_der_diferentes as $id_union => $tablas_der_datos) {
                if ($tabla == $tablas_der_datos['izq']) {
                    if ($tablas_der_datos['etiqueta'] != '') {
                        $as_tabla = 'AS ' . $tablas_der_datos['etiqueta'];
                    } else {
                        $as_tabla = '';
                    }
                    $consulta .= ' INNER JOIN `' . $tablas_der_datos['der'] . '` ' . $as_tabla;
                    $consulta .= self::condiciones($id_union, $tablas_der_datos['der'], $condiciones_union, $tablas_der_sub, $tablas_der_diferentes_sub, $condiciones_union_sub);
                }
            }
            $tablas_existentes = ',';
        }
        return $consulta;
    }

    static private function condiciones($id_union, $tabla, $condiciones_union, $tablas_der_sub = null, $tablas_der_diferentes_sub = null, $condiciones_union_sub = null) {
        $on = ' ON ';
        $consulta = '';
        foreach ($condiciones_union as $id_condiciones => $condiciones) {
            if (isset($tablas_der_sub[$id_union])) {
                $tablas_der_sub_union = $tablas_der_sub[$id_union];
            } else {
                $tablas_der_sub_union = '';
            }
            if (isset($tablas_der_diferentes_sub[$id_union])) {
                $tablas_der_diferentes_sub_union = $tablas_der_diferentes_sub[$id_union];
            } else {
                $tablas_der_diferentes_sub_union = array();
            }
            if (isset($condiciones_union_sub[$id_union])) {
                $condiciones_union_sub_union = $condiciones_union_sub[$id_union];
            } else {
                $condiciones_union_sub_union = array();
            }

            if ($id_union == $id_condiciones) {
                $sub_condiciones = self::armado_sub($tabla, $tablas_der_sub_union, $tablas_der_diferentes_sub_union, $condiciones_union_sub_union);
                if ($sub_condiciones !== false) {
                    $consulta .= $sub_condiciones;
                }
                foreach ($condiciones as $condiciones_texto) {
                    $consulta .= $on . $condiciones_texto;
                    $on = '';
                }
            }
        }
        return $consulta;
    }

    static private function armado_sub($tabla, $tablas_der_sub, $tablas_der_diferentes_sub, $condiciones_union_sub) {

        if ((is_array($tablas_der_sub) && (count($tablas_der_sub) > 0)) || (is_array($tablas_der_diferentes_sub) && (count($tablas_der_diferentes_sub) > 0))) {

            $condicion_sub = '';
            $cond_cont = true;
            if (is_array($tablas_der_sub) && (count($tablas_der_sub) > 0)) {
                foreach ($tablas_der_sub as $tabla_sub) {
                    if ($cond_cont === true) {
                        if ($tabla_sub['etiqueta'] != '') {
                            $as_tabla = 'AS ' . $tabla_sub['etiqueta'];
                        } else {
                            $as_tabla = '';
                        }
                        $condicion_sub .= ' LEFT JOIN `' . $tabla_sub['der'] . '` ' . $as_tabla;
                        $cond_cont = false;
                    }
                    if ($tabla_sub['izq'] == $tabla) {
                        $on = ' ON ';
                        foreach ($condiciones_union_sub[$tabla_sub['id_union_sub']] as $valor_sub_union) {
                            $condicion_sub .= $on . $valor_sub_union;
                            $on = '';
                        }
                    }
                }
            }
            
            $condicion_sub = '';
            $cond_cont = true;
            if (is_array($tablas_der_diferentes_sub) && (count($tablas_der_diferentes_sub) > 0)) {
                foreach ($tablas_der_diferentes_sub as $tabla_sub) {
                    if ($cond_cont === true) {
                        if ($tabla_sub['etiqueta'] != '') {
                            $as_tabla = 'AS ' . $tabla_sub['etiqueta'];
                        } else {
                            $as_tabla = '';
                        }
                        $condicion_sub .= ' INNER JOIN `' . $tabla_sub['der'] . '` ' . $as_tabla;
                        $cond_cont = false;
                    }
                    if ($tabla_sub['izq'] == $tabla) {
                        $on = ' ON ';
                        foreach ($condiciones_union_sub[$tabla_sub['id_union_sub']] as $valor_sub_union) {
                            $condicion_sub .= $on . $valor_sub_union;
                            $on = '';
                        }
                    }
                }
            }

            return $condicion_sub;
        } else {
            return false;
        }
    }

}

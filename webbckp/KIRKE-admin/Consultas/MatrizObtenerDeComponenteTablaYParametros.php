<?php

class Consultas_MatrizObtenerDeComponenteTablaYParametros {

    static private $_consultasRealizadas = Array();

    static public function armado($id_componente = null, $id_tabla = null) {

        if (!isset(self::$_consultasRealizadas['armado'][$id_componente][$id_tabla])) {

            // si $id_componente = 'todos', obtiene los parametros de todos los componente
            // de la tabla actual
            // obtengo los datos de la tabla que contiene al componente
            $consulta = new Bases_RegistroConsulta(__FILE__, __LINE__);
            $consulta->tablas('kirke_tabla');
            $consulta->tablas('kirke_tabla_prefijo');
            $consulta->tablas('kirke_componente');
            $consulta->campos('kirke_tabla', 'id_tabla');
            $consulta->campos('kirke_tabla', 'tabla_nombre');
            $consulta->campos('kirke_tabla', 'tipo');
            $consulta->campos('kirke_tabla_prefijo', 'prefijo');
            $consulta->campos('kirke_componente', 'id_componente');
            $consulta->campos('kirke_componente', 'componente');
            $consulta->campos('kirke_componente', 'tabla_campo');
            $consulta->campos('kirke_componente', 'orden');
            $consulta->campos('kirke_componente', 'orden');
            $consulta->condiciones('y', 'kirke_componente', 'id_tabla', 'iguales', 'kirke_tabla', 'id_tabla');
            $consulta->condiciones('y', 'kirke_tabla', 'id_tabla_prefijo', 'iguales', 'kirke_tabla_prefijo', 'id_tabla_prefijo');
            $consulta->orden('kirke_tabla', 'orden');
            $consulta->orden('kirke_componente', 'orden');

            if (isset($_GET['id_componente'])) {

                $consulta->condiciones('y', 'kirke_componente', 'id_componente', 'iguales', '', '', $_GET['id_componente']);
            } elseif ($id_componente != 'todos') {

                $consulta->condiciones('y', 'kirke_componente', 'id_componente', 'iguales', '', '', $id_componente);
            } elseif (($id_componente == 'todos') && ($id_tabla == NULL)) {

                $consulta->condiciones('y', 'kirke_tabla', 'id_tabla', 'iguales', '', '', $_GET['id_tabla']);
                $consulta->orden('kirke_tabla', 'orden');
            } elseif ($id_tabla != NULL) {

                $consulta->condiciones('y', 'kirke_tabla', 'id_tabla', 'iguales', '', '', $id_tabla);
                $consulta->orden('kirke_tabla', 'orden');
            }

            $matriz = $consulta->realizarConsulta();

            if (is_array($matriz)) {

                foreach ($matriz as $id_cp => $value) {

                    // datos del componente
                    $componente[$id_cp]['cp_id'] = $matriz[$id_cp]['id_componente'];
                    $componente[$id_cp]['cp_nombre'] = $matriz[$id_cp]['componente'];
                    $componente[$id_cp]['cp_orden'] = $matriz[$id_cp]['orden'];

                    // datos de la tabla que contiene al componente
                    $componente[$id_cp]['tb_id'] = $matriz[$id_cp]['id_tabla'];
                    $componente[$id_cp]['tb_nombre'] = $matriz[$id_cp]['tabla_nombre'];
                    $componente[$id_cp]['tb_campo'] = $matriz[$id_cp]['tabla_campo'];
                    $componente[$id_cp]['tb_prefijo'] = $matriz[$id_cp]['prefijo'];
                    $componente[$id_cp]['tb_tipo'] = $matriz[$id_cp]['tipo'];

                    // obtengo los parametros del componente
                    $consulta = new Bases_RegistroConsulta(__FILE__, __LINE__);
                    $consulta->tablas('kirke_componente_parametro');
                    $consulta->campos('kirke_componente_parametro', 'parametro');
                    $consulta->campos('kirke_componente_parametro', 'valor');
                    $consulta->condiciones('y', 'kirke_componente_parametro', 'id_componente', 'iguales', '', '', $componente[$id_cp]['cp_id']);
                    $matriz_cp = $consulta->realizarConsulta();

                    if (is_array($matriz_cp)) {

                        foreach ($matriz_cp as $id => $value) {

                            // obtengo los parametos del componenete
                            if ($matriz_cp[$id]['parametro'] != 'origen_cp_id') {
                                $componente[$id_cp][$matriz_cp[$id]['parametro']] = $matriz_cp[$id]['valor'];
                                // obtengo datos de componente de origen
                            } else {
                                $cp_origen = self::_componenteOrigen($matriz_cp[$id]['valor']);
                                $componente[$id_cp]['origen_cp_id'] = $cp_origen[0]['id_componente'];
                                $componente[$id_cp]['origen_cp_nombre'] = $cp_origen[0]['componente'];
                                $componente[$id_cp]['origen_tb_id'] = $cp_origen[0]['id_tabla'];
                                $componente[$id_cp]['origen_tb_nombre'] = $cp_origen[0]['tabla_nombre'];
                                if (isset($cp_origen[0]['id_prefijo'])) {
                                    $componente[$id_cp]['origen_tb_id_prefijo'] = $cp_origen[0]['id_prefijo'];
                                }
                                $componente[$id_cp]['origen_tb_prefijo'] = $cp_origen[0]['prefijo'];
                                $componente[$id_cp]['origen_tb_campo'] = $cp_origen[0]['tabla_campo'];
                            }
                        }
                    }
                }

                self::$_consultasRealizadas['armado'][$id_componente][$id_tabla] = $componente;
            } else {

                $componente = '';
            }
        } else {

            $componente = self::$_consultasRealizadas['armado'][$id_componente][$id_tabla];
        }

        if ($id_componente != 'todos') {
            if (isset($componente[0])) {
                return $componente[0];
            } else {
                return '';
            }
        } else {
            return $componente;
        }
    }

    static private function _componenteOrigen($id_cp_origen) {

        $consulta = new Bases_RegistroConsulta(__FILE__, __LINE__);
        $consulta->tablas('kirke_tabla');
        $consulta->tablas('kirke_tabla_prefijo');
        $consulta->tablas('kirke_componente');
        $consulta->campos('kirke_tabla', 'id_tabla');
        $consulta->campos('kirke_tabla', 'tabla_nombre');
        $consulta->campos('kirke_tabla_prefijo', 'id_tabla_prefijo');
        $consulta->campos('kirke_tabla_prefijo', 'prefijo');
        $consulta->campos('kirke_componente', 'id_componente');
        $consulta->campos('kirke_componente', 'componente');
        $consulta->campos('kirke_componente', 'tabla_campo');
        $consulta->condiciones('y', 'kirke_componente', 'id_componente', 'iguales', '', '', $id_cp_origen);
        $consulta->condiciones('y', 'kirke_componente', 'id_tabla', 'iguales', 'kirke_tabla', 'id_tabla');
        $consulta->condiciones('y', 'kirke_tabla', 'id_tabla_prefijo', 'iguales', 'kirke_tabla_prefijo', 'id_tabla_prefijo');
        //$consulta->verConsulta();
        $matriz = $consulta->realizarConsulta();

        return $matriz;
    }

}


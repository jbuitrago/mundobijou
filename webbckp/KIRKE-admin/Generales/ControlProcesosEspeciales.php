<?php

class Generales_ControlProcesosEspeciales {

    static private $_cp_id = array();

    static public function control($tipo, $cp_id, $valor = '', $tb_nombre = null, $tb_campo = null, $id=null) {

        $valor_control = self::_valorControl($valor);

        if (file_exists(Inicio::path() . '/ProcesosEspeciales/cpid_' . $cp_id . '.php')) {

            if (isset($_GET['kk_parametro'])) {
                $kk_parametro = $_GET['kk_parametro'];
            } else {
                $kk_parametro = '';
            }

            include_once( Inicio::path() . '/ProcesosEspeciales/cpid_' . $cp_id . '.php' );

            $clase = 'cpid_' . $cp_id;
            $proceso_especial = new $clase();

            if ($tipo == 'valor') {
                $control = $proceso_especial->valor($valor, $tb_nombre, $tb_campo, $kk_parametro, $id);

                if ($control !== false) {
                    self::$_cp_id[$cp_id][$valor_control]['texto'] = $control;
                    self::$_cp_id[$cp_id][$valor_control]['valor'] = $valor;
                    return true;
                } else {
                    return false;
                }
            } elseif ($tipo == 'matriz_links') {
                $control = $proceso_especial->matrizLinks($valor, $tb_nombre, $tb_campo, $kk_parametro, $id);
                if ($control !== false) {
                    self::$_cp_id[$cp_id][$valor_control]['matriz_links'] = $control;
                    return true;
                } else {
                    return false;
                }
            }
        } else {
            return false;
        }
    }

    static public function existeProcesoEspecial($cp_id) {
        if (isset(self::$_cp_id[$cp_id]) && (count(self::$_cp_id[$cp_id]) > 0)) {
            return true;
        } elseif (file_exists(Inicio::path() . '/ProcesosEspeciales/cpid_' . $cp_id . '.php')) {
            return true;
        } else {
            return false;
        }
    }

    static public function texto($cp_id, $valor = '') {
        $valor_control = self::_valorControl($valor);
        if (isset(self::$_cp_id[$cp_id][$valor_control]['texto'])) {
            return self::$_cp_id[$cp_id][$valor_control]['texto'];
        } else {
            return false;
        }
    }

    static public function valor($cp_id, $valor = '') {
        $valor_control = self::_valorControl($valor);
        if (isset(self::$_cp_id[$cp_id][$valor_control]['valor'])) {
            return self::$_cp_id[$cp_id][$valor_control]['valor'];
        } else {
            return false;
        }
    }

    static public function matriz_links($cp_id, $valor = '') {
        $valor_control = self::_valorControl($valor);
        if (isset(self::$_cp_id[$cp_id][$valor_control]['matriz_links'])) {
            return self::$_cp_id[$cp_id][$valor_control]['matriz_links'];
        } else {
            return false;
        }
    }

    static private function _valorControl($valor) {
        if (!is_array($valor)) {
            return $valor;
        } else {
            $valor_control = '';
            foreach ($valor as $key => $valor_array_dato) {
                if (!is_array($valor_array_dato)) {
                    $valor_control .= $key . ';' . $valor_array_dato . ',';
                } else {
                    foreach ($valor_array_dato as $valor_array_sub_dato) {
                        $valor_control .= $key . ';' . $valor_array_sub_dato . ',';
                    }
                }
            }
            return $valor_control;
        }
    }

}

<?php

class BDMysqliConsulta {

    static public function consulta($consulta, $control = NULL) {

        $tipo = '';

        if ($control == 's') {
            if (VariableGet::sistema('cron') == false) {
                $separador = '<br /><br /><br />';
            } else {
                $separador = "\n\n\n";
            }
            echo $separador . 'Consulta : ' . $consulta . $separador;
        }

        $resultado = mysqli_query(BDMysqliConexion::conexion(), $consulta);

        if (mysqli_affected_rows(BDMysqliConexion::conexion()) != 0) {
            $num_lineas = mysqli_affected_rows(BDMysqliConexion::conexion());
        } else {
            $num_lineas = false;
        }
        
        $tipo = strtolower(substr(trim($consulta), 0, 6));

        if (!$resultado) {
            if ($control == 's') {
                echo $separador . 'Error en consulta : ' . mysqli_error(BDMysqliConexion::conexion()) . $separador;
            }
        } else {
            if ($tipo == 'insert') {
                return mysqli_insert_id(BDMysqliConexion::conexion());
            } elseif (($tipo == 'update') || ($tipo == 'delete')) {
                return $num_lineas;
            } elseif (@mysqli_num_rows($resultado)) {
                while ($linea = mysqli_fetch_array($resultado, MYSQLI_ASSOC)) {
                    $resultado_matriz[] = $linea;
                }
                mysqli_free_result($resultado);
                return $resultado_matriz;
            }
        }
    }

    static public function validaciones($valores) {

        if (is_array($valores) && (count($valores) > 0)) {

            foreach ($valores as $k => $v) {

                switch (VariableGet::sistema('tipo_base')) {
                    case 'mysql':
                        $valores[$k] = self::_validacionValor($valores[$k]);
                        break;
                }
            }
        } elseif (!is_array($valores)) {
            $valores = self::_validacionValor($valores);
        }

        return $valores;
    }

    static private function _validacionValor($valor) {

        if (
                is_numeric(substr(trim($valor), 0, 1)) && ( stristr($valor, ' select ') || stristr($valor, ' union ') )
        ) {
            return (int) mysqli_real_escape_string($valor);
        }
        return str_replace(array('<', '>'), array('&lt;', '&gt;'), $valor);
    }

}

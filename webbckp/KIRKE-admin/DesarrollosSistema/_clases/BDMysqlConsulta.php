<?php

class BDMysqlConsulta {

    static public function consulta($consulta, $control = NULL) {

        $tipo = '';

        if ($control == 's') {
            echo 'Consulta : ' . $consulta . '<br /><br /><br />';
        }

        $resultado = mysql_query($consulta);

        if (strtoupper(trim(substr(trim($consulta), 0, 7))) == 'INSERT') {
            $tipo = 'insert';
        } elseif (strtoupper(trim(substr(trim($consulta), 0, 7))) == 'UPDATE') {
            if (mysql_affected_rows() != 0) {
                $num_lineas = mysql_affected_rows();
            } else {
                $num_lineas = false;
            }
            $tipo = 'update';
        }

        if (!$resultado) {
            if ($control == 's') {
                echo 'Error en consulta : ' . mysql_error() . '<br /><br />';
            }
        } else {
            if ($tipo == 'insert') {
                return mysql_insert_id();
            } elseif ($tipo == 'update') {
                return $num_lineas;
            } elseif (@mysql_num_rows($resultado)) {
                while ($linea = mysql_fetch_array($resultado, MYSQL_ASSOC)) {
                    $resultado_matriz[] = $linea;
                }
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
            return (int) mysql_real_escape_string($valor);
        }
        return mysql_real_escape_string(str_replace(array('<', '>'), array('&lt;', '&gt;'), $valor));
    }

}

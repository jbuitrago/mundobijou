<?php

class Armado_PrefijoSelect {

    static public function armado($id_tabla_prefijo = null) {

        $resultado_matriz = Consultas_TablaPrefijo::RegistroConsulta(__FILE__, __LINE__);

        $ver = '<select name="tabla_prefijo">';

        if (is_array($resultado_matriz)) {
            foreach ($resultado_matriz as $id => $valor) {

                if ($id_tabla_prefijo != $resultado_matriz[$id]['id_tabla_prefijo']) {
                    $seleccionado = '';
                } else {
                    $seleccionado = 'selected';
                }
                $ver .= '<option value="' . $resultado_matriz[$id]['id_tabla_prefijo'] . '" ' . $seleccionado . '>' . $resultado_matriz[$id]['prefijo'] . '</option>';
            }
            $ver .= '</select>';

            return $ver;
        } else {
            return false;
        }
    }

}

<?php

class Armado_PaginaSelect {

    static public function armado($nombre_select, $tipo = 'todas') {

        // elementos del menu
        $resultado_matriz = Consultas_TablaNombreIdioma::RegistroConsulta(__FILE__, __LINE__, Generales_Idioma::obtener(), null, $tipo);

        $ver = '<select name="' . $nombre_select . '">';

        if (is_array($resultado_matriz)) {
            foreach ($resultado_matriz as $id => $valor) {
                if (isset($id_tabla_seleccionada) && ($id_tabla_seleccionada != $resultado_matriz[$id]['id_tabla'])) {
                    $seleccionado = '';
                } else {
                    $seleccionado = 'selected';
                }
                $ver .= '<option value="' . $resultado_matriz[$id]['id_tabla'] . '" ' . $seleccionado . '>' . $resultado_matriz[$id]['tabla_nombre_idioma'] . '</option>';
            }
            $ver .= '</select>';
            return $ver;
        } else {
            return false;
        }
    }

}

<?php

class Armado_ProcesosEspecialesSelect extends Armado_Plantilla {

    static public function armar($archivo_seleccionado = null) {

        $directorios = Generales_DirectorioContenido::directorioContenido(Inicio::path() . '/ProcesosEspeciales/');

        if (is_array($directorios)) {
            asort($directorios);

            $proceso_select = '<select name="proceso_especial">';
            $proceso_select .= '<option value="">{TR|o_seleccione_un_proceso}</option>';
            foreach ($directorios as $linea) {

                $linea_array = explode(".", $linea);
                $linea = str_replace("." . end($linea_array), "", $linea);

                if (($archivo_seleccionado !== null) && ($linea == $archivo_seleccionado)) {
                    $seleccionado = 'selected';
                } else {
                    $seleccionado = '';
                }

                $proceso_select .= '<option value="' . $linea . '" ' . $seleccionado . '>' . $linea . ' </option>';
            }
            $proceso_select .= '</select>';

            return $proceso_select;
        } else {

            return false;
        }
    }

}

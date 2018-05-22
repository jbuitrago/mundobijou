<?php

class Armado_EtiquetaIdiomas {

    public static function armar($etiqueta_nombre, $datos_idiomas_cargados = null, $nulo = null, $array = null) {

        $primer_campo = 0;
        $ver = '';
        if ($nulo === null) {
            $nulo = '';
        } else {
            $nulo = ' no_nulo="{TR|o_debe_ingresar_un_dato}"';
        }
        if ($array === null) {
            $array = '';
        } else {
            $array = '[]';
        }
        foreach (Inicio::confVars('idiomas') as $key => $value) {
            if (isset($datos_idiomas_cargados[$value])) {
                $datos_idiomas_cargados_mostrar = $datos_idiomas_cargados[$value];
            } else {
                $datos_idiomas_cargados_mostrar = '';
            }
            $ver .= '<div class="cien_porciento">';
            $ver .= '<input type="text" size="30" name="' . $etiqueta_nombre . '_' . $value . $array . '" id="' . $etiqueta_nombre . '_' . $value . '" value="' . $datos_idiomas_cargados_mostrar . '" ' . $nulo . '>';
            $ver .= '&nbsp;{TR|s_traduccion_' . $value . '}';
            $ver .= '<div id="VC_' . $etiqueta_nombre . '_' . $value . '"></div>';
            $ver .= '</div>';
            $primer_campo++;
        }
        return $ver;
    }

}
